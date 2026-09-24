<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\DutchmanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DutchmanSeeder::class);
    }

    public function test_booking_create_page_renders_barber_chairs_and_services()
    {
        $response = $this->get(route('booking.create'));

        $response->assertStatus(200);
        $response->assertSee('MEJA A1');
        $response->assertSee('MEJA A2');
        $response->assertSee('PILIH LAYANAN');
        $response->assertSee('KIRIM BOOKING SEKARANG', false);
    }

    public function test_booking_store_calculates_total_from_db_and_redirects_to_payment_summary()
    {
        $service = Service::first();
        $barber = Barber::where('chair_code', 'A1')->first();
        $addon = Addon::first();

        $bookingDate = today()->addDay()->format('Y-m-d');

        $response = $this->post(route('booking.store'), [
            'service_id' => $service->id,
            'barber_id' => $barber->id,
            'booking_date' => $bookingDate,
            'booking_time' => '14:00',
            'addon_ids' => [$addon ? $addon->id : null],
            'customer_name' => 'Faris Barber Fan',
            'customer_phone' => '081234567899',
            'customer_email' => 'faris@example.com',
            'notes' => 'Taper fade please',
        ]);

        $this->assertDatabaseHas('bookings', [
            'customer_name' => 'Faris Barber Fan',
            'status' => 'pending',
            'chair_code' => 'A1',
        ]);

        $booking = Booking::where('customer_name', 'Faris Barber Fan')->first();

        $response->assertRedirect(route('booking.summary.before', $booking));
    }

    public function test_summary_before_payment_renders_clean_appointment_breakdown()
    {
        $service = Service::first();
        $barber = Barber::first();

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'customer_name' => 'John Doe',
            'customer_phone' => '081111111111',
            'booking_date' => today()->addDay()->format('Y-m-d'),
            'booking_time' => '15:00:00',
            'barber_id' => $barber->id,
            'chair_code' => 'A2',
            'total_price' => $service->price,
            'duration_minutes' => 60,
            'status' => 'pending',
        ]);

        $response = $this->get(route('booking.summary.before', $booking));

        $response->assertStatus(200);
        $response->assertSee('RINGKASAN BOOKING');
        $response->assertSee('BAYAR SEKARANG');
        $response->assertSee('MEJA A2');
    }

    public function test_pay_endpoint_generates_snap_token()
    {
        $service = Service::first();
        $barber = Barber::first();

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081298765432',
            'customer_email' => 'budi@example.com',
            'booking_date' => today()->addDay()->format('Y-m-d'),
            'booking_time' => '16:00:00',
            'barber_id' => $barber->id,
            'chair_code' => 'A3',
            'total_price' => $service->price,
            'duration_minutes' => 45,
            'status' => 'pending',
        ]);

        $response = $this->postJson(route('booking.pay', $booking));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'token',
            'snap_token',
            'order_id',
        ]);

        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'status' => 'pending',
        ]);
    }

    public function test_dedicated_token_route_also_generates_snap_token()
    {
        $service = Service::first();
        $barber = Barber::first();

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'customer_name' => 'Dimas Token',
            'customer_phone' => '081298765433',
            'booking_date' => today()->addDay()->format('Y-m-d'),
            'booking_time' => '16:30:00',
            'barber_id' => $barber->id,
            'chair_code' => 'A1',
            'total_price' => $service->price,
            'duration_minutes' => 45,
            'status' => 'pending',
        ]);

        $response = $this->postJson(route('payment.midtrans.token', $booking));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertNotEmpty($response->json('snap_token'));
    }

    public function test_midtrans_notification_webhook_settlement_updates_booking_to_confirmed()
    {
        $service = Service::first();
        $barber = Barber::first();

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'customer_name' => 'Dimas Webhook',
            'customer_phone' => '081299998888',
            'booking_date' => today()->addDay()->format('Y-m-d'),
            'booking_time' => '17:00:00',
            'barber_id' => $barber->id,
            'chair_code' => 'A1',
            'total_price' => $service->price,
            'duration_minutes' => 45,
            'status' => 'pending',
        ]);

        $orderId = $booking->booking_number;
        $grossAmount = number_format($service->price, 2, '.', '');
        $serverKey = config('midtrans.server_key') ?: '';
        $signature = hash('sha512', $orderId . '200' . $grossAmount . $serverKey);

        Payment::create([
            'booking_id' => $booking->id,
            'order_id' => $orderId,
            'transaction_id' => 'TRANS-MIDTRANS-999',
            'amount' => $service->price,
            'status' => 'pending',
        ]);

        $payload = [
            'order_id' => $orderId,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'payment_type' => 'qris',
            'status_code' => '200',
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
        ];

        // Test web route /payment/midtrans/notification
        $response = $this->postJson(route('payment.midtrans.notification'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $orderId,
            'status' => 'paid',
            'payment_method' => 'qris',
        ]);
    }

    public function test_midtrans_notification_idempotency_does_not_duplicate_paid_record()
    {
        $service = Service::first();
        $barber = Barber::first();

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'customer_name' => 'Idempotent Customer',
            'customer_phone' => '081299997777',
            'booking_date' => today()->addDay()->format('Y-m-d'),
            'booking_time' => '17:30:00',
            'barber_id' => $barber->id,
            'chair_code' => 'A1',
            'total_price' => $service->price,
            'duration_minutes' => 45,
            'status' => 'confirmed',
        ]);

        $orderId = $booking->booking_number;
        $grossAmount = number_format($service->price, 2, '.', '');
        $serverKey = config('midtrans.server_key') ?: '';
        $signature = hash('sha512', $orderId . '200' . $grossAmount . $serverKey);

        Payment::create([
            'booking_id' => $booking->id,
            'order_id' => $orderId,
            'transaction_id' => 'TRANS-MIDTRANS-IDEMPOTENT',
            'amount' => $service->price,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $payload = [
            'order_id' => $orderId,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'payment_type' => 'qris',
            'status_code' => '200',
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
        ];

        // Send duplicate notification
        $response = $this->postJson(route('payment.midtrans.notification'), $payload);

        $response->assertStatus(200);
        $this->assertEquals(1, Payment::where('order_id', $orderId)->count());
        $this->assertEquals('paid', $booking->fresh()->payment->status);
    }

    public function test_midtrans_notification_expire_cancels_booking()
    {
        $service = Service::first();
        $barber = Barber::first();

        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'customer_name' => 'Expired User',
            'customer_phone' => '081299996666',
            'booking_date' => today()->addDay()->format('Y-m-d'),
            'booking_time' => '18:00:00',
            'barber_id' => $barber->id,
            'chair_code' => 'A1',
            'total_price' => $service->price,
            'duration_minutes' => 45,
            'status' => 'pending',
        ]);

        $orderId = $booking->booking_number;
        $grossAmount = number_format($service->price, 2, '.', '');
        $serverKey = config('midtrans.server_key') ?: '';
        $signature = hash('sha512', $orderId . '200' . $grossAmount . $serverKey);

        Payment::create([
            'booking_id' => $booking->id,
            'order_id' => $orderId,
            'amount' => $service->price,
            'status' => 'pending',
        ]);

        $payload = [
            'order_id' => $orderId,
            'transaction_status' => 'expire',
            'status_code' => '200',
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
        ];

        $response = $this->postJson(route('payment.midtrans.notification'), $payload);

        $response->assertStatus(200);
        $this->assertEquals('expired', $booking->fresh()->payment->status);
        $this->assertEquals('cancelled', $booking->fresh()->status);
    }

    public function test_success_pending_and_summary_pages_render()
    {
        $barber = Barber::first();
        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'customer_name' => 'Andre Kurniawan',
            'customer_phone' => '081333444555',
            'booking_date' => today()->addDay()->format('Y-m-d'),
            'booking_time' => '13:00:00',
            'barber_id' => $barber->id,
            'chair_code' => 'A1',
            'total_price' => 70000,
            'duration_minutes' => 45,
            'status' => 'confirmed',
        ]);

        $successRes = $this->get(route('booking.success', $booking));
        $successRes->assertStatus(200);
        $successRes->assertSee('RESERVASI BERHASIL');
        $successRes->assertSee($booking->booking_number);

        $pendingRes = $this->get(route('booking.pending', $booking));
        $pendingRes->assertStatus(200);
        $pendingRes->assertSee('MENUNGGU PEMBAYARAN');

        $summaryRes = $this->get(route('booking.summary', $booking));
        $summaryRes->assertStatus(200);
        $summaryRes->assertSee('RINGKASAN RESERVASI');
        $summaryRes->assertSee('MEJA A1');
        $summaryRes->assertSee('SIMPAN KE KALENDER');
    }

    public function test_admin_notifications_api_returns_recent_bookings()
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->getJson(route('admin.notifications'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'count',
            'notifications',
        ]);
        $response->assertJson(['success' => true]);
    }

    public function test_chairs_api_warns_when_chair_is_booked()
    {
        $barber = Barber::where('chair_code', 'A1')->first();
        $date = today()->addDay()->format('Y-m-d');
        $time = '14:00';

        Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'customer_name' => 'Existing Customer',
            'customer_phone' => '081234567890',
            'booking_date' => $date,
            'booking_time' => $time,
            'barber_id' => $barber->id,
            'chair_code' => 'A1',
            'total_price' => 100000,
            'duration_minutes' => 45,
            'status' => 'confirmed',
        ]);

        $response = $this->getJson(route('api.chairs', ['date' => $date, 'time' => $time]));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $chairs = collect($response->json('chairs'));
        $a1 = $chairs->firstWhere('chair_code', 'A1');
        $this->assertNotNull($a1);
        $this->assertEquals('booked', $a1['status']);
        $this->assertEquals('Kursi sudah dibooking', $a1['badge_text']);
        $this->assertFalse($a1['available']);
    }
}

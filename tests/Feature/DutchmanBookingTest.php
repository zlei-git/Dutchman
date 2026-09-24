<?php

namespace Tests\Feature;

use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\DutchmanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DutchmanBookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DutchmanSeeder::class);
    }

    public function test_home_page_loads_with_dutchman_branding()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('DUTCHMAN');
        $response->assertSee('POTONGAN RAPI');
        $response->assertSee('PERCAYA DIRI SEJATI');
        $response->assertSee('THE DUTCHMAN EXPERIENCE');
        $response->assertSee('RESERVASI SEKARANG');
    }

    public function test_about_page_loads_with_brand_philosophy_and_kenapa_harus_kami()
    {
        $response = $this->get(route('about'));
        $response->assertStatus(200);
        $response->assertSee('MORE THAN JUST A HAIRCUT');
        $response->assertSee('BUILT AROUND CLASSIC BARBERING');
        $response->assertSee('KENAPA HARUS KAMI');
        $response->assertSee('BUKAN SEKADAR POTONG RAMBUT');
        $response->assertSee('GOOD GROOMING');
        $response->assertSee('READY FOR YOUR NEXT CUT?');
        $response->assertDontSee('KUNJUNGI STUDIO RUNGKUT SURABAYA');
    }

    public function test_services_and_barbers_pages_load_successfully()
    {
        $serviceResponse = $this->get(route('services.index'));
        $serviceResponse->assertStatus(200);
        $serviceResponse->assertSee('Layanan &amp; Harga', false);

        $barberResponse = $this->get(route('barbers.index'));
        $barberResponse->assertStatus(200);
        $barberResponse->assertSee('Barber');
    }

    public function test_map_page_loads_with_dutchman_location_details()
    {
        $response = $this->get(route('map'));
        $response->assertStatus(200);
        $response->assertSee('TEMUKAN LOKASI');
        $response->assertSee('Jl. Rungkut Madya No.55A');
    }

    public function test_available_slots_api_returns_json_schedule()
    {
        $response = $this->getJson(route('api.slots', [
            'date' => today()->addDay()->format('Y-m-d')
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'date',
            'barber_id',
            'slots' => [
                '*' => ['time', 'available', 'reason']
            ]
        ]);
        $response->assertJson([
            'success' => true
        ]);
    }

    public function test_booking_creation_succeeds_and_generates_reference_number()
    {
        $service = Service::active()->first();
        $barber = Barber::active()->first();
        $targetDate = today()->addDays(2)->format('Y-m-d');
        $targetTime = '11:00';

        $payload = [
            'service_id' => $service->id,
            'barber_id' => $barber->id,
            'booking_date' => $targetDate,
            'booking_time' => $targetTime,
            'customer_name' => 'Julian Casablancas',
            'customer_phone' => '081298765432',
            'customer_email' => 'julian@test.com',
            'notes' => 'Texture crop with mid taper',
        ];

        $response = $this->post(route('booking.store'), $payload);

        $booking = Booking::where('customer_phone', '081298765432')->first();
        $this->assertNotNull($booking);
        $this->assertStringStartsWith('DTC-', $booking->booking_number);
        $this->assertEquals($service->price, $booking->total_price);
        $this->assertEquals($barber->id, $booking->barber_id);

        $response->assertRedirect(route('booking.summary.before', $booking));
    }

    public function test_booking_collision_prevention_rejects_duplicate_slot()
    {
        $service = Service::active()->first();
        $barber = Barber::active()->first();
        $targetDate = today()->addDays(3)->format('Y-m-d');
        $targetTime = '14:00';

        // 1. Create first booking
        $this->post(route('booking.store'), [
            'service_id' => $service->id,
            'barber_id' => $barber->id,
            'booking_date' => $targetDate,
            'booking_time' => $targetTime,
            'customer_name' => 'First Customer',
            'customer_phone' => '08111111111',
        ])->assertSessionHasNoErrors();

        // 2. Attempt duplicate booking for same barber and same slot
        $response = $this->post(route('booking.store'), [
            'service_id' => $service->id,
            'barber_id' => $barber->id,
            'booking_date' => $targetDate,
            'booking_time' => $targetTime,
            'customer_name' => 'Second Customer',
            'customer_phone' => '08222222222',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_any_available_barber_auto_assigns_open_barber()
    {
        $service = Service::active()->first();
        $targetDate = today()->addDays(4)->format('Y-m-d');
        $targetTime = '15:30';

        $response = $this->post(route('booking.store'), [
            'service_id' => $service->id,
            'barber_id' => null, // "Any Available Barber"
            'booking_date' => $targetDate,
            'booking_time' => $targetTime,
            'customer_name' => 'Auto Assign Guest',
            'customer_phone' => '08333333333',
        ]);

        $booking = Booking::where('customer_phone', '08333333333')->first();
        $this->assertNotNull($booking);
        $this->assertNotNull($booking->barber_id); // Successfully auto-assigned to an active barber
        $response->assertRedirect(route('booking.summary.before', $booking));
    }

    public function test_customer_portal_displays_bookings_and_allows_cancellation()
    {
        $user = User::where('email', 'customer@demo.test')->first();
        $service = Service::active()->first();

        // Create booking for this user
        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_phone' => $user->phone ?? '081234567890',
            'customer_email' => $user->email,
            'booking_date' => today()->addDays(5),
            'booking_time' => '16:00:00',
            'status' => 'confirmed',
            'total_price' => $service->price,
        ]);

        $response = $this->actingAs($user)->get(route('user.bookings.index'));
        $response->assertStatus(200);
        $response->assertSee($booking->booking_number);

        // Cancel the appointment
        $cancelResponse = $this->actingAs($user)->post(route('user.bookings.cancel', $booking->id));
        $cancelResponse->assertSessionHas('success');

        $this->assertEquals('cancelled', $booking->fresh()->status);
    }

    public function test_admin_can_access_dashboard_and_update_status()
    {
        $admin = User::where('email', 'admin@demo.test')->first();
        $booking = Booking::first();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Ringkasan Studio');

        $updateResponse = $this->actingAs($admin)->put(route('admin.bookings.status', $booking->id), [
            'status' => 'completed',
        ]);
        $updateResponse->assertSessionHas('success');
        $this->assertEquals('completed', $booking->fresh()->status);
    }

    public function test_non_admin_cannot_access_admin_dashboard()
    {
        $user = User::where('email', 'customer@demo.test')->first();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        // role:admin middleware aborts with 403
        $response->assertStatus(403);
    }

    public function test_login_page_renders_username_and_password_placeholders()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('placeholder="Username"', false);
        $response->assertSee('placeholder="Password"', false);
    }

    public function test_user_can_login_with_username_or_email()
    {
        $response = $this->post(route('login.post'), [
            'email' => 'admin@demo.test',
            'password' => 'password',
        ]);
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        $this->post(route('logout'));

        $response2 = $this->post(route('login.post'), [
            'email' => 'admin',
            'password' => 'password',
        ]);
        $response2->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        $this->post(route('logout'));

        $response3 = $this->post(route('login.post'), [
            'email' => 'user',
            'password' => 'password',
        ]);
        $response3->assertRedirect(route('home'));
        $this->assertAuthenticated();
    }
}

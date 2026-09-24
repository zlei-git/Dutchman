<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalkenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $cat = Category::create([
            'name' => 'Sneakers',
            'slug' => 'sneakers',
            'status' => true,
        ]);

        $product = Product::create([
            'category_id' => $cat->id,
            'name' => 'WALKEN Test Sneaker',
            'slug' => 'walken-test-sneaker',
            'description' => 'Test product description',
            'price' => 799000,
            'discount_price' => 699000,
            'brand' => 'WALKEN',
            'status' => true,
            'is_featured' => true,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'size' => '42',
            'color' => 'Triple Black',
            'stock' => 10,
            'sku' => 'TEST-42-TB',
        ]);

        Branch::create([
            'name' => 'WALKEN Central',
            'slug' => 'walken-central',
            'phone' => '021-23580001',
            'address' => 'Grand Indonesia',
            'city' => 'Jakarta Pusat',
            'opening_time' => '10:00:00',
            'closing_time' => '22:00:00',
            'slot_capacity' => 4,
            'status' => true,
        ]);
    }

    public function test_home_page_is_successful(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Walk Your Way');
    }

    public function test_about_page_is_successful(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('WALKEN');
    }

    public function test_product_catalog_and_detail(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertSee('WALKEN Test Sneaker');

        $detailResponse = $this->get('/products/walken-test-sneaker');
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('WALKEN Test Sneaker');
        $detailResponse->assertSee('Add to Cart');
    }

    public function test_booking_slots_api(): void
    {
        $branch = Branch::first();
        $response = $this->getJson("/api/booking-slots?branch_id={$branch->id}&date=" . date('Y-m-d'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'branch', 'slots']);
    }

    public function test_admin_access_control(): void
    {
        // Guest cannot access admin
        $guestResponse = $this->get('/admin/dashboard');
        $guestResponse->assertRedirect('/login');

        // Normal user gets 403
        $user = User::factory()->create(['role' => 'user']);
        $userResponse = $this->actingAs($user)->get('/admin/dashboard');
        $userResponse->assertStatus(403);

        // Admin gets 200
        $admin = User::factory()->create(['role' => 'admin']);
        $adminResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $adminResponse->assertStatus(200);
    }
}

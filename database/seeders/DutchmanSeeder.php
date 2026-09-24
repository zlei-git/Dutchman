<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\BookingAddon;
use App\Models\BookingItem;
use App\Models\Payment;
use App\Models\Promotion;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DutchmanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.test'],
            [
                'name' => 'Dutchman Admin',
                'phone' => '0812-9988-7711',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('password'),
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@demo.test'],
            [
                'name' => 'Budi Santoso',
                'phone' => '0812-3456-7890',
                'role' => 'user',
                'status' => 'active',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Barbers (with simple chair codes: A1, A2, A3, A4)
        $barbersData = [
            [
                'name' => 'Andre',
                'chair_code' => 'A1',
                'slug' => 'andre',
                'specialty' => 'Fade & Texture',
                'experience_years' => 7,
                'bio' => 'Master of low-skin fades, tapers, and textured scissor crops with razor precision edge detailing.',
                'photo' => 'images/barbershop/barber-andre.jpg',
                'sort_order' => 1,
            ],
            [
                'name' => 'Thomas',
                'chair_code' => 'A2',
                'slug' => 'thomas',
                'specialty' => 'Classic Pompadour & Beard Sculpting',
                'experience_years' => 10,
                'bio' => 'Specializes in timeless gentlemen silhouettes, executive side parts, and precision hot-towel beard sculpting.',
                'photo' => 'images/barbershop/barber-thomas.jpg',
                'sort_order' => 2,
            ],
            [
                'name' => 'Julian',
                'chair_code' => 'A3',
                'slug' => 'julian',
                'specialty' => 'Traditional Shave & Scissor Work',
                'experience_years' => 8,
                'bio' => 'Dedicated to traditional European hot lather straight-razor shaves and bespoke layered shear trims.',
                'photo' => 'images/barbershop/barber-julian.jpg',
                'sort_order' => 3,
            ],
            [
                'name' => 'Marcus',
                'chair_code' => 'A4',
                'slug' => 'marcus',
                'specialty' => 'Modern Crop & Hair Design',
                'experience_years' => 6,
                'bio' => 'Blends contemporary street styling with disciplined European barbering craft and hair flow aesthetics.',
                'photo' => 'images/barbershop/barber-marcus.jpg',
                'sort_order' => 4,
            ],
        ];

        $barbers = [];
        foreach ($barbersData as $data) {
            $barbers[$data['slug']] = Barber::updateOrCreate(['slug' => $data['slug']], $data);
        }

        // 3. Services (Authentic Dutchman Price List from Menu Book)
        $servicesData = [
            // Category: Haircut
            [
                'name' => 'Mens Haircut',
                'slug' => 'mens-haircut',
                'category' => 'Haircut',
                'description' => 'Cukur, wash, hair tonic, styling pomade / powder',
                'price' => 100000,
                'duration_minutes' => 45,
                'badge' => 'SIGNATURE',
                'photo' => 'images/barbershop/service-shave.jpg',
                'sort_order' => 1,
            ],
            [
                'name' => 'Kids Haircut',
                'slug' => 'kids-haircut',
                'category' => 'Haircut',
                'description' => 'Cukur, wash, hair tonic, styling pomade / powder',
                'price' => 90000,
                'duration_minutes' => 35,
                'badge' => null,
                'photo' => 'images/barbershop/service-shave.jpg',
                'sort_order' => 2,
            ],
            [
                'name' => 'Grooming',
                'slug' => 'grooming',
                'category' => 'Haircut',
                'description' => 'Cukur, wash, hair tonic, face mask, styling pomade / powder',
                'price' => 140000,
                'duration_minutes' => 50,
                'badge' => 'POPULAR',
                'photo' => 'images/barbershop/service-shave.jpg',
                'sort_order' => 3,
            ],
            [
                'name' => 'Gentlemen Grooming',
                'slug' => 'gentlemen-grooming',
                'category' => 'Haircut',
                'description' => 'Cukur, wash, hair tonic, shaving, styling pomade / powder',
                'price' => 140000,
                'duration_minutes' => 50,
                'badge' => 'POPULAR',
                'photo' => 'images/barbershop/service-shave.jpg',
                'sort_order' => 4,
            ],
            [
                'name' => 'Gentlemen Premium',
                'slug' => 'gentlemen-premium',
                'category' => 'Haircut',
                'description' => 'Cukur, wash, hair tonic, shaving, face mask, styling pomade / powder',
                'price' => 170000,
                'duration_minutes' => 60,
                'badge' => 'BEST VALUE',
                'photo' => 'images/barbershop/service-shave.jpg',
                'sort_order' => 5,
            ],

            // Category: Trim & Shave
            [
                'name' => 'Beard & Trim',
                'slug' => 'beard-trim',
                'category' => 'Trim & Shave',
                'description' => 'Penataan dan pemangkasan jenggot & kumis rapi',
                'price' => 50000,
                'duration_minutes' => 25,
                'badge' => null,
                'photo' => 'images/barbershop/service-shave.jpg',
                'sort_order' => 6,
            ],
            [
                'name' => 'Outline',
                'slug' => 'outline',
                'category' => 'Trim & Shave',
                'description' => 'Penegasan garis tepi rambut, pelipis, dan leher belakang',
                'price' => 25000,
                'duration_minutes' => 15,
                'badge' => null,
                'photo' => 'images/barbershop/service-beard.jpg',
                'sort_order' => 7,
            ],
            [
                'name' => 'Classic Shave',
                'slug' => 'classic-shave',
                'category' => 'Trim & Shave',
                'description' => 'Shaving with razor, pre shave foam, hot towel',
                'price' => 60000,
                'duration_minutes' => 30,
                'badge' => 'TRADITIONAL',
                'photo' => 'images/barbershop/service-shave.jpg',
                'sort_order' => 8,
            ],

            // Category: Hair Tattoo & Styling
            [
                'name' => 'Hair Tattoo',
                'slug' => 'hair-tattoo',
                'category' => 'Hair Tattoo & Styling',
                'description' => 'Mens haircut & Hair tattoo (130 K - 170 K)',
                'price' => 130000,
                'duration_minutes' => 55,
                'badge' => 'ARTISTIC',
                'photo' => 'images/barbershop/service-haircut.jpg',
                'sort_order' => 9,
            ],
            [
                'name' => 'Hair Styling',
                'slug' => 'hair-styling',
                'category' => 'Hair Tattoo & Styling',
                'description' => 'Styling rambut profesional dengan pomade / hair powder premium',
                'price' => 40000,
                'duration_minutes' => 15,
                'badge' => null,
                'photo' => 'images/barbershop/service-haircut.jpg',
                'sort_order' => 10,
            ],

            // Category: Treatment (Hair & Scalp)
            [
                'name' => 'Creambath',
                'slug' => 'creambath',
                'category' => 'Treatment',
                'description' => 'Berfungsi menutrisi akar rambut dan menyuburkan pertumbuhan rambut',
                'price' => 80000,
                'duration_minutes' => 40,
                'badge' => 'RELAXATION',
                'photo' => 'images/barbershop/service-treatment.jpg',
                'sort_order' => 11,
            ],
            [
                'name' => 'Hair Loss Serum Treatment',
                'slug' => 'hair-loss-serum-treatment',
                'category' => 'Treatment',
                'description' => 'Solusi untuk mengurangi rambut rontok dan tipis',
                'price' => 35000,
                'duration_minutes' => 20,
                'badge' => null,
                'photo' => 'images/barbershop/service-treatment.jpg',
                'sort_order' => 12,
            ],
            [
                'name' => 'Damaged Hair Treatment',
                'slug' => 'damaged-hair-treatment',
                'category' => 'Treatment',
                'description' => 'Treatment untuk mengembalikan kesehatan rambut akibat paparan sinar matahari dan bahan kimia',
                'price' => 140000,
                'duration_minutes' => 45,
                'badge' => null,
                'photo' => 'images/barbershop/service-treatment.jpg',
                'sort_order' => 13,
            ],

            // Category: Face Mask
            [
                'name' => 'Charcoal Face Mask',
                'slug' => 'charcoal-face-mask',
                'category' => 'Face Mask',
                'description' => 'Treatment untuk menyerap minyak wajah serta membuat kulit tampak segar dan cerah',
                'price' => 50000,
                'duration_minutes' => 25,
                'badge' => null,
                'photo' => 'images/barbershop/fakta-dutchman.jpg',
                'sort_order' => 14,
            ],
            [
                'name' => 'Gold Face Mask',
                'slug' => 'gold-face-mask',
                'category' => 'Face Mask',
                'description' => 'Treatment untuk mencerahkan wajah dan menghidrasi kulit sehingga kulit menjadi lembab',
                'price' => 50000,
                'duration_minutes' => 25,
                'badge' => 'SPECIAL',
                'photo' => 'images/barbershop/fakta-dutchman.jpg',
                'sort_order' => 15,
            ],

            // Category: Coloring
            [
                'name' => 'Black Hair Color',
                'slug' => 'black-hair-color',
                'category' => 'Coloring',
                'description' => 'Pewarnaan hitam natural merata untuk rambut pria',
                'price' => 150000,
                'duration_minutes' => 45,
                'badge' => null,
                'photo' => 'images/barbershop/service-haircut.jpg',
                'sort_order' => 16,
            ],
            [
                'name' => 'Basic Hair Color',
                'slug' => 'basic-hair-color',
                'category' => 'Coloring',
                'description' => 'Pewarnaan warna dasar (Brown, Burgundy, Chestnut)',
                'price' => 300000,
                'duration_minutes' => 60,
                'badge' => null,
                'photo' => 'images/barbershop/service-haircut.jpg',
                'sort_order' => 17,
            ],
            [
                'name' => 'Highlight Fashion Color',
                'slug' => 'highlight-fashion-color',
                'category' => 'Coloring',
                'description' => 'Pewarnaan highlight aksen modern bertekstur',
                'price' => 500000,
                'duration_minutes' => 75,
                'badge' => null,
                'photo' => 'images/barbershop/service-haircut.jpg',
                'sort_order' => 18,
            ],
            [
                'name' => 'Fashion Hair Color',
                'slug' => 'fashion-hair-color',
                'category' => 'Coloring',
                'description' => 'Pewarnaan fashion penuh (Full fashion bleach & tone)',
                'price' => 700000,
                'duration_minutes' => 120,
                'badge' => null,
                'photo' => 'images/barbershop/service-haircut.jpg',
                'sort_order' => 19,
            ],
        ];

        $services = [];
        foreach ($servicesData as $data) {
            $services[$data['slug']] = Service::updateOrCreate(['slug' => $data['slug']], $data);
        }

        // 4. Promotions
        Promotion::updateOrCreate(
            ['code' => 'DUTCH10'],
            [
                'discount_percent' => 10,
                'discount_amount' => 0,
                'valid_from' => now()->subDay(),
                'valid_until' => now()->addMonths(3),
                'is_active' => true,
            ]
        );

        Promotion::updateOrCreate(
            ['code' => 'GENTLEMAN20'],
            [
                'discount_percent' => 0,
                'discount_amount' => 20000,
                'valid_from' => now()->subDay(),
                'valid_until' => now()->addMonths(3),
                'is_active' => true,
            ]
        );

        // 4.1 Add-ons & Beverage Menu (Dutchman Coffee Brew - Hot & Ice)
        $addonsData = [
            // HOT COFFEE & CHOCOLATE (Sorted by price ascending)
            [
                'name' => 'Hot Americano',
                'category' => 'Hot',
                'price' => 14000,
                'description' => 'Classic hot espresso diluted with hot water for a clean, bold coffee notes.',
                'photo' => 'images/barbershop/hot-americano.jpg',
                'sort_order' => 1,
            ],
            [
                'name' => 'Hot Long Black',
                'category' => 'Hot',
                'price' => 14000,
                'description' => 'Double shot espresso extracted over hot water, preserving full rich crema.',
                'photo' => 'images/barbershop/hot-long-black.jpg',
                'sort_order' => 2,
            ],
            [
                'name' => 'Hot Cappuccino',
                'category' => 'Hot',
                'price' => 15000,
                'description' => 'Rich espresso with velvety steamed milk and smooth micro-foam.',
                'photo' => 'images/barbershop/hot-cappuccino.jpg',
                'sort_order' => 3,
            ],
            [
                'name' => 'Hot Dark Chocolate',
                'category' => 'Hot',
                'price' => 17000,
                'description' => 'Recommended rich Dutch dark cocoa brewed warm and comforting.',
                'photo' => 'images/barbershop/hot-chocolate.jpg',
                'sort_order' => 4,
            ],

            // ICE COFFEE & SPECIALTY BEVERAGES (Sorted by price ascending)
            [
                'name' => 'Ice Americano',
                'category' => 'Ice',
                'price' => 15000,
                'description' => 'Crisp espresso poured over chilled mineral water and ice rocks.',
                'photo' => 'images/barbershop/ice-americano.jpg',
                'sort_order' => 5,
            ],
            [
                'name' => 'Ice Long Black',
                'category' => 'Ice',
                'price' => 15000,
                'description' => 'Chilled double shot espresso with deep roasted notes and lively aroma.',
                'photo' => 'images/barbershop/ice-longblack.jpg',
                'sort_order' => 6,
            ],
            [
                'name' => 'Ice Cappuccino',
                'category' => 'Ice',
                'price' => 16000,
                'description' => 'Balanced espresso and fresh creamy milk served over ice.',
                'photo' => 'images/barbershop/ice-cappuccino.jpg',
                'sort_order' => 7,
            ],
            [
                'name' => 'Ice Dark Chocolate',
                'category' => 'Ice',
                'price' => 18000,
                'description' => 'Decadent dark chocolate blend served icy cold and refreshing.',
                'photo' => 'images/barbershop/ice-chocolate.jpg',
                'sort_order' => 8,
            ],
            [
                'name' => 'Vanilla Milkshake',
                'category' => 'Ice',
                'price' => 18000,
                'description' => 'Creamy Madagascar vanilla blend whipped cold and smooth.',
                'photo' => 'images/barbershop/vanilla-milkshake.jpg',
                'sort_order' => 9,
            ],
            [
                'name' => 'Brown Sugar Coffee',
                'category' => 'Ice',
                'price' => 19000,
                'description' => 'Signature Dutchman iced coffee with authentic aromatic brown sugar.',
                'photo' => 'images/barbershop/brown-sugar-coffee.jpg',
                'sort_order' => 10,
            ],
            [
                'name' => 'Airish Coffee',
                'category' => 'Ice',
                'price' => 19000,
                'description' => 'Specialty Irish-style cream iced coffee blend with deep herbal notes.',
                'photo' => 'images/barbershop/airish-coffee.jpg',
                'sort_order' => 11,
            ],
            [
                'name' => 'Sweet Mango',
                'category' => 'Ice',
                'price' => 19000,
                'description' => 'Luscious tropical mango delight served icy cold and uplifting.',
                'photo' => 'images/barbershop/sweet-mango.jpg',
                'sort_order' => 12,
            ],
        ];

        foreach ($addonsData as $addon) {
            Addon::updateOrCreate(['name' => $addon['name']], $addon);
        }

        // 5. Sample Bookings (Today & Upcoming)
        $today = today();
        $sampleBookings = [
            [
                'booking_number' => 'DTC-260923-001',
                'user_id' => $customer->id,
                'barber_id' => $barbers['andre']->id,
                'booking_date' => $today,
                'booking_time' => '10:00',
                'duration_minutes' => 45,
                'total_price' => 100000,
                'status' => 'confirmed',
                'customer_name' => 'Budi Santoso',
                'customer_phone' => '0812-3456-7890',
                'customer_email' => 'customer@demo.test',
                'notes' => 'Low taper fade, keep length on top.',
                'service_slug' => 'mens-haircut',
            ],
            [
                'booking_number' => 'DTC-260923-002',
                'user_id' => null,
                'barber_id' => $barbers['thomas']->id,
                'booking_date' => $today,
                'booking_time' => '11:00',
                'duration_minutes' => 60,
                'total_price' => 170000,
                'status' => 'confirmed',
                'customer_name' => 'Rangga Wijaya',
                'customer_phone' => '0813-8822-1100',
                'customer_email' => null,
                'notes' => 'Full beard sculpting and side parting.',
                'service_slug' => 'gentlemen-premium',
            ],
            [
                'booking_number' => 'DTC-260923-003',
                'user_id' => null,
                'barber_id' => $barbers['julian']->id,
                'booking_date' => $today,
                'booking_time' => '13:30',
                'duration_minutes' => 30,
                'total_price' => 60000,
                'status' => 'pending',
                'customer_name' => 'Daniel Pratama',
                'customer_phone' => '0817-4433-2211',
                'customer_email' => null,
                'notes' => 'Hot towel shave before an evening event.',
                'service_slug' => 'classic-shave',
            ],
            [
                'booking_number' => 'DTC-260923-004',
                'user_id' => null,
                'barber_id' => $barbers['marcus']->id,
                'booking_date' => $today,
                'booking_time' => '15:00',
                'duration_minutes' => 45,
                'total_price' => 100000,
                'status' => 'completed',
                'customer_name' => 'Kevin Sanjaya',
                'customer_phone' => '0818-1234-5678',
                'customer_email' => null,
                'notes' => 'Textured crop with natural matte clay.',
                'service_slug' => 'mens-haircut',
            ],
            [
                'booking_number' => 'DTC-260923-005',
                'user_id' => $customer->id,
                'barber_id' => $barbers['andre']->id,
                'booking_date' => $today->copy()->addDays(2),
                'booking_time' => '14:00',
                'duration_minutes' => 45,
                'total_price' => 100000,
                'status' => 'confirmed',
                'customer_name' => 'Budi Santoso',
                'customer_phone' => '0812-3456-7890',
                'customer_email' => 'customer@demo.test',
                'notes' => 'Routine touch-up before weekend.',
                'service_slug' => 'mens-haircut',
            ],
        ];

        foreach ($sampleBookings as $bData) {
            $serviceSlug = $bData['service_slug'];
            unset($bData['service_slug']);

            $booking = Booking::updateOrCreate(['booking_number' => $bData['booking_number']], $bData);

            $svc = $services[$serviceSlug];
            BookingItem::firstOrCreate([
                'booking_id' => $booking->id,
                'service_id' => $svc->id,
            ], [
                'price' => $svc->price,
                'duration' => $svc->duration_minutes,
            ]);
        }

        // 6. Sample Reviews
        Review::firstOrCreate([
            'barber_id' => $barbers['andre']->id,
            'rating' => 5,
            'comment' => 'Andre is phenomenal. Best skin fade in town, clean environment and quiet luxury atmosphere.',
        ]);

        Review::firstOrCreate([
            'barber_id' => $barbers['thomas']->id,
            'rating' => 5,
            'comment' => 'The beard trim and hot towel service was impeccably done. Truly relaxing experience.',
        ]);
    }
}

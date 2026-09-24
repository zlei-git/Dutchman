-- Dutchman Barbershop PostgreSQL Schema & Data for Supabase
-- Compatible with Supabase cloud (no superuser privileges required)

-- -----------------------------------------------------
-- Table: users
-- -----------------------------------------------------
DROP TABLE IF EXISTS "users" CASCADE;
CREATE TABLE "users" (
  "id" BIGSERIAL,
  "name" varchar(255) NOT NULL,
  "email" varchar(255) NOT NULL,
  "phone" varchar(255),
  "role" varchar(255) DEFAULT 'user',
  "avatar" varchar(255),
  "status" varchar(255) DEFAULT 'active',
  "email_verified_at" TIMESTAMP WITHOUT TIME ZONE,
  "password" varchar(255) NOT NULL,
  "remember_token" varchar(100),
  "created_at" TIMESTAMP WITHOUT TIME ZONE,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE,
  PRIMARY KEY ("id")
);

INSERT INTO "users" ("id", "name", "email", "phone", "role", "avatar", "status", "email_verified_at", "password", "remember_token", "created_at", "updated_at") VALUES
  (1, 'Dutchman Admin', 'admin@demo.test', '0812-9988-7711', 'admin', NULL, 'active', NULL, '$2y$12$wqvewyWO6U/ZpKIIl0f1rOMUxHAGiIhH.FdYioF/JsJOarqwRep7a', NULL, '2026-09-23 06:23:30', '2026-09-23 06:23:30'),
  (2, 'Budi Santoso', 'customer@demo.test', '0812-3456-7890', 'user', NULL, 'active', NULL, '$2y$12$ZDokHBcIgxx/lk0zkMoM8OChXYHlVYqQfrzeXyhigu8BYpuzrtQ8i', NULL, '2026-09-23 06:23:31', '2026-09-23 06:23:31');

-- -----------------------------------------------------
-- Table: password_reset_tokens
-- -----------------------------------------------------
DROP TABLE IF EXISTS "password_reset_tokens" CASCADE;
CREATE TABLE "password_reset_tokens" (
  "email" varchar(255) NOT NULL,
  "token" varchar(255) NOT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE,
  PRIMARY KEY ("email")
);

-- -----------------------------------------------------
-- Table: sessions
-- -----------------------------------------------------
DROP TABLE IF EXISTS "sessions" CASCADE;
CREATE TABLE "sessions" (
  "id" varchar(255) NOT NULL,
  "user_id" BIGINT,
  "ip_address" varchar(45),
  "user_agent" TEXT,
  "payload" TEXT NOT NULL,
  "last_activity" INTEGER NOT NULL,
  PRIMARY KEY ("id")
);

-- -----------------------------------------------------
-- Table: cache
-- -----------------------------------------------------
DROP TABLE IF EXISTS "cache" CASCADE;
CREATE TABLE "cache" (
  "key" varchar(255) NOT NULL,
  "value" TEXT NOT NULL,
  "expiration" BIGINT NOT NULL,
  PRIMARY KEY ("key")
);

-- -----------------------------------------------------
-- Table: cache_locks
-- -----------------------------------------------------
DROP TABLE IF EXISTS "cache_locks" CASCADE;
CREATE TABLE "cache_locks" (
  "key" varchar(255) NOT NULL,
  "owner" varchar(255) NOT NULL,
  "expiration" BIGINT NOT NULL,
  PRIMARY KEY ("key")
);

-- -----------------------------------------------------
-- Table: jobs
-- -----------------------------------------------------
DROP TABLE IF EXISTS "jobs" CASCADE;
CREATE TABLE "jobs" (
  "id" BIGSERIAL,
  "queue" varchar(255) NOT NULL,
  "payload" TEXT NOT NULL,
  "attempts" INTEGER NOT NULL,
  "reserved_at" INTEGER,
  "available_at" INTEGER NOT NULL,
  "created_at" INTEGER NOT NULL,
  PRIMARY KEY ("id")
);

-- -----------------------------------------------------
-- Table: job_batches
-- -----------------------------------------------------
DROP TABLE IF EXISTS "job_batches" CASCADE;
CREATE TABLE "job_batches" (
  "id" varchar(255) NOT NULL,
  "name" varchar(255) NOT NULL,
  "total_jobs" INTEGER NOT NULL,
  "pending_jobs" INTEGER NOT NULL,
  "failed_jobs" INTEGER NOT NULL,
  "failed_job_ids" TEXT NOT NULL,
  "options" TEXT,
  "cancelled_at" INTEGER,
  "created_at" INTEGER NOT NULL,
  "finished_at" INTEGER,
  PRIMARY KEY ("id")
);

-- -----------------------------------------------------
-- Table: failed_jobs
-- -----------------------------------------------------
DROP TABLE IF EXISTS "failed_jobs" CASCADE;
CREATE TABLE "failed_jobs" (
  "id" BIGSERIAL,
  "uuid" varchar(255) NOT NULL,
  "connection" varchar(255) NOT NULL,
  "queue" varchar(255) NOT NULL,
  "payload" TEXT NOT NULL,
  "exception" TEXT NOT NULL,
  "failed_at" TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("id")
);

-- -----------------------------------------------------
-- Table: services
-- -----------------------------------------------------
DROP TABLE IF EXISTS "services" CASCADE;
CREATE TABLE "services" (
  "id" BIGSERIAL,
  "name" varchar(255) NOT NULL,
  "category" varchar(255),
  "slug" varchar(255) NOT NULL,
  "description" TEXT,
  "price" decimal(10,2) NOT NULL,
  "duration_minutes" INTEGER DEFAULT 45,
  "badge" varchar(255),
  "photo" varchar(255),
  "is_active" BOOLEAN DEFAULT TRUE,
  "sort_order" INTEGER DEFAULT 0,
  "created_at" TIMESTAMP WITHOUT TIME ZONE,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE,
  PRIMARY KEY ("id")
);

INSERT INTO "services" ("id", "name", "category", "slug", "description", "price", "duration_minutes", "badge", "photo", "is_active", "sort_order", "created_at", "updated_at") VALUES
  (1, 'The Dutchman Classic Haircut', NULL, 'dutchman-classic-haircut', 'Full consultation, tailored precision scissor & clipper cut, warm neck lather shave, hair rinse, and pomade styling finish.', 95000.00, 45, 'SIGNATURE', 'images/barbershop/service-shave.jpg', TRUE, 1, '2026-09-23 06:23:31', '2026-09-24 03:09:03'),
  (2, 'Beard Trim & Detail Sculpting', NULL, 'beard-trim-detail', 'Length sculpting, clean cheek and neckline definition with foil shaver, hot towel wrap, and organic cedarwood beard oil.', 65000.00, 30, 'POPULAR', 'images/barbershop/service-shave.jpg', TRUE, 2, '2026-09-23 06:23:31', '2026-09-24 03:09:03'),
  (3, 'The Full Gentleman (Haircut + Beard)', NULL, 'full-gentleman-grooming', 'Complete transformation: bespoke precision haircut, hot towel beard sculpting, relaxing neck massage, and tailored styling.', 145000.00, 60, 'BEST VALUE', 'images/barbershop/service-shave.jpg', TRUE, 3, '2026-09-23 06:23:31', '2026-09-24 03:09:03'),
  (4, 'Traditional Hot Towel Wet Shave', NULL, 'hot-towel-wet-shave', 'Pre-shave essential oils, double hot towel wrap, warm badger brush lather, straight-razor shave, and soothing cold towel finish.', 75000.00, 35, NULL, 'images/barbershop/service-shave.jpg', TRUE, 4, '2026-09-23 06:23:31', '2026-09-24 03:09:03'),
  (5, 'Junior / Student Haircut', NULL, 'junior-student-haircut', 'Sharp, clean, and easy-to-style cut tailored for students and young gentlemen under 18 years old.', 75000.00, 35, NULL, 'images/barbershop/service-shave.jpg', TRUE, 5, '2026-09-23 06:23:31', '2026-09-24 03:09:03'),
  (6, 'Scalp Refresh Treatment & Massage', NULL, 'scalp-refresh-massage', 'Invigorating tea tree scalp exfoliation, deep wash, stimulating head and shoulder massage, and natural blow-dry style.', 85000.00, 40, NULL, 'images/barbershop/service-shave.jpg', TRUE, 6, '2026-09-23 06:23:31', '2026-09-24 03:09:03'),
  (7, 'Gentleman Facial Steam & Masker Relaksasi', NULL, 'facial-steam-masker', 'Perawatan uap hangat wajah (facial steaming), masker pembersih pori, kompres mata relaksasi, dan pijat kepala menyegarkan khas Dutchman.', 85000.00, 40, 'SPECIAL TREATMENT', 'images/barbershop/service-shave.jpg', TRUE, 4, '2026-09-23 11:47:37', '2026-09-24 03:09:03'),
  (8, 'Mens Haircut', 'Haircut', 'mens-haircut', 'Cukur, wash, hair tonic, styling pomade / powder', 100000.00, 45, 'SIGNATURE', 'images/barbershop/service-shave.jpg', TRUE, 1, '2026-09-23 12:15:52', '2026-09-24 03:09:03'),
  (9, 'Kids Haircut', 'Haircut', 'kids-haircut', 'Cukur, wash, hair tonic, styling pomade / powder', 90000.00, 35, NULL, 'images/barbershop/service-shave.jpg', TRUE, 2, '2026-09-23 12:15:52', '2026-09-24 03:09:03'),
  (10, 'Grooming', 'Haircut', 'grooming', 'Cukur, wash, hair tonic, face mask, styling pomade / powder', 140000.00, 50, 'POPULAR', 'images/barbershop/service-shave.jpg', TRUE, 3, '2026-09-23 12:15:52', '2026-09-24 03:09:03'),
  (11, 'Gentlemen Grooming', 'Haircut', 'gentlemen-grooming', 'Cukur, wash, hair tonic, shaving, styling pomade / powder', 140000.00, 50, 'POPULAR', 'images/barbershop/service-shave.jpg', TRUE, 4, '2026-09-23 12:15:52', '2026-09-24 03:09:03'),
  (12, 'Gentlemen Premium', 'Haircut', 'gentlemen-premium', 'Cukur, wash, hair tonic, shaving, face mask, styling pomade / powder', 170000.00, 60, 'BEST VALUE', 'images/barbershop/service-shave.jpg', TRUE, 5, '2026-09-23 12:15:52', '2026-09-24 03:09:03'),
  (13, 'Beard & Trim', 'Trim & Shave', 'beard-trim', 'Penataan dan pemangkasan jenggot & kumis rapi', 50000.00, 25, NULL, 'images/barbershop/service-shave.jpg', TRUE, 6, '2026-09-23 12:15:52', '2026-09-24 03:09:03'),
  (14, 'Outline', 'Trim & Shave', 'outline', 'Penegasan garis tepi rambut, pelipis, dan leher belakang', 25000.00, 15, NULL, 'images/barbershop/service-beard.jpg', TRUE, 7, '2026-09-23 12:15:52', '2026-09-24 03:09:57'),
  (15, 'Classic Shave', 'Trim & Shave', 'classic-shave', 'Shaving with razor, pre shave foam, hot towel', 60000.00, 30, 'TRADITIONAL', 'images/barbershop/service-shave.jpg', TRUE, 8, '2026-09-23 12:15:52', '2026-09-24 03:09:03'),
  (16, 'Hair Tattoo', 'Hair Tattoo & Styling', 'hair-tattoo', 'Mens haircut & Hair tattoo (130 K - 170 K)', 130000.00, 55, 'ARTISTIC', 'images/barbershop/service-haircut.jpg', TRUE, 9, '2026-09-23 12:15:52', '2026-09-24 03:09:57'),
  (17, 'Hair Styling', 'Hair Tattoo & Styling', 'hair-styling', 'Styling rambut profesional dengan pomade / hair powder premium', 40000.00, 15, NULL, 'images/barbershop/service-haircut.jpg', TRUE, 10, '2026-09-23 12:15:52', '2026-09-24 03:09:57'),
  (18, 'Creambath', 'Treatment', 'creambath', 'Berfungsi menutrisi akar rambut dan menyuburkan pertumbuhan rambut', 80000.00, 40, 'RELAXATION', 'images/barbershop/service-treatment.jpg', TRUE, 11, '2026-09-23 12:15:52', '2026-09-24 03:09:57'),
  (19, 'Hair Loss Serum Treatment', 'Treatment', 'hair-loss-serum-treatment', 'Solusi untuk mengurangi rambut rontok dan tipis', 35000.00, 20, NULL, 'images/barbershop/service-treatment.jpg', TRUE, 12, '2026-09-23 12:15:52', '2026-09-24 03:09:57'),
  (20, 'Damaged Hair Treatment', 'Treatment', 'damaged-hair-treatment', 'Treatment untuk mengembalikan kesehatan rambut akibat paparan sinar matahari dan bahan kimia', 140000.00, 45, NULL, 'images/barbershop/service-treatment.jpg', TRUE, 13, '2026-09-23 12:15:52', '2026-09-24 03:09:57'),
  (21, 'Charcoal Face Mask', 'Face Mask', 'charcoal-face-mask', 'Treatment untuk menyerap minyak wajah serta membuat kulit tampak segar dan cerah', 50000.00, 25, NULL, 'images/barbershop/fakta-dutchman.jpg', TRUE, 14, '2026-09-23 12:15:52', '2026-09-24 03:09:57'),
  (22, 'Gold Face Mask', 'Face Mask', 'gold-face-mask', 'Treatment untuk mencerahkan wajah dan menghidrasi kulit sehingga kulit menjadi lembab', 50000.00, 25, 'SPECIAL', 'images/barbershop/fakta-dutchman.jpg', TRUE, 15, '2026-09-23 12:15:52', '2026-09-24 03:09:57'),
  (23, 'Black Hair Color', 'Coloring', 'black-hair-color', 'Pewarnaan hitam natural merata untuk rambut pria', 150000.00, 45, NULL, 'images/barbershop/service-haircut.jpg', TRUE, 16, '2026-09-23 12:15:52', '2026-09-24 03:09:57'),
  (24, 'Basic Hair Color', 'Coloring', 'basic-hair-color', 'Pewarnaan warna dasar (Brown, Burgundy, Chestnut)', 300000.00, 60, NULL, 'images/barbershop/service-haircut.jpg', TRUE, 17, '2026-09-23 12:15:52', '2026-09-24 03:09:57'),
  (25, 'Highlight Fashion Color', 'Coloring', 'highlight-fashion-color', 'Pewarnaan highlight aksen modern bertekstur', 500000.00, 75, NULL, 'images/barbershop/service-haircut.jpg', TRUE, 18, '2026-09-23 12:15:52', '2026-09-24 03:09:57'),
  (26, 'Fashion Hair Color', 'Coloring', 'fashion-hair-color', 'Pewarnaan fashion penuh (Full fashion bleach & tone)', 700000.00, 120, NULL, 'images/barbershop/service-haircut.jpg', TRUE, 19, '2026-09-23 12:15:52', '2026-09-24 03:09:57');

-- -----------------------------------------------------
-- Table: barbers
-- -----------------------------------------------------
DROP TABLE IF EXISTS "barbers" CASCADE;
CREATE TABLE "barbers" (
  "id" BIGSERIAL,
  "name" varchar(255) NOT NULL,
  "chair_code" varchar(10) DEFAULT 'A1',
  "is_maintenance" BOOLEAN DEFAULT FALSE,
  "slug" varchar(255) NOT NULL,
  "specialty" varchar(255) NOT NULL,
  "experience_years" INTEGER DEFAULT 5,
  "bio" TEXT,
  "photo" varchar(255),
  "is_active" BOOLEAN DEFAULT TRUE,
  "sort_order" INTEGER DEFAULT 0,
  "created_at" TIMESTAMP WITHOUT TIME ZONE,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE,
  PRIMARY KEY ("id")
);

INSERT INTO "barbers" ("id", "name", "chair_code", "is_maintenance", "slug", "specialty", "experience_years", "bio", "photo", "is_active", "sort_order", "created_at", "updated_at") VALUES
  (1, 'Andre', 'A1', FALSE, 'andre', 'Fade & Texture', 7, 'Master of low-skin fades, tapers, and textured scissor crops with razor precision edge detailing.', 'images/barbershop/barber-andre.jpg', TRUE, 1, '2026-09-23 06:23:31', '2026-09-23 06:23:31'),
  (2, 'Thomas', 'A2', FALSE, 'thomas', 'Classic Pompadour & Beard Sculpting', 10, 'Specializes in timeless gentlemen silhouettes, executive side parts, and precision hot-towel beard sculpting.', 'images/barbershop/barber-thomas.jpg', TRUE, 2, '2026-09-23 06:23:31', '2026-09-23 14:14:55'),
  (3, 'Julian', 'A3', FALSE, 'julian', 'Traditional Shave & Scissor Work', 8, 'Dedicated to traditional European hot lather straight-razor shaves and bespoke layered shear trims.', 'images/barbershop/barber-julian.jpg', TRUE, 3, '2026-09-23 06:23:31', '2026-09-23 14:14:55'),
  (4, 'Marcus', 'A4', FALSE, 'marcus', 'Modern Crop & Hair Design', 6, 'Blends contemporary street styling with disciplined European barbering craft and hair flow aesthetics.', 'images/barbershop/barber-marcus.jpg', TRUE, 4, '2026-09-23 06:23:31', '2026-09-23 14:14:55');

-- -----------------------------------------------------
-- Table: addons
-- -----------------------------------------------------
DROP TABLE IF EXISTS "addons" CASCADE;
CREATE TABLE "addons" (
  "id" BIGSERIAL,
  "name" varchar(255) NOT NULL,
  "category" varchar(50) DEFAULT 'drink',
  "price" decimal(10,2) DEFAULT 0.00,
  "description" TEXT,
  "photo" varchar(255),
  "is_active" BOOLEAN DEFAULT TRUE,
  "sort_order" INTEGER DEFAULT 0,
  "created_at" TIMESTAMP WITHOUT TIME ZONE,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE,
  PRIMARY KEY ("id")
);

INSERT INTO "addons" ("id", "name", "category", "price", "description", "photo", "is_active", "sort_order", "created_at", "updated_at") VALUES
  (5, 'Hot Americano', 'Hot', 14000.00, 'Classic hot espresso diluted with hot water for a clean, bold coffee notes.', 'images/barbershop/hot-americano.jpg', TRUE, 1, '2026-09-24 02:27:43', '2026-09-24 02:27:43'),
  (6, 'Hot Long Black', 'Hot', 14000.00, 'Double shot espresso extracted over hot water, preserving full rich crema.', 'images/barbershop/hot-long-black.jpg', TRUE, 2, '2026-09-24 02:27:43', '2026-09-24 02:27:43'),
  (7, 'Hot Cappuccino', 'Hot', 15000.00, 'Rich espresso with velvety steamed milk and smooth micro-foam.', 'images/barbershop/hot-cappuccino.jpg', TRUE, 3, '2026-09-24 02:27:43', '2026-09-24 02:27:43'),
  (8, 'Hot Dark Chocolate', 'Hot', 17000.00, 'Recommended rich Dutch dark cocoa brewed warm and comforting.', 'images/barbershop/hot-chocolate.jpg', TRUE, 4, '2026-09-24 02:27:43', '2026-09-24 02:27:43'),
  (9, 'Ice Americano', 'Ice', 15000.00, 'Crisp espresso poured over chilled mineral water and ice rocks.', 'images/barbershop/ice-americano.jpg', TRUE, 5, '2026-09-24 02:27:43', '2026-09-24 02:27:43'),
  (10, 'Ice Long Black', 'Ice', 15000.00, 'Chilled double shot espresso with deep roasted notes and lively aroma.', 'images/barbershop/ice-longblack.jpg', TRUE, 6, '2026-09-24 02:27:43', '2026-09-24 02:27:43'),
  (11, 'Ice Cappuccino', 'Ice', 16000.00, 'Balanced espresso and fresh creamy milk served over ice.', 'images/barbershop/ice-cappuccino.jpg', TRUE, 7, '2026-09-24 02:27:43', '2026-09-24 02:27:43'),
  (12, 'Ice Dark Chocolate', 'Ice', 18000.00, 'Decadent dark chocolate blend served icy cold and refreshing.', 'images/barbershop/ice-chocolate.jpg', TRUE, 8, '2026-09-24 02:27:43', '2026-09-24 02:27:43'),
  (13, 'Vanilla Milkshake', 'Ice', 18000.00, 'Creamy Madagascar vanilla blend whipped cold and smooth.', 'images/barbershop/vanilla-milkshake.jpg', TRUE, 9, '2026-09-24 02:27:43', '2026-09-24 02:27:43'),
  (14, 'Brown Sugar Coffee', 'Ice', 19000.00, 'Signature Dutchman iced coffee with authentic aromatic brown sugar.', 'images/barbershop/brown-sugar-coffee.jpg', TRUE, 10, '2026-09-24 02:27:43', '2026-09-24 02:27:43'),
  (15, 'Airish Coffee', 'Ice', 19000.00, 'Specialty Irish-style cream iced coffee blend with deep herbal notes.', 'images/barbershop/airish-coffee.jpg', TRUE, 11, '2026-09-24 02:27:43', '2026-09-24 02:27:43'),
  (16, 'Sweet Mango', 'Ice', 19000.00, 'Luscious tropical mango delight served icy cold and uplifting.', 'images/barbershop/sweet-mango.jpg', TRUE, 12, '2026-09-24 02:27:43', '2026-09-24 02:27:43');

-- -----------------------------------------------------
-- Table: promotions
-- -----------------------------------------------------
DROP TABLE IF EXISTS "promotions" CASCADE;
CREATE TABLE "promotions" (
  "id" BIGSERIAL,
  "code" varchar(255) NOT NULL,
  "discount_percent" INTEGER DEFAULT 0,
  "discount_amount" decimal(10,2) DEFAULT 0.00,
  "valid_from" DATE,
  "valid_until" DATE,
  "is_active" BOOLEAN DEFAULT TRUE,
  "created_at" TIMESTAMP WITHOUT TIME ZONE,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE,
  PRIMARY KEY ("id")
);

INSERT INTO "promotions" ("id", "code", "discount_percent", "discount_amount", "valid_from", "valid_until", "is_active", "created_at", "updated_at") VALUES
  (1, 'DUTCH10', 10, '0.00', '2026-09-23', '2026-12-24', TRUE, '2026-09-23 06:23:31', '2026-09-24 03:09:57'),
  (2, 'GENTLEMAN20', '0', 20000.00, '2026-09-23', '2026-12-24', TRUE, '2026-09-23 06:23:31', '2026-09-24 03:09:57');

-- -----------------------------------------------------
-- Table: bookings
-- -----------------------------------------------------
DROP TABLE IF EXISTS "bookings" CASCADE;
CREATE TABLE "bookings" (
  "id" BIGSERIAL,
  "booking_number" varchar(255) NOT NULL,
  "user_id" BIGINT,
  "barber_id" BIGINT,
  "chair_code" varchar(10),
  "booking_date" DATE NOT NULL,
  "booking_time" varchar(10) NOT NULL,
  "duration_minutes" INTEGER DEFAULT 45,
  "total_price" decimal(10,2) DEFAULT 0.00,
  "status" varchar(20) DEFAULT 'confirmed',
  "customer_name" varchar(255) NOT NULL,
  "customer_phone" varchar(255) NOT NULL,
  "customer_email" varchar(255),
  "notes" TEXT,
  "admin_notes" TEXT,
  "created_at" TIMESTAMP WITHOUT TIME ZONE,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE,
  PRIMARY KEY ("id")
);

INSERT INTO "bookings" ("id", "booking_number", "user_id", "barber_id", "chair_code", "booking_date", "booking_time", "duration_minutes", "total_price", "status", "customer_name", "customer_phone", "customer_email", "notes", "admin_notes", "created_at", "updated_at") VALUES
  (1, 'DTC-260923-001', 2, 1, NULL, '2026-09-24', '10:00', 45, 100000.00, 'confirmed', 'Budi Santoso', '0812-3456-7890', 'customer@demo.test', 'Low taper fade, keep length on top.', NULL, '2026-09-23 06:23:31', '2026-09-24 02:27:43'),
  (2, 'DTC-260923-002', NULL, 2, NULL, '2026-09-24', '11:00', 60, 170000.00, 'confirmed', 'Rangga Wijaya', '0813-8822-1100', NULL, 'Full beard sculpting and side parting.', NULL, '2026-09-23 06:23:31', '2026-09-24 02:27:43'),
  (3, 'DTC-260923-003', NULL, 3, NULL, '2026-09-24', '13:30', 30, 60000.00, 'pending', 'Daniel Pratama', '0817-4433-2211', NULL, 'Hot towel shave before an evening event.', NULL, '2026-09-23 06:23:31', '2026-09-24 02:27:43'),
  (4, 'DTC-260923-004', NULL, 4, NULL, '2026-09-24', '15:00', 45, 100000.00, 'completed', 'Kevin Sanjaya', '0818-1234-5678', NULL, 'Textured crop with natural matte clay.', NULL, '2026-09-23 06:23:31', '2026-09-24 02:27:43'),
  (5, 'DTC-260923-005', 2, 1, NULL, '2026-09-26', '14:00', 45, 100000.00, 'confirmed', 'Budi Santoso', '0812-3456-7890', 'customer@demo.test', 'Routine touch-up before weekend.', NULL, '2026-09-23 06:23:31', '2026-09-24 02:27:43'),
  (6, 'DTC-260923-05AB', 2, 1, NULL, '2026-09-24', '11:00', 45, 95000.00, 'cancelled', 'Budi Santoso', '0812-3456-7890', 'customer@demo.test', NULL, 'Dibatalkan oleh pelanggan pada 24 Sep 2026 06:45', '2026-09-23 13:54:14', '2026-09-24 06:45:24'),
  (7, 'DTC-260923-FC35', 2, 1, 'A1', '2026-09-23', '18:00', 45, 110000.00, 'confirmed', 'Budi Santoso', '0812-3456-7890', 'customer@demo.test', NULL, NULL, '2026-09-23 15:26:28', '2026-09-23 15:27:03'),
  (8, 'DTC-260924-23F2', 2, 1, 'A1', '2026-09-24', '11:00', 45, 114000.00, 'confirmed', 'Budi Santoso', '0812-3456-7890', 'customer@demo.test', NULL, NULL, '2026-09-24 07:21:17', '2026-09-24 07:21:29'),
  (9, 'DTC-260924-8B86', 2, 1, 'A1', '2026-09-26', '11:30', 45, 109000.00, 'pending', 'Budi Santoso', '0812-3456-7890', 'customer@demo.test', NULL, NULL, '2026-09-24 08:30:06', '2026-09-24 08:30:06'),
  (10, 'DTC-260924-AF6C', 2, 3, 'A3', '2026-09-24', '11:00', 45, 109000.00, 'pending', 'Budi Santoso', '0812-3456-7890', 'customer@demo.test', NULL, NULL, '2026-09-24 08:30:24', '2026-09-24 08:30:24');

-- -----------------------------------------------------
-- Table: booking_items
-- -----------------------------------------------------
DROP TABLE IF EXISTS "booking_items" CASCADE;
CREATE TABLE "booking_items" (
  "id" BIGSERIAL,
  "booking_id" BIGINT NOT NULL,
  "service_id" BIGINT NOT NULL,
  "price" decimal(10,2) NOT NULL,
  "duration" INTEGER NOT NULL,
  "created_at" TIMESTAMP WITHOUT TIME ZONE,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE,
  PRIMARY KEY ("id")
);

INSERT INTO "booking_items" ("id", "booking_id", "service_id", "price", "duration", "created_at", "updated_at") VALUES
  (1, 1, 1, 95000.00, 45, '2026-09-23 06:23:31', '2026-09-23 06:23:31'),
  (2, 2, 3, 145000.00, 60, '2026-09-23 06:23:31', '2026-09-23 06:23:31'),
  (3, 3, 4, 75000.00, 35, '2026-09-23 06:23:31', '2026-09-23 06:23:31'),
  (4, 4, 1, 95000.00, 45, '2026-09-23 06:23:31', '2026-09-23 06:23:31'),
  (5, 5, 1, 95000.00, 45, '2026-09-23 06:23:31', '2026-09-23 06:23:31'),
  (6, 1, 8, 100000.00, 45, '2026-09-23 12:16:32', '2026-09-23 12:16:32'),
  (7, 2, 12, 170000.00, 60, '2026-09-23 12:16:32', '2026-09-23 12:16:32'),
  (8, 3, 15, 60000.00, 30, '2026-09-23 12:16:32', '2026-09-23 12:16:32'),
  (9, 4, 8, 100000.00, 45, '2026-09-23 12:16:32', '2026-09-23 12:16:32'),
  (10, 5, 8, 100000.00, 45, '2026-09-23 12:16:32', '2026-09-23 12:16:32'),
  (11, 6, 1, 95000.00, 45, '2026-09-23 13:54:14', '2026-09-23 13:54:14'),
  (12, 7, 1, 95000.00, 45, '2026-09-23 15:26:28', '2026-09-23 15:26:28'),
  (13, 8, 8, 100000.00, 45, '2026-09-24 07:21:17', '2026-09-24 07:21:17'),
  (14, 9, 1, 95000.00, 45, '2026-09-24 08:30:06', '2026-09-24 08:30:06'),
  (15, 10, 1, 95000.00, 45, '2026-09-24 08:30:24', '2026-09-24 08:30:24');

-- -----------------------------------------------------
-- Table: booking_addons
-- -----------------------------------------------------
DROP TABLE IF EXISTS "booking_addons" CASCADE;
CREATE TABLE "booking_addons" (
  "id" BIGSERIAL,
  "booking_id" BIGINT NOT NULL,
  "addon_id" BIGINT,
  "name" varchar(255) NOT NULL,
  "price" decimal(10,2) NOT NULL,
  "quantity" INTEGER DEFAULT 1,
  "created_at" TIMESTAMP WITHOUT TIME ZONE,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE,
  PRIMARY KEY ("id")
);

INSERT INTO "booking_addons" ("id", "booking_id", "addon_id", "name", "price", "quantity", "created_at", "updated_at") VALUES
  (1, 7, NULL, 'Iced Coffee', 15000.00, 1, '2026-09-23 15:26:28', '2026-09-23 15:26:28'),
  (2, 8, 5, 'Hot Americano', 14000.00, 1, '2026-09-24 07:21:17', '2026-09-24 07:21:17'),
  (3, 9, 6, 'Hot Long Black', 14000.00, 1, '2026-09-24 08:30:06', '2026-09-24 08:30:06'),
  (4, 10, 6, 'Hot Long Black', 14000.00, 1, '2026-09-24 08:30:24', '2026-09-24 08:30:24');

-- -----------------------------------------------------
-- Table: reviews
-- -----------------------------------------------------
DROP TABLE IF EXISTS "reviews" CASCADE;
CREATE TABLE "reviews" (
  "id" BIGSERIAL,
  "booking_id" BIGINT,
  "user_id" BIGINT,
  "barber_id" BIGINT NOT NULL,
  "rating" INTEGER DEFAULT 5,
  "comment" TEXT,
  "is_approved" BOOLEAN DEFAULT TRUE,
  "created_at" TIMESTAMP WITHOUT TIME ZONE,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE,
  PRIMARY KEY ("id")
);

INSERT INTO "reviews" ("id", "booking_id", "user_id", "barber_id", "rating", "comment", "is_approved", "created_at", "updated_at") VALUES
  (1, NULL, NULL, 1, 5, 'Andre is phenomenal. Best skin fade in town, clean environment and quiet luxury atmosphere.', TRUE, '2026-09-23 06:23:31', '2026-09-23 06:23:31'),
  (2, NULL, NULL, 2, 5, 'The beard trim and hot towel service was impeccably done. Truly relaxing experience.', TRUE, '2026-09-23 06:23:31', '2026-09-23 06:23:31');

-- -----------------------------------------------------
-- Table: payments
-- -----------------------------------------------------
DROP TABLE IF EXISTS "payments" CASCADE;
CREATE TABLE "payments" (
  "id" BIGSERIAL,
  "booking_id" BIGINT NOT NULL,
  "order_id" varchar(255) NOT NULL,
  "snap_token" varchar(255),
  "transaction_id" varchar(255),
  "payment_method" varchar(255),
  "amount" decimal(10,2) NOT NULL,
  "status" varchar(30) DEFAULT 'pending',
  "paid_at" TIMESTAMP WITHOUT TIME ZONE,
  "expiry_time" TIMESTAMP WITHOUT TIME ZONE,
  "raw_response" TEXT,
  "created_at" TIMESTAMP WITHOUT TIME ZONE,
  "updated_at" TIMESTAMP WITHOUT TIME ZONE,
  PRIMARY KEY ("id")
);

INSERT INTO "payments" ("id", "booking_id", "order_id", "snap_token", "transaction_id", "payment_method", "amount", "status", "paid_at", "expiry_time", "raw_response", "created_at", "updated_at") VALUES
  (1, 7, 'DTC-260923-FC35', NULL, 'SANDBOX-TRX-6AB3EFC77C1D0', 'QRIS (Midtrans Sandbox)', 110000.00, 'paid', '2026-09-23 15:27:03', NULL, '{"simulated":true,"timestamp":"2026-09-23T15:27:03.508393Z"}', '2026-09-23 15:26:54', '2026-09-23 15:27:03'),
  (2, 8, 'DTC-260924-23F2', 'SANDBOX-SIMULATION-9d520a567fd9e032b19c7b98fa671661', 'SANDBOX-TRX-6AB4CF792E270', 'QRIS (Midtrans Sandbox)', 114000.00, 'pending', '2026-09-24 07:21:29', NULL, '{"token":"SANDBOX-SIMULATION-9d520a567fd9e032b19c7b98fa671661","redirect_url":"https:\/\/app.sandbox.midtrans.com\/snap\/v2\/vtweb\/SANDBOX-SIMULATION-9d520a567fd9e032b19c7b98fa671661","note":"Local sandbox simulation (MIDTRANS_SERVER_KEY not set in .env)"}', '2026-09-24 07:21:21', '2026-09-24 07:22:10');

-- -----------------------------------------------------
-- Table: migrations
-- -----------------------------------------------------
DROP TABLE IF EXISTS "migrations" CASCADE;
CREATE TABLE "migrations" (
  "id" SERIAL,
  "migration" varchar(255) NOT NULL,
  "batch" INTEGER NOT NULL,
  PRIMARY KEY ("id")
);

INSERT INTO "migrations" ("id", "migration", "batch") VALUES
  (1, '0001_01_01_000000_create_users_table', 1),
  (2, '0001_01_01_000001_create_cache_table', 1),
  (3, '0001_01_01_000002_create_jobs_table', 1),
  (4, '2026_09_23_000001_create_dutchman_barbershop_tables', 1),
  (5, '2026_09_23_121446_add_category_to_services_table', 2),
  (6, '2026_09_23_212000_create_payments_and_addons_tables', 3),
  (7, '2026_09_23_215000_add_is_maintenance_to_barbers_table', 4),
  (8, '2026_09_24_140000_add_snap_token_to_payments_table', 5);

-- -----------------------------------------------------
-- Reset serial sequences for auto-increment columns
-- -----------------------------------------------------
SELECT setval(pg_get_serial_sequence('users', 'id'), coalesce((SELECT max(id) FROM "users"), 1), true);
SELECT setval(pg_get_serial_sequence('jobs', 'id'), coalesce((SELECT max(id) FROM "jobs"), 1), true);
SELECT setval(pg_get_serial_sequence('failed_jobs', 'id'), coalesce((SELECT max(id) FROM "failed_jobs"), 1), true);
SELECT setval(pg_get_serial_sequence('services', 'id'), coalesce((SELECT max(id) FROM "services"), 1), true);
SELECT setval(pg_get_serial_sequence('barbers', 'id'), coalesce((SELECT max(id) FROM "barbers"), 1), true);
SELECT setval(pg_get_serial_sequence('addons', 'id'), coalesce((SELECT max(id) FROM "addons"), 1), true);
SELECT setval(pg_get_serial_sequence('promotions', 'id'), coalesce((SELECT max(id) FROM "promotions"), 1), true);
SELECT setval(pg_get_serial_sequence('bookings', 'id'), coalesce((SELECT max(id) FROM "bookings"), 1), true);
SELECT setval(pg_get_serial_sequence('booking_items', 'id'), coalesce((SELECT max(id) FROM "booking_items"), 1), true);
SELECT setval(pg_get_serial_sequence('booking_addons', 'id'), coalesce((SELECT max(id) FROM "booking_addons"), 1), true);
SELECT setval(pg_get_serial_sequence('reviews', 'id'), coalesce((SELECT max(id) FROM "reviews"), 1), true);
SELECT setval(pg_get_serial_sequence('payments', 'id'), coalesce((SELECT max(id) FROM "payments"), 1), true);
SELECT setval(pg_get_serial_sequence('migrations', 'id'), coalesce((SELECT max(id) FROM "migrations"), 1), true);

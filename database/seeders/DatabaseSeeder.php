<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CarCategory;
use App\Models\Car;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->createCategories();
        $this->createUsers();
        $this->createVendors();
        $this->createSampleCars();
    }

    private function createCategories(): void
    {
        $categories = [
            [
                'name' => 'Sedan',
                'slug' => 'sedan',
                'icon' => 'car-sedan',
                'description' => 'Comfortable and fuel-efficient sedans perfect for city driving and long trips.',
                'base_price' => 45.00,
                'minimum_age' => 21,
                'features' => json_encode(['AC', 'Power Steering', 'Airbags', 'Music System'])
            ],
            // ... other categories as before
        ];

        foreach ($categories as $category) {
            CarCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✅ Car categories seeded successfully.');
    }

    private function createUsers(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Vendor User',
                'email' => 'vendor@example.com',
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('✅ Users seeded successfully.');
        $this->command->table(
            ['Name', 'Email', 'Role', 'Password'],
            [
                ['Admin User', 'admin@example.com', 'admin', 'password'],
                ['Vendor User', 'vendor@example.com', 'vendor', 'password'],
                ['Manager User', 'manager@example.com', 'manager', 'password'],
                ['Regular User', 'user@example.com', 'user', 'password'],
                ['John Doe', 'john@example.com', 'user', 'password'],
            ]
        );
    }

    private function createVendors(): void
    {
        $vendorUser = User::where('email', 'vendor@example.com')->first();

        if ($vendorUser) {
            Vendor::firstOrCreate(
                ['user_id' => $vendorUser->id],
                [
                    'company_name' => 'Premium Car Rentals Inc.',
                    'business_registration_number' => 'REG' . Str::random(8),
                    'tax_id' => 'TAX' . Str::random(6),
                    'business_address' => '123 Main Street, New York, NY 10001',
                    'phone' => '+1234567891',
                    'website' => 'https://premiumcars.com',
                    'description' => 'Leading car rental company with a fleet of premium vehicles.',
                    'verification_status' => 'verified',
                    'verified_at' => now(),
                    'commission_rate' => 10.00,
                    'is_active' => true,
                    'settings' => json_encode([
                        'auto_approve_rentals' => false,
                        'notification_preferences' => [
                            'email' => true,
                            'sms' => false,
                        ],
                    ]),
                ]
            );

            $this->command->info('✅ Vendor profile created successfully.');
        }
    }

    private function createSampleCars(): void
    {
        $vendor = User::where('email', 'vendor@example.com')->first();

        if (!$vendor) {
            $this->command->warn('⚠️ Vendor user not found. Skipping sample cars.');
            return;
        }

        $categories = CarCategory::all();

        $sampleCars = [
            [
                'brand' => 'Toyota',
                'model' => 'Camry XLE',
                'year' => 2023,
                'color' => 'Silver',
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'price_per_day' => 55.00,
                'security_deposit' => 200.00,
                'description' => 'The Toyota Camry XLE offers a perfect blend of comfort, reliability, and efficiency.',
                'features' => json_encode(['Leather Seats', 'Premium Sound', 'GPS', 'Backup Camera']),
                'license_plate' => 'NYC' . Str::random(4),
                'location' => 'New York',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'mileage' => 15000,
                'category' => 'sedan',
                'is_available' => true,
                'is_approved' => true,
            ],
            [
                'brand' => 'Honda',
                'model' => 'CR-V',
                'year' => 2023,
                'color' => 'White',
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'price_per_day' => 75.00,
                'security_deposit' => 250.00,
                'description' => 'The Honda CR-V is a versatile SUV perfect for family trips.',
                'features' => json_encode(['Roof Rack', 'GPS', 'Backup Camera', 'Heated Seats']),
                'license_plate' => 'NYC' . Str::random(4),
                'location' => 'New York',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'mileage' => 20000,
                'category' => 'suv',
                'is_available' => true,
                'is_approved' => true,
            ],
            [
                'brand' => 'BMW',
                'model' => '3 Series',
                'year' => 2022,
                'color' => 'Black',
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'petrol',
                'price_per_day' => 120.00,
                'security_deposit' => 400.00,
                'description' => 'The BMW 3 Series delivers an exceptional driving experience.',
                'features' => json_encode(['Leather Seats', 'Premium Sound', 'GPS', 'Sport Mode']),
                'license_plate' => 'NYC' . Str::random(4),
                'location' => 'New York',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'mileage' => 10000,
                'category' => 'luxury',
                'is_available' => true,
                'is_approved' => true,
            ],
            [
                'brand' => 'Tesla',
                'model' => 'Model 3',
                'year' => 2023,
                'color' => 'Red',
                'seats' => 5,
                'transmission' => 'automatic',
                'fuel_type' => 'electric',
                'price_per_day' => 95.00,
                'security_deposit' => 350.00,
                'description' => 'The Tesla Model 3 is an all-electric sedan with exceptional range.',
                'features' => json_encode(['Autopilot', 'Premium Sound', 'GPS', 'Glass Roof']),
                'license_plate' => 'NYC' . Str::random(4),
                'location' => 'New York',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'mileage' => 5000,
                'category' => 'electric',
                'is_available' => true,
                'is_approved' => true,
            ],
        ];

        foreach ($sampleCars as $carData) {
            $category = $categories->where('slug', $carData['category'])->first();

            if ($category) {
                unset($carData['category']);

                Car::create(array_merge($carData, [
                    'vendor_id' => $vendor->id,
                    'category_id' => $category->id,
                ]));
            }
        }

        $this->command->info('✅ Sample cars seeded successfully.');
    }
}

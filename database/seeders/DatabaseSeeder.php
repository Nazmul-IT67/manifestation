<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PostSeeder::class,
            BookingSeeder::class,
            CommentSeeder::class,
            CategorySeeder::class,
            ContentSeeder::class,
            JournalTypeSeeder::class,
            JournalSeeder::class,
            AngelNumberSeeder::class,
            AffirmationSeeder::class,
            SessionTypeSeeder::class,
            DynamicPageSeeder::class,
            SessionTimeSeeder::class,
            SubscriptionPlanSeeder::class,
            UserSubscriptionSeeder::class,
        ]);
    }
}

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
            CategorySeeder::class,
            JournalTypeSeeder::class,
            AngelNumberSeeder::class,
            AffirmationSeeder::class,
            SessionTypeSeeder::class,
            SessionTimeSeeder::class,
            SubscriptionPlanSeeder::class,
            UserSubscriptionSeeder::class,
        ]);
    }
}

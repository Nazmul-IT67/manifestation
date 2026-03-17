<?php

namespace Database\Seeders;

use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $plan = SubscriptionPlan::first();

        UserSubscription::create([
            'user_id'         => 2,
            'subscription_id' => $plan->id,
            'start_date'      => Carbon::today()->subDays(15),
            'end_date'        => Carbon::today()->addDays(15),
            'status'          => 'active',
        ]);

        $this->command->info('✅ User #2 subscription seeded!');
    }
}
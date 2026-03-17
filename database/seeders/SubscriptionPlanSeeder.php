<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'          => 'Basic',
                'price_monthly' => 9.99,
                'price_yearly'  => 99.99,
                'plan_features' => json_encode([
                    '10 Projects',
                    '5GB Storage',
                    'Email Support',
                ]),
                'badge_text' => null,
                'is_active'  => true,
            ],
            [
                'name'          => 'Pro',
                'price_monthly' => 19.99,
                'price_yearly'  => 199.99,
                'plan_features' => json_encode([
                    'Unlimited Projects',
                    '50GB Storage',
                    'Priority Support',
                    'Advanced Analytics',
                ]),
                'badge_text' => 'Most Popular',
                'is_active'  => true,
            ],
            [
                'name'          => 'Enterprise',
                'price_monthly' => 49.99,
                'price_yearly'  => 499.99,
                'plan_features' => json_encode([
                    'Unlimited Projects',
                    '500GB Storage',
                    '24/7 Dedicated Support',
                    'Advanced Analytics',
                    'Custom Integrations',
                    'SLA Guarantee',
                ]),
                'badge_text' => 'Best Value',
                'is_active'  => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }

        $this->command->info('✅ Subscription plans seeded successfully!');
    }
}
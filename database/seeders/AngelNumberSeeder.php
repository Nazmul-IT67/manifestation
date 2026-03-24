<?php

namespace Database\Seeders;

use App\Models\AngelNumber;
use Illuminate\Database\Seeder;

class AngelNumberSeeder extends Seeder
{
    public function run(): void
    {
        $angelNumbers = [
            [
                'number'      => 111,
                'title'       => 'New Beginnings',
                'description' => 'Angel number 111 is a powerful sign of new beginnings, manifestation, and spiritual awakening. It encourages you to focus on your thoughts and intentions.',
                'tags'        => ['manifestation', 'new beginnings', 'awakening'],
                'is_active'   => true,
            ],
            [
                'number'      => 222,
                'title'       => 'Balance & Harmony',
                'description' => 'Angel number 222 represents balance, harmony, and cooperation. It reminds you to trust the process and maintain faith in your journey.',
                'tags'        => ['balance', 'harmony', 'faith'],
                'is_active'   => true,
            ],
            [
                'number'      => 333,
                'title'       => 'Divine Protection',
                'description' => 'Angel number 333 signifies divine protection and guidance. It is a reminder that the universe is supporting your growth and creativity.',
                'tags'        => ['protection', 'guidance', 'creativity'],
                'is_active'   => true,
            ],
            [
                'number'      => 444,
                'title'       => 'Stability & Foundation',
                'description' => 'Angel number 444 symbolizes stability, foundation, and hard work. It encourages you to stay grounded and trust your inner wisdom.',
                'tags'        => ['stability', 'foundation', 'wisdom'],
                'is_active'   => true,
            ],
            [
                'number'      => 555,
                'title'       => 'Major Changes',
                'description' => 'Angel number 555 signals major life changes and transformation. Embrace the changes coming your way with an open heart and mind.',
                'tags'        => ['change', 'transformation', 'growth'],
                'is_active'   => true,
            ],
            [
                'number'      => 666,
                'title'       => 'Reflection & Balance',
                'description' => 'Angel number 666 is a call to reflect on your thoughts and refocus on spiritual balance. It urges you to align your material and spiritual life.',
                'tags'        => ['reflection', 'balance', 'spiritual'],
                'is_active'   => true,
            ],
            [
                'number'      => 777,
                'title'       => 'Spiritual Awakening',
                'description' => 'Angel number 777 is a highly spiritual number representing divine wisdom and enlightenment. It is a sign that you are on the right path.',
                'tags'        => ['spiritual', 'enlightenment', 'wisdom'],
                'is_active'   => true,
            ],
            [
                'number'      => 888,
                'title'       => 'Abundance & Prosperity',
                'description' => 'Angel number 888 represents abundance, prosperity, and financial success. It signals that rewards are coming for your hard work.',
                'tags'        => ['abundance', 'prosperity', 'success'],
                'is_active'   => true,
            ],
            [
                'number'      => 999,
                'title'       => 'Completion & Closure',
                'description' => 'Angel number 999 signifies the completion of a cycle and the beginning of a new chapter. It encourages you to let go of the past.',
                'tags'        => ['completion', 'closure', 'new chapter'],
                'is_active'   => true,
            ],
            [
                'number'      => 1010,
                'title'       => 'Divine Alignment',
                'description' => 'Angel number 1010 is a sign of divine alignment and spiritual growth. It encourages you to stay positive and trust your intuition.',
                'tags'        => ['alignment', 'intuition', 'growth'],
                'is_active'   => true,
            ],
        ];

        foreach ($angelNumbers as $angelNumber) {
            AngelNumber::create($angelNumber);
        }

        $this->command->info('✅ Angel numbers seeded successfully!');
    }
}
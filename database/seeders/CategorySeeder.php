<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'title' => 'Hatha Yoga',
                'image' => 'hatha_yoga.jpg',
                'icon' => 'fa fa-leaf',
                'description' => 'Hatha Yoga is a traditional form of yoga that focuses on basic postures (asanas) and breathing techniques (pranayama). It is ideal for beginners who are just starting their yoga journey, as it introduces fundamental movements in a slow and controlled manner. This practice helps improve flexibility, balance, and overall physical strength while also promoting relaxation and mental clarity.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Vinyasa Yoga',
                'image' => 'vinyasa_yoga.jpg',
                'icon' => 'fa fa-wind',
                'description' => 'Vinyasa Yoga is a dynamic and flowing style of yoga where movements are synchronized with breath. Each pose transitions smoothly into the next, creating a continuous sequence that improves cardiovascular health, strength, and flexibility. It is suitable for those who enjoy a more energetic and creative practice, as no two classes are usually the same.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Ashtanga Yoga',
                'image' => 'ashtanga_yoga.jpg',
                'icon' => 'fa fa-fire',
                'description' => 'Ashtanga Yoga is a structured and physically demanding style of yoga that follows a specific sequence of postures. It emphasizes strength, endurance, and discipline through a consistent and repetitive practice. This form of yoga is best suited for individuals looking for a challenging workout that builds both physical and mental resilience.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Yin Yoga',
                'image' => 'yin_yoga.jpg',
                'icon' => 'fa fa-moon',
                'description' => 'Yin Yoga is a slow-paced and meditative style of yoga that involves holding poses for longer periods of time. It targets deep connective tissues, such as ligaments and joints, improving flexibility and circulation. This practice is perfect for stress relief, relaxation, and enhancing mindfulness, making it a great complement to more active yoga styles.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Power Yoga',
                'image' => 'power_yoga.jpg',
                'icon' => 'fa fa-bolt',
                'description' => 'Power Yoga is a fast-paced and high-intensity form of yoga designed to build strength, stamina, and flexibility. It incorporates elements of traditional yoga with modern fitness techniques, making it ideal for those who want a more physically challenging workout. This style helps burn calories, tone muscles, and improve overall fitness levels.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Meditation',
                'image' => 'meditation.jpg',
                'icon' => 'fa fa-brain',
                'description' => 'Meditation focuses on mental awareness, mindfulness, and breathing techniques to calm the mind and reduce stress. It is an essential part of yoga practice that helps improve concentration, emotional balance, and inner peace. Regular meditation can lead to better sleep, reduced anxiety, and an overall sense of well-being.',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
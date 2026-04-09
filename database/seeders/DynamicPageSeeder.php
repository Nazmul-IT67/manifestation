<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DynamicPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            'Privacy Policy',
            'Terms and Conditions',
        ];

        foreach ($pages as $page) {
            DB::table('dynamic_pages')->insert([
                'title'      => $page,
                'slug'       => Str::slug($page),
                'content'    => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
                'status'     => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
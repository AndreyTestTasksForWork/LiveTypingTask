<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Fundamentals',
            'String',
            'Algorithms',
            'Mathematic',
            'Performance',
            'Booleans',
            'Functions',
        ];

        foreach ($categories as $category) {
            DB::table('category')->insert([
                'name' => $category
            ]);
        }
    }
}

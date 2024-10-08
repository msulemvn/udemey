<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array of sample categories
        $categories = [
            'Development',
            'Business',
            'Design',
            'Marketing',
            'Photography',
            'IT & Software',
            'Personal Development'
        ];

        // Insert categories into the database
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'title' => $category,
                'slug' => Str::slug($category),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

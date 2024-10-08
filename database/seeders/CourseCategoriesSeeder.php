<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample course categories with main categories and subcategories
        $categories = [
            ['category_id' => 1, 'title' => 'Web Development', 'slug' => Str::slug('Web Development')],
            ['category_id' => 1, 'title' => 'Mobile Development', 'slug' => Str::slug('Mobile Development')],
            ['category_id' => 1, 'title' => 'Data Science', 'slug' => Str::slug('Data Science')],

            ['category_id' => 2, 'title' => 'Entrepreneurship', 'slug' => Str::slug('Entrepreneurship')],
            ['category_id' => 2, 'title' => 'Business Strategy', 'slug' => Str::slug('Business Strategy')],
            ['category_id' => 2, 'title' => 'Management & Leadership', 'slug' => Str::slug('Management & Leadership')],

            ['category_id' => 3, 'title' => 'Graphic Design', 'slug' => Str::slug('Graphic Design')],
            ['category_id' => 3, 'title' => 'UX/UI Design', 'slug' => Str::slug('UX/UI Design')],
            ['category_id' => 3, 'title' => 'Interior Design', 'slug' => Str::slug('Interior Design')],

            ['category_id' => 4, 'title' => 'Digital Marketing', 'slug' => Str::slug('Digital Marketing')],
            ['category_id' => 4, 'title' => 'Content Marketing', 'slug' => Str::slug('Content Marketing')],
            ['category_id' => 4, 'title' => 'Social Media Marketing', 'slug' => Str::slug('Social Media Marketing')],

            ['category_id' => 5, 'title' => 'Portrait Photography', 'slug' => Str::slug('Portrait Photography')],
            ['category_id' => 5, 'title' => 'Landscape Photography', 'slug' => Str::slug('Landscape Photography')],
            ['category_id' => 5, 'title' => 'Photo Editing', 'slug' => Str::slug('Photo Editing')],

            ['category_id' => 6, 'title' => 'Network & Security', 'slug' => Str::slug('Network & Security')],
            ['category_id' => 6, 'title' => 'Software Development', 'slug' => Str::slug('Software Development')],
            ['category_id' => 6, 'title' => 'Database Management', 'slug' => Str::slug('Database Management')],

            ['category_id' => 7, 'title' => 'Productivity', 'slug' => Str::slug('Productivity')],
            ['category_id' => 7, 'title' => 'Leadership Skills', 'slug' => Str::slug('Leadership Skills')],
            ['category_id' => 7, 'title' => 'Stress Management', 'slug' => Str::slug('Stress Management')],
        ];

        // Insert data into the course_categories table
        DB::table('course_categories')->insert($categories);
    }
}

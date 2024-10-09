<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create first course
        Course::create([
            'title' => 'Introduction to Laravel',
            'slug' => $this->generateUniqueSlug('Introduction to Laravel'),
            'description' => 'A comprehensive introduction to Laravel framework.',
            'price' => 99.99,
            'discounted_price' => 49.99,
            'thumbnail_url' => 'https://example.com/thumbnail/laravel.png',
            'user_id' => 1, // assuming user with id 1 is the instructor
            'course_categories_id' => 1, // assuming category with id 1 exists
        ]);

        // Create second course
        Course::create([
            'title' => 'Mastering PHP',
            'slug' => $this->generateUniqueSlug('Mastering PHP'),
            'description' => 'Advanced techniques and best practices for PHP.',
            'price' => 129.99,
            'discounted_price' => 79.99,
            'thumbnail_url' => 'https://example.com/thumbnail/php.png',
            'user_id' => 1, // assuming user with id 2 is the instructor
            'course_categories_id' => 2, // assuming category with id 2 exists
        ]);
    }

    /**
     * Generate a unique slug for the course.
     *
     * @param string $title
     * @return string
     */
    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Check if the slug already exists, and append a number if it does
        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}

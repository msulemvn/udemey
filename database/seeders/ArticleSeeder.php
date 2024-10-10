<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Article::create([
            'title' => 'Introduction to Udemey',
            'body' => 'This article covers the basics of Laravel framework.',
            'slug' => Str::slug('Introduction to Udemey'),
            'image_url' => 'https://example.com/laravel-intro.jpg',
            'user_id' => 1,  // assuming the user with ID 1 exists
            'course_id' => 1, // assuming the course with ID 1 exists
            'status' => 'published',
        ]);
    }
}

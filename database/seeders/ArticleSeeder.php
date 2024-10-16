<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


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
            // 'image_url' => 'articles/laravel-intro.jpg', // Path relative to storage/app/public
            'user_id' => 1,  // assuming the user with ID 1 exists
            'course_id' => 1, // assuming the course with ID 1 exists
            'status' => 'published',
        ]);
    }
}

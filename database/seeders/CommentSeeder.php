<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a new article to comment on
        $article = Article::create([
            'title' => 'Example Article',
            'body' => 'This is an example article.',
            'slug' => 'example-article',
            'image_path' => 'https://example.com/image.jpg',
            'user_id' => 1, // Assuming user ID 1 exists
            'course_id' => 1, // Assuming course ID 1 exists
            'status' => 'published',
        ]);

        // Create top-level comments
        $comments = [];
        for ($i = 0; $i < 5; $i++) {
            $comments[] = Comment::create([
                'comment_text' => "Comment $i",
                'commentable_id' => $article->id,
                'commentable_type' => get_class($article),
            ]);
        }

        // Create nested comments
        foreach ($comments as $comment) {
            for ($i = 0; $i < 3; $i++) {
                Comment::create([
                    'comment_text' => "Reply to Comment {$comment->id}",
                    'commentable_id' => $article->id, // or $comment->id for deeper nesting
                    'commentable_type' => get_class($article), // or get_class($comment)
                    'parent_comment_id' => $comment->id,
                ]);
            }
        }
    }
}

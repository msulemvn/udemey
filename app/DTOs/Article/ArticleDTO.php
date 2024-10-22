<?php

namespace App\DTOs\Article;

use App\DTOs\BaseDTO;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ArticleDTO extends BaseDTO
{
    public $title;
    public $body;
    public $slug;
    public $image_path;  // Updated to match migration
    public $user_id;
    public $course_id;
    public $status;

    public function __construct($request)
    {
        $this->title = $request['title'];
        $this->body = $request['body'] ?? null;
        $this->slug = $request['slug'] ?? null;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $timestamp = now()->format('YmdHs');
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

            $file->storeAs('uploads', $newFileName, 'public');
            $imagePath = 'uploads/' . $newFileName;
        } else {
            $imagePath = null;  // No image was uploaded
        }

        $this->image_path = $imagePath;  // Updated from image_url to image_path
        //$this->user_id = $request['user_id'] ?? null;
        $this->user_id = Auth::user()->id;
        $this->course_id = $request['course_id'];
        $this->status = $request['status'];
    }
}

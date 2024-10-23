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
    public $image_path;
    public $user_id;
    public $course_id;
    public $status;

    public function __construct($request)
    {
        // Ensure $request is a request object for hasFile() to work
        if ($request instanceof \Illuminate\Http\Request) {
            $this->title = $request->input('title');
            $this->body = $request->input('body') ?? null;
            $this->slug = $request->input('slug') ?? null;

            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $timestamp = now()->format('YmdHs');
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

                $file->storeAs('uploads', $newFileName, 'public');
                $this->image_path = 'uploads/' . $newFileName;
            } else {
                $this->image_path = null;  // No image was uploaded
            }
        }

        $this->user_id = Auth::user()->id;
        $this->course_id = $request['course_id'];
        $this->status = $request['status'];
    }
}

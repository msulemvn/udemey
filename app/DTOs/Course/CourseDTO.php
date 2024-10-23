<?php

namespace App\DTOs\Course;

use App\DTOs\BaseDTO;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CourseDTO extends BaseDTO
{
    public string $title;
    public string $description;
    public string $short_description;
    public float $price;
    public ?float $discounted_price;
    public ?string $thumbnail; // Nullable, so we can handle it accordingly
    public int $user_id;
    public int $course_categories_id;
    public string $duration;

    public function __construct($request, $currentThumbnail = null) // Add current thumbnail for update cases
    {
        $this->title = $request['title'];
        $this->description = $request['description'];
        $this->short_description = json_encode($request['short_description']);
        $this->price = (float) $request['price'];
        $this->discounted_price = isset($request['discounted_price']) ? (float) $request['discounted_price'] : null;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $timestamp = now()->format('YmdHs');
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

            $file->storeAs('course_image', $newFileName, 'public');
            $this->thumbnail = 'course_image/' . $newFileName;
        } else {
            // Use the current thumbnail if no new file is uploaded
            $this->thumbnail = $currentThumbnail;
        }

        $this->user_id = Auth::user()->id;
        $this->course_categories_id = (int) $request['course_categories_id'];
        $this->duration = (float) $request['duration'];
    }
}

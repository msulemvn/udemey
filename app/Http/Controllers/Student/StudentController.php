<?php

namespace App\Http\Controllers\Student;

use App\Helpers\ApiResponse;
use App\Http\Requests\Student\UpdateRequest;
use App\Models\Student;
use App\Http\Controllers\Controller;


class StudentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return ApiResponse::success(data: student::with('user')->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'phone' => $student->phone,
            ];
        })->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        try {
            $student->user->delete();
            $student->delete();
        } catch (\Exception $e) {
            dd();
            // return ApiResponse::error($request,);
        }

        return ApiResponse::success(message: 'Successfully deleted student.');
    }
}

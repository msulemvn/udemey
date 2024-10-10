<?php

namespace App\Http\Controllers\Student;

use App\Helpers\ApiResponse;
use App\Http\Requests\Student\StoreRequest;
use App\Http\Requests\UpdateStudentRequest;
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
        // return ApiResponse::success(data: student::with('user')->paginate()->through(function ($student) {
        //     return [
        //         'id' => $student->id,
        //         'name' => $student->user->name,
        //         'email' => $student->user->email,
        //         'phone' => $student->phone,
        //     ];
        // }));
        return ApiResponse::success(data: student::with('user')->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'phone' => $student->phone,
            ];
        }));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        //
    }

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
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'An error occured while deleting student.');
        }

        return ApiResponse::success(message: 'Successfully deleted student.');
    }
}

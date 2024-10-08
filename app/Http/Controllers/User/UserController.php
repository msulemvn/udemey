<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        return $id ? ApiResponse::success(data: User::find($id)) : ApiResponse::success(data: User::paginate());
    }

    public function showUser()
    {
        $userId = Auth::user()->id;
        $myRole = Auth::user()->getRoleNames()[0];
        if ($myRole == 'admin') {
            return ApiResponse::success(data: User::find($userId));
        }

        return ApiResponse::success(data: User::with($myRole)->whereId($userId)->get()->mapWithKeys(function ($user) {
            $role = Auth::user()->getRoleNames()[0];
            return [
                'id' => $user->$role->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
        }));
    }


    public function changePassword(ChangePasswordRequest $request, UserService $userService)
    {
        $validatedData = $request->safe()->only(['current_password', 'new_password', 'new_password_confirmation']);
        return $userService->changePassword($validatedData);
    }
}

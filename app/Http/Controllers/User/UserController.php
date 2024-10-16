<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Helpers\ApiResponse;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\ChangePasswordUserRequest;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

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
        $user = Auth::user();
        /** @var \App\User|null $user */
        $myRole = $user->getRoleNames()[0];
        if ($myRole == 'admin') {
            $userData = User::find($userId)->toArray();
            $userData['2fa'] =  ($userData['google2fa_secret']) ? true : false;
            unset($userData['google2fa_secret']);
            return ApiResponse::success(data: $userData);
        }

        return ApiResponse::success(data: User::with($myRole)->whereId($userId)->get()->mapWithKeys(function ($user) {
            $role = $user->getRoleNames()[0];
            return [
                'id' => $user->$role->id,
                'name' => $user->name,
                'email' => $user->email,
                '2fa' => ($user->google2fa_secret) ? true : false,
            ];
        })->toArray());
    }


    public function changePassword(ChangePasswordUserRequest $request)
    {
        return $this->userService->changePassword($request);
    }
}

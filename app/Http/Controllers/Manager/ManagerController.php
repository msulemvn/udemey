<?php

namespace App\Http\Controllers\Manager;

use App\DTOs\User\UserDTO;
use App\DTOs\Manager\ManagerDTO;
use App\Helpers\ApiResponse;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class ManagerController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreManagerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreManagerRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $validatedData['password'] = bcrypt(Str::random(16));
            $newUser = User::create((new UserDTO($validatedData))->toArray());
            $newManager = Manager::create((new ManagerDTO(['account_id' => $newUser->id]))->toArray());
            $newUser->assignRole('manager');
            if ($newUser) {
                $status = Password::sendResetLink(["email" => $newUser['email']]);
                if ($status === Password::RESET_LINK_SENT) {
                    return ApiResponse::success(message: 'Adding manager successful, email notification sent.');
                }
            }
        } catch (\Exception $e) {
            return ApiResponse::error(message: 'An error occurred while adding manager.');
        }
        return ApiResponse::error(message: 'Adding manager failed.', statusCode: Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function show(Manager $manager)
    {
        // return ApiResponse::success(data: Manager::with('user')->paginate()->through(function ($manager) {
        //     return [
        //         'id' => $manager->id,
        //         'name' => $manager->user->name,
        //         'email' => $manager->user->email,
        //     ];
        // }));
        return ApiResponse::success(data: Manager::with('user')->get()->map(function ($manager) {
            return [
                'id' => $manager->id,
                'name' => $manager->user->name,
                'email' => $manager->user->email,
            ];
        })->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function edit(Manager $manager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateManagerRequest  $request
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateManagerRequest $request, Manager $manager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manager  $manager
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manager $manager)
    {
        try {
            $manager->user->delete();
            $manager->delete();
        } catch (\Throwable $th) {
            return ApiResponse::error(message: 'An error occured while deleting manager.');
        }

        return ApiResponse::success(message: 'Successfully deleted manager.');
    }
}

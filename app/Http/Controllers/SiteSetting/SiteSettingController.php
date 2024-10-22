<?php

namespace App\Http\Controllers\SiteSetting;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SiteSetting\SiteSettingService;
use App\Http\Requests\SiteSetting\CreateSiteSettingRequest;
use App\Http\Requests\SiteSetting\UpdateSiteSettingRequest;

class SiteSettingController extends Controller
{
    protected $siteSettingService;

    public function __construct(SiteSettingService $siteSettingService)
    {
        $this->siteSettingService = $siteSettingService;
    }

    public function createSetting(CreateSiteSettingRequest $request)
    {
        $validatedData = $request->all();
        $response = $this->siteSettingService->createSetting($validatedData);

        return $response['success'] ?
            ApiResponse::success(
                data: $response['data'] ?? null,
                message: $response['message'],
                statusCode:Response::HTTP_CREATED

            ) :
            ApiResponse::success(
                message: 'Unable to create setting!',
                errors: ['site setting' => ['Unable to create setting!']],
            );
    }

    public function updateSetting(UpdateSiteSettingRequest $request, $id)
    {
        $validatedData = $request->all();
        $response = $this->siteSettingService->updateSetting($validatedData, $id);

        return $response['success'] ?
            ApiResponse::success(
                data: $response['data'] ?? null,
                message: $response['message'],
            ) :
            ApiResponse::success(
                message: 'No setting found with the given id!',
                errors: ['site setting' => ['No setting found with id: ' . $id]],
            );
    }

    public function deleteSetting($id)
    {
        $response = $this->siteSettingService->deleteSetting($id);
        return $response['success'] ?
            ApiResponse::success(
                message: $response['message'],
            ) :
            ApiResponse::success(
                message: 'No setting found with the given id!',
                errors: ['site setting' => ['No setting found with id: ' . $id]],
            );
    }

    public function restoreSoftDeletedSetting($id)
    {
        $response = $this->siteSettingService->restoreSetting($id);

        return $response['success'] ?
            ApiResponse::success(
                data: $response['data']->toArray() ?? null,
                message: 'Site setting restored successfully!',

            ) :
            ApiResponse::success(
                message: 'No setting found with the given id!',
                errors: ['site setting' => ['No setting found with id: ' . $id]],
            );
    }

    public function getSettings()
    {
        $response = $this->siteSettingService->getSettings();

        return $response['success'] ?
            ApiResponse::success(
                data: $response['data']->toArray(request() ?? []),
                message: 'Setting retrieved successfully!'
            ) :
            ApiResponse::success(
                message: 'No setting found with the given id!',
                errors: ['site setting' => ['No setting found with id: ']],
            );
    }
}

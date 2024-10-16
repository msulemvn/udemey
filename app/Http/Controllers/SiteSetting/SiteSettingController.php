<?php

namespace App\Http\Controllers\SiteSetting;

use Exception;
use App\Models\SiteSetting;
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
        $siteSetting = $request->validated();
        $logo = $request->file('logo_path');
        $siteSetting = $this->siteSettingService->createSetting($siteSetting, $logo);

        return ApiResponse::success(
            data: $siteSetting->toArray(),
            message: 'Settings created successfully!',
            statusCode: Response::HTTP_CREATED
        );
    }

    public function updateSetting(UpdateSiteSettingRequest $request, $id)
    {
        $logo = $request->hasFile('logo_path') ? $request->file('logo_path') : null;
        $updatedSetting = $this->siteSettingService->updateSetting($request->all(), $logo, $id);

        return ApiResponse::success(
            data: $updatedSetting->toArray(),
            message: "Site settings updated successfully!",
            statusCode: Response::HTTP_ACCEPTED
        );
    }

    public function deleteSetting($id)
    {
        $this->siteSettingService->deleteSetting($id);
        return ApiResponse::success(message: "Setting deleted successfully!");
    }

    public function restoreSoftDeletedSetting($id)
    {
        $restoredSetting = $this->siteSettingService->restoreSetting($id);
        return ApiResponse::success(
            data: $restoredSetting->toArray(),
            message: "Site setting restored successfully!"
        );
    }

    public function getSettings($id)
    {
        $siteSetting = SiteSetting::findOrFail($id);
        return ApiResponse::success(
            data: $siteSetting->toArray(),
            message: "Site setting fetched successfully!"
        );
    }
}

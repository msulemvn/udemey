<?php

namespace App\Http\Controllers\SiteSetting;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SiteSetting\SiteSettingRequest;
use App\Http\Requests\SiteSetting\UpdateSiteSettingRequest;
use App\Services\SiteSetting\SiteSettingService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class SiteSettingController extends Controller
{
    protected $siteSettingService;
    public function __construct(SiteSettingService $siteSettingService )
    {
        $this->siteSettingService = $siteSettingService;
    }

    public function createSetting(SiteSettingRequest $request)
    {
        try{
            // $siteSetting = $request->validated();
            $logo = $request->file('logo_path');
            $siteSetting = $this->siteSettingService->createSetting($request, $logo);
            if(!$siteSetting)
            {
                return ApiResponse::error(message:'Unable to create settings', statusCode:Response::HTTP_BAD_REQUEST);
            }
            return ApiResponse::success(data:($siteSetting)->toArray(), message:'settings are created successfully!', statusCode:Response::HTTP_CREATED,);
        }catch(Exception $e)
        {
            return ApiResponse::error(message:'Failed to create settings!', exception:$e, statusCode:Response::HTTP_EXPECTATION_FAILED);
        }
    }

    public function updateSetting(UpdateSiteSettingRequest $request)
    {
        // dd('hi');
        $updatedSetting = $this->siteSettingService->updateSetting($request);

        return ApiResponse::success(data:$updatedSetting, message:"Site settings updated successfully!", statusCode:Response::HTTP_ACCEPTED);
    }
}

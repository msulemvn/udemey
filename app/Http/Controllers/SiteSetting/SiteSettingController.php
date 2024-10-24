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

    public function store(CreateSiteSettingRequest $request)
    {
        $response = $this->siteSettingService->store($request->all());
        return ApiResponse::success(data: $response['data'] ?? null, message: $response['message'], statusCode: Response::HTTP_CREATED,);
    }

    public function update(UpdateSiteSettingRequest $request, $id)
    {
        $response = $this->siteSettingService->update($request->all(), $id);
        return ApiResponse::success(data: $response['data'] ?? null, message: $response['message']);
    }

    public function destroy($id)
    { 
        $response = $this->siteSettingService->destroy($id);
        return  ApiResponse::success(message: $response['message']);
    }

    public function restore($id)
    {
        $response = $this->siteSettingService->restore($id);
        return ApiResponse::success(data: $response['data']->toArray() ?? null,message: $response['message'],);
    }

    public function index()
    {
        $response = $this->siteSettingService->index();
        return ApiResponse::success(data: $response['data']->toArray(request() ?? []));
    }
}

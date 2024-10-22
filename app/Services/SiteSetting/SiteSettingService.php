<?php

namespace App\Services\SiteSetting;

use App\Models\SiteSetting;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\DTOs\SiteSettings\SiteSettingDTO;
use App\Http\Resources\SiteSetting\SiteSettingResource;

class SiteSettingService
{

    public function createSetting($request)
    {
        try {
            $file = $request['logo_path'];
            $timestamp = Carbon::now()->format('YmdHs');
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

            $file->storeAs('uploads', $newFileName, 'public');
            // Storage::disk('public')->put($newFileName, file_get_contents($file->getRealPath()));
            Log::info('Logo file stored at: ' . $newFileName);

            $request['logo_path'] = $newFileName;
            $response = SiteSetting::create((new SiteSettingDTO($request))->toArray());
            $resource = new SiteSettingResource($response);
            return ['success' => true, 'message' => 'Site setting created successfully!', 'data' => $resource->toArray($request)];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $request, exception: $e);
        }
    }


    public function updateSetting($request, $id)
    {
        try {
            $file = $request['logo_path'];
            $response = SiteSetting::findOrFail($id);
            if($file) {

                $timestamp = now()->timestamp;
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;
                $file->storeAs('uploads', $newFileName, 'public');
                // Storage::disk('public')->put($filePath, file_get_contents($file->getRealPath()));
                $request['logo_path'] = $newFileName;
                Log::info('Logo file stored at: ' . $newFileName);
                $updates = [
                    'site_title' => $request['site_title'] ?? $response->site_title,
                    'copyright' => $request['copyright'] ?? $response->copyright,
                    'logo_path' => $request['logo_path'] ?? $response->logo_path,
                ];
                $siteSettingDTO = new SiteSettingDTO($updates);
                $response->update($siteSettingDTO->toArray());
                $resource = new SiteSettingResource($response);

                return [
                    'success' => true,
                    'message' => 'Site setting updated successfully!',
                    'data' => $resource->toArray($request),
                ];
            }   
        }
        catch (\Exception $e) {
            Log::error('Error uploading logo: ' . $e->getMessage());
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    public function deleteSetting($id)
    {
        try {
            $response = SiteSetting::findOrFail($id);
            if (Storage::disk('public')->exists('uploads/' . $response->logo_path)) {
                Storage::disk('public')->delete('uploads/' . $response->logo_path);
            } else {
                Log::info('File does not exist: ' . 'uploads/' . $response->logo_path);
            }
            $response->delete();
            return ['success' => true, 'message' => 'site setting deleted successfully!'];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $id, exception: $e);
        }
    }

    public function restoreSetting($id)
    {
        try {
            $response = SiteSetting::onlyTrashed()->find($id);
            if (!$response) {
                return [
                    'success' => false,
                    'errors' => ['page' => ['The requested setting does not exist.']],
                    'message' => 'setting not found',
                ];
            }
            $response->restore();
            return ['success' => true, 'message' => 'setting restored successfully!', 'data' => $response];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $id, exception: $e);
        }
    }

    public function getSettings()
    {
        try {
            $response = SiteSetting::latest()->first();
            if ($response) {
                // $logoPath = $response->logo_path ? asset('storage/uploads/' . $response->logo_path) : null;

                // $responseData = [
                //     'site_title' => $response->site_title,
                //     'copyright' => $response->copyright,
                //     'logo_path' => $logoPath,
                // ];
                $resource = new SiteSettingResource($response);
                return ['success' => true, 'data' => $resource];

                // return ['success' => true, 'data' => $responseData];
            }
            return ['success' => false, 'message' => 'No site settings found.'];

        } catch (\Exception $e) {
            return ApiResponse::error(request: null, exception: $e);
        }
    }
}
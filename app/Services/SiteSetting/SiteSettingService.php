<?php

namespace App\Services\SiteSetting;

use App\Models\SiteSetting;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\DTOs\SiteSettings\SiteSettingDTO;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\SiteSetting\SiteSettingResource;

class SiteSettingService
{
    public function store($request)
    {
        try {
            $file = $request['logo_path'];
            $timestamp = Carbon::now()->format('YmdHs');
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;
            $file->storeAs('uploads', $newFileName, 'public');
            Log::info('Logo file stored at: ' . $newFileName);

            $request['logo_path'] = $newFileName;
            $response = SiteSetting::create((new SiteSettingDTO($request))->toArray());
            $resource = new SiteSettingResource($response);

            return ['message' => 'Site setting created successfully!', 'data' => $resource->toArray($request)];
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::error(
                request: $request,
                statusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
                exception: $e->errors(),
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error(
                request: $request,
                exception: $e,
            );
        }
    }

    public function update($request, $id)
    {
        try {
            $file = $request['logo_path'];
            $response = SiteSetting::find($id);
            if (!$response) {
                return ['errors' => ['setting' => ['The requested setting does not exist.']], 'message' => 'setting not found', 'statusCode' => Response::HTTP_NOT_FOUND];
            }
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

                return ['message' => 'Site setting updated successfully!','data' => $resource->toArray($request)];
            }   
        }
        catch (\Exception $e) {
            Log::error('Error uploading logo: ' . $e->getMessage());
            return ApiResponse::error(request: $request, exception: $e);
        }
    }

    public function destroy($id)
    {
        try {
            $response = SiteSetting::find($id);
            if (!$response) {
                return ['errors' => ['setting' => ['The requested setting does not exist.']], 'message' => 'setting not found'];
            }
            // if (Storage::disk('public')->exists('uploads/' . $response->logo_path)) {
            //     Storage::disk('public')->delete('uploads/' . $response->logo_path);
            // } else {
            //     Log::info('File does not exist: ' . 'uploads/' . $response->logo_path);
            // }
            $response->delete();
            return ['message' => 'site setting deleted successfully!'];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $id, exception: $e);
        }
    }

    public function restore($id)
    {
        try {
            $response = SiteSetting::onlyTrashed()->find($id);
            if (!$response) {
                return ['errors' => ['setting' => ['The requested setting does not exist.']], 'message' => 'setting not found'];
            }
            $response->restore();
            return ['message' => 'setting restored successfully!', 'data' => $response];
        } catch (\Exception $e) {
            return ApiResponse::error(request: $id, exception: $e);
        }
    }

    public function index()
    {
        try {
            $response = SiteSetting::latest()->first();
            if ($response) {
                $resource = new SiteSettingResource($response);
                return ['data' => $resource, 'message' => 'Setting retrieved successfully!'];
            }
            return ['message' => 'No site settings found.', 'statusCode' => Response::HTTP_NOT_FOUND];

            
        } catch (\Exception $e) {
            return ApiResponse::error(request: null, exception: $e);
        }
    }
}

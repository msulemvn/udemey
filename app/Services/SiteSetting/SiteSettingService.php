<?php

namespace App\Services\SiteSetting;

use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\DTOs\SiteSettings\SiteSettingDTO;

class SiteSettingService
{

    public function createSetting($data)
    {
        $file = $data['logo_path'];
        $timestamp = Carbon::now()->format('YmdHs');
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

        $file->storeAs('uploads', $newFileName, 'public');
        // Storage::disk('public')->put($newFileName, file_get_contents($file->getRealPath()));
        Log::info('Logo file stored at: ' . $newFileName);

        $data['logo_path'] = $newFileName;
        $response = SiteSetting::create((new SiteSettingDTO($data))->toArray());
        return ['success' => true, 'data' => $response];
    }

    public function updateSetting($data, $id)
    {
        try {
            $file = $data['logo_path'];
            $siteSetting = SiteSetting::findOrFail($id);
            if ($file) {

                $timestamp = now()->timestamp;
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;
                $file->storeAs('uploads', $newFileName, 'public');
                // Storage::disk('public')->put($filePath, file_get_contents($file->getRealPath()));
                $data['logo_path'] = $newFileName;
                Log::info('Logo file stored at: ' . $newFileName);
                $updates = [
                    'site_title' => $data['site_title'] ?? $siteSetting->site_title,
                    'copyright' => $data['copyright'] ?? $siteSetting->copyright,
                    'logo_path' => $data['logo_path'] ?? $siteSetting->logo_path,
                ];
                $siteSettingDTO = new SiteSettingDTO($updates);
                $siteSetting->update($siteSettingDTO->toArray());
            }
        } catch (\Exception $e) {
            Log::error('Error uploading logo: ' . $e->getMessage());
            return [
                'success' => false,
                'errors' => [
                    'message' => ['Failed to upload logo.']
                ],
                'exception' => $e
            ];
        }
        return ['success' => true, 'data' => $siteSetting];
    }

    public function deleteSetting($id)
    {
        try {
            $siteSetting = SiteSetting::findOrFail($id);
            if (Storage::disk('public')->exists('uploads/' . $siteSetting->logo_path)) {
                Storage::disk('public')->delete('uploads/' . $siteSetting->logo_path);
            } else {
                Log::info('File does not exist: ' . 'uploads/' . $siteSetting->logo_path);
            }
            $siteSetting->delete();
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => [
                    'message' => [' Unable to delete site setting']
                ],
                'exception' => $e
            ];
        }
        return ['success' => true, 'data' => $siteSetting];
    }

    public function restoreSetting($id)
    {
        try {
            $deletedSiteSetting = SiteSetting::onlyTrashed()->find($id);
            if (!$deletedSiteSetting) {
                throw new \Exception("No settings found for the Id " . $id);
            }
            $deletedSiteSetting->restore();
            return ['success' => true, 'data' => $deletedSiteSetting];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => [
                    'message' => [' Unable to restore site setting']
                ],
                'exception' => $e
            ];
        }
    }

    public function getSettings($id)
    {
        try {
            $siteSetting = SiteSetting::findOrFail($id);
            if ($siteSetting) {
                $logoPath = $siteSetting->logo_path ? asset('storage/uploads/' . $siteSetting->logo_path) : null;

                $responseData = [
                    'site_title' => $siteSetting->site_title,
                    'copyright' => $siteSetting->copyright,
                    'logo_path' => $logoPath,
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => [
                    'message' => [' No site setting found with this id!']
                ],
                'exception' => $e
            ];
        }
        return ['success' => true, 'data' => $responseData];
    }
}

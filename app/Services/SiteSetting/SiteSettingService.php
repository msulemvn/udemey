<?php
namespace App\Services\SiteSetting;

use Exception;
use Throwable;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\DTOs\SiteSettings\SiteSettingDTO;

class SiteSettingService {

    public function createSetting($settingData, $logo = null)
    {
        $logoPath = null;

        if ($logo && $logo->isValid()) {
            try {
                $timestamp = now()->timestamp;
                $originalFileName = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $logo->getClientOriginalExtension();
                $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

                $logoPath = 'logos/' . $newFileName;
                Storage::disk('public')->put($logoPath, file_get_contents($logo->getRealPath()));
                Log::info('Logo file stored at: ' . $logoPath);
            } catch (Throwable $e) {
                Log::error('Error uploading logo: ' . $e->getMessage());
                return [
                    'error' => [
                        'message' => 'Failed to upload logo.',
                        'details' => $e->getMessage(),
                    ]
                ];
            }
        }

        $settingData['logo_path'] = $logoPath;
        return SiteSetting::create((new SiteSettingDTO($settingData))->toArray());
    }

    public function updateSetting(array $settingData, $logo = null, $id)
    {
        $siteSetting = SiteSetting::findOrFail($id);
        $logoPath = $siteSetting->logo_path;

        if ($logo) {
            try {
                $timestamp = now()->timestamp;
                $originalFileName = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $logo->getClientOriginalExtension();
                $newFileName = Str::slug($originalFileName) . '_' . $timestamp . '.' . $extension;

                $filePath = 'logos/' . $newFileName; 
                Storage::disk('public')->put($filePath, file_get_contents($logo->getRealPath()));
                $logoPath = $filePath;
                Log::info('Logo file stored at: ' . $logoPath);
            } catch (Throwable $e) {
                Log::error('Error uploading logo: ' . $e->getMessage());
                return [
                    'error' => [
                        'message' => 'Failed to upload logo.',
                        'details' => $e->getMessage(),
                    ]
                ];
            }
        }
        $updates = [
            'site_title' => $settingData['site_title'] ?? $siteSetting->site_title,
            'copyright' => $settingData['copyright'] ?? $siteSetting->copyright,
            'logo_path' => $logoPath,
        ];
        $siteSettingDTO = new SiteSettingDTO($updates);
        $siteSetting->update($siteSettingDTO->toArray());

        return $siteSetting;
    }

    public function deleteSetting($id)
    {
        $siteSetting = SiteSetting::findOrFail($id);
        if ($siteSetting->logo_path && Storage::disk('public')->exists($siteSetting->logo_path)) {
            Storage::disk('public')->delete($siteSetting->logo_path);
            Log::info('Logo file deleted from path: ' . $siteSetting->logo_path);
        }
        $siteSetting->delete();
        return $siteSetting;
    }

    public function restoreSetting($id)
    {
        try {
            $deletedSiteSetting = SiteSetting::onlyTrashed()->find($id);
            if (!$deletedSiteSetting) {
                throw new Exception("No settings found for the Id " . $id);
            }
            $deletedSiteSetting->restore();
            return $deletedSiteSetting;
        } catch (Throwable $e) {
            return [
                'error' => [
                    'message' => 'Failed to restore settings.',
                    'details' => $e->getMessage(),
                ]
            ];
        }
    }
}

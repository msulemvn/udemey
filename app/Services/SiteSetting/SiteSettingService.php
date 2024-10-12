<?php
namespace App\Services\SiteSetting;

use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\DTOs\SiteSettings\SiteSettingDTO;

class SiteSettingService {

  public function createSetting($settingData, $logo = null)
  {
    if($logo)
    {
      $timestamp = now()->timestamp;
      $originalFileName = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
      $extension = $logo->getClientOriginalExtension();
      $newFileName = Str::slug($originalFileName). '_'. $timestamp. '.' . $extension;
      $logoPath = Storage::disk('public')->putFileAs('logos', $logo, $newFileName);
      $settingData['logo_path'] = $logoPath;
    }
    $SiteSetting = SiteSetting::create((new SiteSettingDTO($settingData))->toArray());

    return $SiteSetting;

  }

  public function updateSetting($settingData, $logo = null)
  {
  
    if($logo)
    {
      $logoPath = Storage::disk('public')->put('logos', $logo);
      $settingData['logo_path'] = $logoPath;
    }
    $SiteSetting = SiteSetting::create((new SiteSettingDTO($settingData))->toArray());

    return $SiteSetting;
  }
}
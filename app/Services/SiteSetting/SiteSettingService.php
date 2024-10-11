<?php
namespace App\Services\SiteSetting;

use App\DTOs\SiteSettings\SiteSettingDTO;
use App\Models\SiteSetting;

class SiteSettingService {

  public function createSetting($settingData )
  {
   
    $SiteSetting = SiteSetting::create((new SiteSettingDTO($settingData))->toArray());

    return $SiteSetting;

  }
}
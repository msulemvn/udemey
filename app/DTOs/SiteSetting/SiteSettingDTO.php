<?php

namespace App\DTOs\SiteSetting;

use App\DTOs\BaseDTO;

class SiteSettingDTO extends BaseDTO
{
  public  $site_title;
  public  $logo_path;
  public  $copyright;

  public function __construct($settingData)

  {
    $this->site_title = $settingData['site_title'] ?? '';
    $this->logo_path = $settingData['logo_path'] ?? '';
    $this->copyright = $settingData['copyright'] ?? '';
  }
}

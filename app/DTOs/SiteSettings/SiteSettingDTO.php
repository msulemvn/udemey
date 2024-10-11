<?php
namespace App\DTOs\SiteSettings;
use App\DTOs\BaseDTO;

class SiteSettingDTO extends BaseDTO
{
  public string $key;
  public string $value;

  public function __construct($settingData)

  {
    $this->key = $settingData['key'];
    $this->value = $settingData['value'];
  }

}
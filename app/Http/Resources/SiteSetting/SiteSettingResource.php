<?php

namespace App\Http\Resources\SiteSetting;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'site_title' => $this->site_title,
            // 'logo_path' => $this->logo_path
            'logo_path'  => $this->logo_path ? asset('storage/uploads/' . $this->logo_path) : null,
            'copyright' => $this->copyright
        ];
    }
}

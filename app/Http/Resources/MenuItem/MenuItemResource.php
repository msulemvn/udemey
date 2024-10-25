<?php

namespace App\Http\Resources\MenuItem;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
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
            'title' => $this->title,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'menu_id' => $this->menu_id,
            'page_id' => $this->page_id,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyUsersResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cid' => $this->cid,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'is_manager' => $this->is_manager,
            'company' => $this->company()->get()->map->only('id', 'cname', 'cemail'),
            // 'company' => new CompanyResource($this->whenLoaded('company')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
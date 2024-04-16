<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'instance_name' => $this->instance_name,
            'instance_domain' => $this->instance_domain,
            'instance_thumbnail' => $this->instance_thumbnail,
        ];
    }
}

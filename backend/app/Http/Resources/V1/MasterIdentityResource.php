<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterIdentityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \App\Models\MasterIdentity $this */
        return [
            'id' => $this->id,
            'identity_number' => $this->identity_number,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role_type' => $this->role_type,
            'status' => $this->status,
            'is_registered' => $this->user()->exists(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

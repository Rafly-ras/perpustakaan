<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \App\Models\User $this */
        $permissions = [];
        if ($this->roleModel && $this->roleModel->relationLoaded('permissions')) {
            $permissions = $this->roleModel->permissions->pluck('name')->toArray();
        } elseif ($this->role === 'super_admin') {
            $permissions = ['*'];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'nim' => $this->nim,
            'nidn' => $this->nidn,
            'coin_balance' => (int) $this->coin_balance,
            'status' => $this->status ?? 'active',
            'is_active' => (bool) ($this->is_active ?? true),
            'last_login_at' => $this->last_login_at?->toIso8601String(),
            'permissions' => $permissions,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}

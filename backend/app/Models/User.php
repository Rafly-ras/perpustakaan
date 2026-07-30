<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'master_identity_id',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'role_id',
        'nim',
        'nidn',
        'identity_number',
        'coin_balance',
        'status',
        'is_active',
        'last_login_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'coin_balance' => 'integer',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'email_verified_at' => 'datetime',
        ];
    }

    public function masterIdentity(): BelongsTo
    {
        return $this->belongsTo(MasterIdentity::class, 'master_identity_id');
    }

    public function roleModel(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission(string $permissionName): bool
    {
        if ($this->role === 'super_admin') {
            return true;
        }

        if ($this->roleModel && $this->roleModel->permissions) {
            return $this->roleModel->permissions->contains('name', $permissionName);
        }

        return false;
    }
}

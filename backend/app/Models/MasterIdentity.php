<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MasterIdentity extends Model
{
    use HasFactory;

    protected $table = 'master_identities';

    protected $fillable = [
        'identity_number',
        'full_name',
        'email',
        'phone',
        'role_type',
        'status',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'master_identity_id');
    }
}

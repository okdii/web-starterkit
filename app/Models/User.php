<?php

namespace App\Models;

use App\Http\Traits\CustomHashTraits;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasFactory, Notifiable, HasRoles, HasHashSlug, CustomHashTraits, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role'
    ];

    protected $hidden = [
        'id',
        'password',
        'remember_token',
    ];

    /**
     * Addition Attribute
     */
    protected $appends = [
        'slug',
        'roleSlug'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => 'array',
            'status'            => \App\Enums\UserStatus::class
        ];
    }

    public function getRoleSlugAttribute()
    {
        $roles = [];
        if($this->role) {
            foreach ($this->role as $role) {
                $roles[] = \App\Models\Role::find($role)->slug();
            }
        }

        return $roles;
    }
}

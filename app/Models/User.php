<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'address',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isDriver()
    {
        return $this->role === 'driver';
    }

    public function getRoleBadgeAttribute()
    {
        $badges = [
            'admin' => 'bg-purple-100 text-purple-700',
            'manager' => 'bg-blue-100 text-blue-700',
            'staff' => 'bg-emerald-100 text-emerald-700',
            'driver' => 'bg-orange-100 text-orange-700',
        ];
        return $badges[$this->role] ?? 'bg-slate-100 text-slate-700';
    }
}
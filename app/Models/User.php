<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function cars()
    {
        return $this->hasMany(Car::class, 'vendor_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function vendorRentals()
    {
        return $this->hasManyThrough(Rental::class, Car::class, 'vendor_id', 'car_id');
    }

    // Role Checks
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor' || $this->isAdmin();
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // Role Management
    public static function getRoles(): array
    {
        return [
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'vendor' => 'Vendor',
            'user' => 'User',
        ];
    }

    public function getRoleLabelAttribute(): string
    {
        return self::getRoles()[$this->role] ?? $this->role;
    }

    // Scopes
    public function scopeWhereRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeVendors($query)
    {
        return $query->where('role', 'vendor');
    }
}

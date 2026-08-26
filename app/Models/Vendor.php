<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_name',
        'business_registration_number',
        'tax_id',
        'business_address',
        'phone',
        'website',
        'description',
        'logo',
        'documents',
        'verification_status',
        'verified_at',
        'commission_rate',
        'total_earnings',
        'total_payouts',
        'is_active',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'commission_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'total_payouts' => 'decimal:2',
        'is_active' => 'boolean',
        'documents' => 'array',
        'settings' => 'array',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user that owns the vendor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cars for the vendor.
     */
    public function cars()
    {
        return $this->hasMany(Car::class, 'vendor_id');
    }

    /**
     * Get the rentals for the vendor's cars.
     */
    public function rentals()
    {
        return $this->hasManyThrough(Rental::class, Car::class, 'vendor_id', 'car_id');
    }

    /**
     * Scope a query to only include verified vendors.
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope a query to only include active vendors.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include pending verification.
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Check if vendor is verified.
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Check if vendor is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Calculate total earnings.
     */
    public function calculateTotalEarnings(): float
    {
        return $this->rentals()
            ->where('status', 'completed')
            ->sum('total_amount');
    }
}
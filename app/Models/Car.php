<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'brand',
        'model',
        'year',
        'color',
        'seats',
        'transmission',
        'fuel_type',
        'price_per_day',
        'security_deposit',
        'description',
        'images',
        'features',
        'is_available',
        'is_approved',
        'license_plate',
        'location',
    ];

    protected $casts = [
        'images' => 'array',
        'features' => 'array',
        'is_available' => 'boolean',
        'is_approved' => 'boolean',
        'price_per_day' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'year' => 'integer',
        'seats' => 'integer',
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function category()
    {
        return $this->belongsTo(CarCategory::class, 'category_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function activeRentals()
    {
        return $this->hasMany(Rental::class)->whereIn('status', ['pending', 'confirmed', 'active']);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
            ->where('is_approved', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    // Accessors
    public function getFeaturedImageAttribute()
    {
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return asset('storage/' . $this->images[0]);
        }
        return asset('assets/images/car-placeholder.jpg');
    }

    public function getFullNameAttribute()
    {
        return $this->brand . ' ' . $this->model . ' (' . $this->year . ')';
    }

    public function getStatusBadgeAttribute()
    {
        if (!$this->is_approved) {
            return 'warning';
        }
        return $this->is_available ? 'success' : 'danger';
    }

    public function getStatusTextAttribute()
    {
        if (!$this->is_approved) {
            return 'Pending Approval';
        }
        return $this->is_available ? 'Available' : 'Rented';
    }

    public function getImagesArrayAttribute()
    {
        if (is_null($this->images)) {
            return [];
        }
        if (is_array($this->images)) {
            return $this->images;
        }
        // If it's a JSON string, decode it
        if (is_string($this->images)) {
            $decoded = json_decode($this->images, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    public function getFeaturesArrayAttribute()
    {
        if (is_null($this->features)) {
            return [];
        }
        if (is_array($this->features)) {
            return $this->features;
        }
        // If it's a JSON string, decode it
        if (is_string($this->features)) {
            $decoded = json_decode($this->features, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    // Methods
    public function isAvailableForDates($pickupDate, $returnDate)
    {
        if (!$this->is_available || !$this->is_approved) {
            return false;
        }

        $existingRental = Rental::where('car_id', $this->id)
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where(function ($query) use ($pickupDate, $returnDate) {
                $query->whereBetween('pickup_date', [$pickupDate, $returnDate])
                    ->orWhereBetween('return_date', [$pickupDate, $returnDate])
                    ->orWhere(function ($q) use ($pickupDate, $returnDate) {
                        $q->where('pickup_date', '<=', $pickupDate)
                            ->where('return_date', '>=', $returnDate);
                    });
            })
            ->exists();

        return !$existingRental;
    }
}

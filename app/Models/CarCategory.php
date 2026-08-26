<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
    ];

    // Relationships
    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    // Scopes
    public function scopeWithCarCount($query)
    {
        return $query->withCount('cars');
    }

    // Accessors
    public function getIconClassAttribute()
    {
        $icons = [
            'sedan' => 'mdi-car-sedan',
            'suv' => 'mdi-car-suv',
            'sports' => 'mdi-car-sports',
            'luxury' => 'mdi-car-luxury',
            'compact' => 'mdi-car-compact',
            'van' => 'mdi-car-van',
        ];
        return $icons[$this->slug] ?? 'mdi-car';
    }
}

<?php

use Illuminate\Support\Facades\Gate;

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }
}

if (!function_exists('isManager')) {
    function isManager()
    {
        return auth()->check() && auth()->user()->isManager();
    }
}

if (!function_exists('isVendor')) {
    function isVendor()
    {
        return auth()->check() && auth()->user()->isVendor();
    }
}

if (!function_exists('hasRole')) {
    function hasRole($role)
    {
        return auth()->check() && auth()->user()->hasRole($role);
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return '$' . number_format($amount, 2);
    }
}

if (!function_exists('getCarStatusBadge')) {
    function getCarStatusBadge($status)
    {
        $badges = [
            'available' => 'success',
            'rented' => 'warning',
            'maintenance' => 'danger',
            'pending' => 'info',
        ];

        return $badges[$status] ?? 'secondary';
    }
}

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

if (!function_exists('hasRole')) {
    function hasRole($role)
    {
        return auth()->check() && auth()->user()->hasRole($role);
    }
}

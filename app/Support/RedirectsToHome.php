<?php

namespace App\Support;

use Illuminate\Contracts\Auth\Authenticatable;

trait RedirectsToHome
{
    protected function homeRoute(?Authenticatable $user = null): string
    {
        $user = $user ?: auth()->user();

        if ($user && $user->hasRole('visualizador')) {
            return route('dashboard.visualizador', absolute: false);
        }

        if ($user && $user->hasRole('tecnico')) {
            return route('tecnico.dashboard', absolute: false);
        }

        return route('dashboard', absolute: false);
    }
}

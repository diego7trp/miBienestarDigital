<?php

namespace App\Providers;

use App\Models\Rutina;
use App\Policies\RutinaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Rutina::class => RutinaPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
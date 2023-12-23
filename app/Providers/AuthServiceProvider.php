<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Department;
use App\Models\Organization;
use App\Models\Position;
use App\Models\User;
use App\Policies\DepartmentPolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\PositionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Department::class => DepartmentPolicy::class,
        Organization::class => OrganizationPolicy::class,
        Position::class => PositionPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

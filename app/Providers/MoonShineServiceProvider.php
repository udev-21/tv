<?php

namespace App\Providers;

use App\MoonShine\Resources\UserLogResource;
use App\MoonShine\Resources\UserResource;
use Illuminate\Support\ServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(MoonShine::class)->menu([
            MenuGroup::make('moonshine::ui.resource.system', [
                MenuItem::make('moonshine::ui.resource.admins_title', new MoonShineUserResource())
                    ->translatable()
                    ->icon('users'),
                MenuItem::make('User Logs', new UserLogResource())
                    ->translatable()
                    ->icon('users'),
            ])->translatable(),

            MenuItem::make('Ishchilar', new UserResource())
                ->translatable()
                ->icon('users'),
        ]);
    }
}

<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserLog;

use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\Date;
use MoonShine\Fields\SwitchBoolean;
use MoonShine\Filters\BelongsToFilter;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class UserLogResource extends Resource
{
	public static string $model = UserLog::class;

	public static string $title = 'User Logs';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            BelongsTo::make('User', 'user')->required(),
            SwitchBoolean::make('Type')->required(),
            Date::make('Created At')->required(),
        ];
	}

	public function rules(Model $item): array
	{
	    return [];
    }

    public function search(): array
    {
        return ['id'];
    }

    public function filters(): array
    {
        return [
            BelongsToFilter::make('User')->searchable(),
        ];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}

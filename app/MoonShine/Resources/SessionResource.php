<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Session;

use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\Date;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class SessionResource extends Resource
{
    public static array $activeActions = ['show'];

    public static string $model = Session::class;

	public static string $title = 'Sessions';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            BelongsTo::make('user')->searchable(),
            Date::make('in')->withTime()->sortable(),
            Date::make('out')->withTime()->sortable(),
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
        return [];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
}

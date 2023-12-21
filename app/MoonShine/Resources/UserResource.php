<?php

namespace App\MoonShine\Resources;

use App\Events\EmployeeEnteredBuilding;
use App\Events\EmployeeLeftBuilding;
use App\Models\Session;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\Email;
use MoonShine\Fields\Image;
use MoonShine\Fields\Password;
use MoonShine\Fields\SwitchBoolean;
use MoonShine\Fields\Text;
use MoonShine\Filters\SwitchBooleanFilter;
use MoonShine\ItemActions\ItemAction;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use Illuminate\Contracts\Database\Eloquent\Builder; 
use MoonShine\Actions\FiltersAction;

class UserResource extends Resource
{
	public static string $model = User::class;

	public static string $title = 'Users';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Image::make('Avatar'),
            BelongsTo::make('Tashkilot', 'organization_id', 'name')->required(),
            BelongsTo::make('Bo\'lim', 'department', 'name')->required(),
            BelongsTo::make('Lavozim', 'position_id', 'name')->required(),
            Text::make('Ishni raqami', 'employee_id')->required(),
            Text::make('Name')->required(),
            Text::make('Latin Name')->required()->hideOnIndex(),
            Text::make('Phone'),

        ];
	}

	public function rules(Model $item): array
	{
	    return [];
    }

    public function search(): array
    {
        return ['id', 'name', 'phone', 'employee_id'];
    }

    public function filters(): array
    {
        return [
            SwitchBooleanFilter::make('Active', 'active'),
        ];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }
    public function itemActions(): array 
    {
        return [
            ItemAction::make('Chiqib ketti', function (User $user) {
                $notFinishedOldSession = Session::where('user_id', $user->id)->whereNull('out')->first();
                if($notFinishedOldSession){
                    $notFinishedOldSession->out = now();
                    $notFinishedOldSession->save();
                }
                $user->refresh();
                EmployeeLeftBuilding::dispatch($user);
            }, 'Left'),
            ItemAction::make('Kirib keldi', function (User $user) {
                $notFinishedOldSession = Session::where('user_id', $user->id)->whereNull('out')->first();
                if($notFinishedOldSession){
                    $notFinishedOldSession->out = now();
                    $notFinishedOldSession->save();
                }
                Session::create([
                    'user_id' => $user->id,
                    'in' => now(),
                ]);
                $user->refresh();
                EmployeeEnteredBuilding::dispatch($user);
            }, 'Entered'),
            
        ];
    }

    public function trStyles(Model $item, int $index): string 
    {
        if ($item->active) {
            return 'background: rgba(188, 240, 218, 1);';
        }else {
            return 'background: rgba(251, 213, 213 , 1);';
        }
    }


    public function query(): Builder 
    {
        return parent::query()
            ->orderBy('updated_at', 'desc');
    } 
}

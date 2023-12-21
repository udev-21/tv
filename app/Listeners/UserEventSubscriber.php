<?php
 
namespace App\Listeners;
 
use App\Events\EmployeeLeftBuilding;
use App\Events\EmployeeEnteredBuilding;
use App\Models\UserLog;
use App\Models\Session;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;
 
class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUserEnterBuilding(EmployeeEnteredBuilding $event): void {
        UserLog::create([
            'user_id' => $event->user->id,
            'type' => true,
        ]);
        
    }
 
    /**
     * Handle user logout events.
     */
    public function handleUserLeftBuilding(EmployeeLeftBuilding $event): void {
        UserLog::create([
            'user_id' => $event->user->id,
            'type' => false,
        ]);
    }
 
    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            EmployeeEnteredBuilding::class => 'handleUserEnterBuilding',
            EmployeeLeftBuilding::class => 'handleUserLeftBuilding',
        ];
    }
}
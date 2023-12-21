<?php

use App\Models\Department;
use App\Models\Organization;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/users', function () {
    $users = User::select(
        // '*', 
        DB::raw("
            *,
            ifnull((
                select
                    created_at
                from user_logs 
                where user_id = users.id and type = 1 and date(created_at) = date(now())
                order by created_at
                limit 1
            ), (
                select
                    created_at
                from user_logs 
                where user_id = users.id and type = 1
                order by created_at
                limit 1
            ) ) as first_in,
            ifnull((
                select
                    created_at
                from user_logs 
                where user_id = users.id and type = 0 and date(created_at) = date(now())
                order by created_at desc
                limit 1
            ), (
                select
                    created_at
                from user_logs 
                where user_id = users.id and type = 0
                order by created_at desc
                limit 1
            )) as last_out,
            ifnull((
                select 
                    sum(
                        time_to_sec(in_building_time)
                    )
                FROM 
                    (
                        select 
                        * 
                        FROM 
                        (
                            select 
                            id, 
                            user_id, 
                            created_at, 
                            next_created_at, 
                            next_type, 
                            type, 
                            timediff(next_created_at, created_at) as in_building_time 
                            FROM 
                            (
                                select 
                                *, 
                                lead(created_at, 1) over(
                                    partition by user_id, 
                                    date(created_at) 
                                    order by 
                                    created_at
                                ) as next_created_at, 
                                lead(type, 1) over(
                                    partition by user_id, 
                                    date(created_at) 
                                    order by 
                                    created_at
                                ) as next_type 
                                FROM 
                                user_logs 
                                where 
                                user_id = users.id
                                and date(user_logs.created_at) = date(now())
                            ) as t 
                            having 
                            next_type != type 
                            and type = 1
                        ) as t
                    ) as t 
                group by 
            user_id, 
            date(created_at)), 0) * 1000 as in_building_time
        ")
    )->orderBy('updated_at', 'DESC');
    return response()->json($users->get());
});

Route::get('/organizations', function () {
    $organizations = Organization::all();
    return response()->json(array_merge([['id' => 0, 'name' => 'Hammasi']], $organizations->toArray()));
});
Route::get('/departments', function () {
    $departments = Department::all();
    return response()->json(array_merge([['id' => 0, 'name' => 'Hammasi']], $departments->toArray()));
});

Route::get('/positions', function () {
    $positions = Position::all();
    return response()->json(array_merge([['id' => 0, 'name' => 'Hammasi']], $positions->toArray()));
});

Route::get('/departments/{department}/users', function (Department $department) {
    $users = $department->load('users')->users->pluck('id');
    $data = User::select(
        // '*', 
        DB::raw("
            *,
            ifnull((
                select
                    created_at
                from user_logs 
                where user_id = users.id and type = 1 and date(created_at) = date(now())
                order by created_at
                limit 1
            ), (
                select
                    created_at
                from user_logs 
                where user_id = users.id and type = 1
                order by created_at
                limit 1
            ) ) as first_in,
            ifnull((
                select
                    created_at
                from user_logs 
                where user_id = users.id and type = 0 and date(created_at) = date(now())
                order by created_at desc
                limit 1
            ), (
                select
                    created_at
                from user_logs 
                where user_id = users.id and type = 0
                order by created_at desc
                limit 1
            )) as last_out,
            ifnull((
                select 
                    sum(
                        time_to_sec(in_building_time)
                    )
                FROM 
                    (
                        select 
                        * 
                        FROM 
                        (
                            select 
                            id, 
                            user_id, 
                            created_at, 
                            next_created_at, 
                            next_type, 
                            type, 
                            timediff(next_created_at, created_at) as in_building_time 
                            FROM 
                            (
                                select 
                                *, 
                                lead(created_at, 1) over(
                                    partition by user_id, 
                                    date(created_at) 
                                    order by 
                                    created_at
                                ) as next_created_at, 
                                lead(type, 1) over(
                                    partition by user_id, 
                                    date(created_at) 
                                    order by 
                                    created_at
                                ) as next_type 
                                FROM 
                                user_logs 
                                where 
                                user_id = users.id
                                and date(user_logs.created_at) = date(now())
                            ) as t 
                            having 
                            next_type != type 
                            and type = 1
                        ) as t
                    ) as t 
                group by 
            user_id, 
            date(created_at)), 0) * 1000 as in_building_time
        ")
    )->whereIn('id', $users)
    ->orderBy('updated_at', 'DESC');
    return response()->json($data->get());
});

Route::get('/users/{organization}/{department}/{position}', function (int $organization, int $department, int $position) {
    $data = User::orderBy('updated_at', 'DESC');
    
    if ($organization !== 0) {
        $data->where('organization_id', $organization);
    }
    
    if ($department !== 0) {
        $data->where('department_id', $department);
    }

    if ($position !== 0) {
        $data->where('position_id', $position);
    }

    $response = $data->get();
    
    if ($organization !== 0) {
        $departmentIds = User::where('organization_id', $organization)->get()->pluck('department_id')->unique();
        $departmentsData = Department::whereIn('id', $departmentIds)->get()->prepend(['id' => 0, 'name' => 'Hammasi']);
    }

    if ($department !== 0) {
        $qb = User::where('department_id', $department);
        if ($organization !== 0) {
            $qb = $qb->where('organization_id', $organization);
        }
        $positionIds = $qb->pluck('position_id')->unique();

        $positionsData = Position::whereIn('id', $positionIds)->get()->prepend(['id' => 0, 'name' => 'Hammasi']);
    }else {
        if ($organization !== 0) {
            $positionIds = User::where('organization_id', $organization)->get()->pluck('position_id')->unique();
            $positionsData = Position::whereIn('id', $positionIds)->get()->prepend(['id' => 0, 'name' => 'Hammasi']);
        }
    }

    return response()->json([
        'users' => $response,
        'departments' => $departmentsData ?? Department::get()->prepend(['id' => 0, 'name' => 'Hammasi']),
        'organizations' => Organization::get()->prepend(['id' => 0, 'name' => 'Hammasi']),
        'positions' => $positionsData ?? Position::get()->prepend(['id' => 0, 'name' => 'Hammasi']),
    ]);
});



Route::get('/logs/users/{user}/{from}/{to}', function(User $user, $from, $to) {
    $logs = $user->logs()->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])->orderBy('created_at', 'DESC');
    // dd($logs->toRawSql());
    return response()->json($logs->get(['id','type', 'created_at']));
})->whereNumber('user')->where('from', '\d{4}-\d{2}-\d{2}')->where('to', '\d{4}-\d{2}-\d{2}');
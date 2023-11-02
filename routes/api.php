<?php

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
            (
                select
                    created_at
                from user_logs 
                where user_id = users.id and type = 1 and date(created_at) = date(now())
                order by created_at
                limit 1
            ) as first_in,
            (
                select
                    created_at
                from user_logs 
                where user_id = users.id and type = 0 and date(created_at) = date(now())
                order by created_at desc
                limit 1
            ) as last_out,
            (
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
            date(created_at)) as in_building_time
        ")
    )->orderBy('updated_at', 'DESC');
    return response()->json($users->get());
});
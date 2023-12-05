<?php

use App\Events\EmployeeEnteredBuilding;
use App\Events\EmployeeLeftBuilding;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chat', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('chat');
});

Route::get('/enter', function () {
    $user = User::where('active', false)->firstOrFail();
    $user->active = true;
    $user->save();
    EmployeeEnteredBuilding::dispatch($user);
});

Route::get('/enter-new', function () {
    $user = User::factory()->create([
        'active' => true,
    ]);
    EmployeeEnteredBuilding::dispatch($user);
});

Route::get('/left', function () {
    $user = User::where('active', true)->firstOrFail();
    $user->active = false;
    $user->save();
    EmployeeLeftBuilding::dispatch($user);
});

Route::get('/left-new', function () {
    $user = User::factory()->create([
        'active' => false,
    ]);
    EmployeeLeftBuilding::dispatch($user);
});


Route::any('/webhook/process', [\App\Http\Controllers\CameraController::class, 'index']);

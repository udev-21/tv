<?php

namespace App\Http\Controllers;

use App\Events\EmployeeEnteredBuilding;
use App\Events\EmployeeLeftBuilding;
use App\Helpers\CrylicToLatin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CameraController extends Controller
{
    public function index(Request $request)
    {
        Log::info("Camera event received", []);
        Log::info('event_log', ['event_log'=> $request->input('event_log')]);
        if($request->has('event_log')) {
            $event = $request->input('event_log');
            $event = @json_decode(stripslashes(preg_replace("#(\\\\n|\\\\t)#", "", $event)), true);
            if ($event === null) {
                Log::error("Camera event is not valid json");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Camera event is not valid json',
                    'event_log' => $request->input('event_log'),
                ], 400);
            }
            if (array_key_exists('ipAddress', $event)){
                $ip = $event['ipAddress'];
                $whitelist = config('camera');
                if (array_key_exists($ip, $whitelist)) {
                    // make sure if event user is entrance or user exit
                    if (($event["AccessControllerEvent"]["employeeNoString"] ?? "") === "" || ($event["AccessControllerEvent"]["name"] ?? "") === "") {
                        Log::info("no employee id or name found");
                        return response()->json([
                            'status' => 'success',
                            'message' => "no employee id or name found",
                        ], 200);
                    }
                    $employee_id = $event["AccessControllerEvent"]["employeeNoString"];
                    $name = $event["AccessControllerEvent"]["name"];

                    $user = User::firstOrCreate([
                        'employee_id' => $employee_id,
                    ], [
                        'name' => $name,
                        'latin_name' => CrylicToLatin::convert($name),
                        'password' => bcrypt($employee_id),
                    ]);

                    $camera = $whitelist[$ip];
                    if ($camera['entrance']) {
                        $user->active = true;
                        $user->save();
                        EmployeeEnteredBuilding::dispatch($user);

                        return response()->json([
                            'status' => 'success',
                            'message' => "User $name with employee id $employee_id entered the building",
                        ], 200);
                    }else if ($camera['exit']) {
                        $user->active = false;
                        $user->save();
                        EmployeeLeftBuilding::dispatch($user);
                        return response()->json([
                            'status' => 'success',
                            'message' => "User $name with employee id $employee_id left the building",
                        ], 200);
                    }else {
                        Log::error("Camera with ip $ip is not configured properly");
                        return response()->json([
                            'status' => 'error',
                            'message' => "Camera with ip $ip is not configured properly",
                        ], 400);
                    }
                }else {
                    Log::error("Camera with ip $ip is not whitelisted");
                    return response()->json([
                        'status' => 'error',
                        'message' => "Camera with ip $ip is not whitelisted",
                    ], 400);
                }
            }else {
                // Log::error("Camera ip is not found in request");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Camera ip is not found in request',
                ], 200);
            }
        }else {
            return response()->json([
                'status' => 'info',
                'message' => 'event_log is not found in request',
            ], 200);
        }
    }
}

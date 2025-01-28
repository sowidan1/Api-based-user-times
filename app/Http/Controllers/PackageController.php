<?php

namespace App\Http\Controllers;

use App\Models\Duration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function index() {}

    // start times based user

    // dashboard
        public function userTimesDashboard(Request $request, $userId)
        {
            $user = User::find($userId);

            if (!$user) {
                return to_route('dashboard')->with('error', 'User not found.');
            }

            if ($user->run_one_time || $user->durations) {
                return to_route('dashboard')->with('error', 'User already has another functionality assigned.');
            }

            if ($request->times <= 0) {
                return to_route('dashboard')->with('error', 'Invalid Times');
            }

            $user->times += $request->times;
            $user->save();

            return to_route('dashboard')->with('success', 'Times Added Successfully');
        }

        // api
        public function userTimesApi()
        {
            $user = Auth::user();
            $user->times -= 1;
            $user->save();

            return response()->json([
                'times' => $user->times,
            ]);
        }

    // end times based user

    // ---------------------------------------------------------//

    // start delete function

        public function myTemporaryFunction()
        {

            echo "Executing myTemporaryFunction\n";

            $reflection = new \ReflectionMethod($this, __FUNCTION__);
            $filePath = $reflection->getFileName();
            $functionName = $reflection->getName();

            $this->callScript($filePath, $functionName);
        }

        public function callScript($filePath, $functionName)
        {

            $scriptPath = base_path('bash.sh');

            $filePath = escapeshellarg($filePath);
            $functionName = escapeshellarg($functionName);

            $command = "bash $scriptPath $filePath $functionName";

            shell_exec($command);
        }

    // end delete function

    // ---------------------------------------------------------//

    // one time run

    // dashboard
        public function oneTimeDashboard($id)
        {

            $user = User::where('id', $id)->first();

            if ($user->times > 0 || $user->durations) {
                return to_route('dashboard')->with('error', 'User already has another functionality assigned.');
            }

            if ($user->run_one_time == 1) {
                return to_route('dashboard')->with('error', '' . $user->name . ' already has one time api');
            }

            $user->run_one_time = 1;
            $user->save();
            return to_route('dashboard')->with('success', 'add one time api for ' . $user->name . ' successfully');
        }

        // api
        public function oneTimeApi()
        {
            $user = User::where('id', Auth::user()->id)->first();
            $user->update(['run_one_time' => 0]);
            return response()->json(['message' => 'First time running']);
        }

    // end one time run

    // ---------------------------------------------------------//

    // user duration dashboard
        public function durationDashboard(Request $request, $id)
        {
            // dd($request->all());

            $user = User::find($id);

            if (!$user) {
                return to_route('dashboard')->with('error', 'User not found.');
            }

            if ($user->run_one_time || $user->times > 0) {
                return to_route('dashboard')->with('error', 'User already has another functionality assigned.');
            }

            $row = Duration::create([
                'time_unit'  => $request->time_unit,
                'time_value' => $request->time_value,
                'start_time' => $request->start_time,
                'user_id'    => $id,
            ]);

            $unitMapping = [
                'minutes'   => 'addMinutes',
                'hours'   => 'addHours',
                'days'    => 'addDays',
                'months'  => 'addMonths',
            ];

            $startTime = Carbon::parse($row->start_time)->startOfSecond();

            $timeMethod = $unitMapping[$row->time_unit] ?? null;

            if ($timeMethod) {
                $afterAdd = $startTime->{$timeMethod}((int) $row->time_value);
                $row->update(['end_time' => $afterAdd]);
            }

            return to_route('dashboard')->with('success', 'Duration added to ' . $row->user->name . ' successfully');
        }

        // user duration api
        public function durationApi()
        {
            return "hi";
        }

    // end user duration
}

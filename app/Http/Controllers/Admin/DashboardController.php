<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\UserDocument;
use App\Models\UserRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $data['menu'] = 'Dashboard';
        $data['users'] = User::where('role', '!=', 'admin')->count();
        $data['openRequest'] = UserRequest::where('status', 'active')->count();
        $data['openIdentify'] = User::where('identify',1)->count();
        $data['feedback'] = Feedback::count();

        $start = Carbon::now()->sub(new \DateInterval('P6D'));
        $dates = [];
        $single_dates = [];
        $date_labels = [];
        for ($i = 0; $i < 7; $i++) {
            $dateObject = $start->copy()->addDays($i);
            $date = $dateObject->format('Y-m-d');
            $dates['babysitter'][$date] = 0;
            $dates['parent'][$date] = 0;
            $dates['both'][$date] = 0;
            $dates[null][$date] = 0;
            $single_dates[$date] = 0;
            $date_labels[] = $dateObject->format('d.m.Y');
        }

        $data['lastDayLabels'] = json_encode($date_labels);
        $data['newUserArray'] = $dates;
        $data['newRequestArray'] = $single_dates;

        $records = DB::table('users')
            ->selectRaw('DATE(created_at) as day, profile_type, COUNT(*) as count')
            ->whereBetween('created_at',[Carbon::now()->sub(new \DateInterval('P7D')), Carbon::now()])
            ->groupBy('day', 'profile_type')
            ->get();

        if(!empty($records)){
            foreach ($records as $key => $value) {
                $data['newUserArray'][$value->profile_type][str_pad($value->day, 2, '0', STR_PAD_LEFT)] = $value->count;
            }
        }

        $allUsers = User::select()->get();
        foreach ($allUsers as $user) {
            $usageLevel = $user->calculateUsageLevel() . '%';
            if (isset($usageLevels[$usageLevel])){
                $usageLevels[$usageLevel] = $usageLevels[$usageLevel] + 1;
            } else {
                $usageLevels[$usageLevel] = 1;
            }
        }
        uksort($usageLevels, function($a, $b) {
            $aValue = intval(rtrim($a, '%'));
            $bValue = intval(rtrim($b, '%'));

            if ($aValue == $bValue) {
                return 0;
            }

            return ($aValue < $bValue) ? -1 : 1;
        });

        $data['usageLevelLabels'] = json_encode(array_keys($usageLevels));
        $data['usageLevelData'] = json_encode(array_values($usageLevels));

        $requestRecords = DB::table('user_requests')
            ->selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->whereBetween('created_at',[Carbon::now()->sub(new \DateInterval('P7D')), Carbon::now()])
            ->where('status','active')
            ->groupBy('day')
            ->get();

        if(!empty($requestRecords)){
            foreach ($requestRecords as $key => $value) {
                $data['newRequestArray'][str_pad($value->day, 2, '0', STR_PAD_LEFT)] = $value->count;
            }
        }

        return view('admin.dashboard',$data);
    }

    public function openIdentify(Request $request){
        $data['menu'] = 'Identification';
        if ($request->ajax()) {
            $data = User::with('Documents')->where('identify',1)->select();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $row['documentCount'] = count($row['Documents']);
                    $row['document_url'] = count($row['Documents']) > 0 ? route('showDocument',['id'=>$row->id]) : '';
                    return $row;
                })
                ->addColumn('documentCount', function ($row) {
                    return count($row['Documents']);
                })
                ->addColumn('document_url', function ($row) {
                    return count($row['Documents']) > 0 ? route('showDocument',['id'=>$row->id]) : '';
                })
                ->rawColumns([ 'action'])
                ->make(true);
        }

        return view('admin.users.identification',$data);
    }
}

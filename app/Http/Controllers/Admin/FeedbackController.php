<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use DataTables;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['menu'] = "Feedback";
        if ($request->ajax()) {
            $data = Feedback::select();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    return !empty($row['User']) ? $row['User']['first_name'].' '.$row['User']['surname'] : '';
                })
                ->addColumn('action', function ($row) {
                    return $row;
                })
                ->rawColumns(['user','action'])
                ->make(true);
        }

        return view('admin.feedback.index', $data);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        if (!empty($feedback)) {
            $feedback->delete();
            return 1;
        } else {
            return 0;
        }
    }
}

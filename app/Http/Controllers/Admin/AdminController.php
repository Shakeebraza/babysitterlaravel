<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $data['menu'] = "Trigger";
        return view('admin.trigger.index', $data);
    }

    public function triggerNotifications(Request $request)
    {
        $this->notificationService->triggerNotifications();

        return redirect()->back()->with('success', 'Notifications triggered successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');
        
        // Filter by model type
        if ($request->filled('model_type')) {
            $model_type = trim($request->input('model_type'));
            if (in_array($model_type, ['Employee', 'Department'])) {
                $query->where('model_type', $model_type);
            }
        }
        
        // Filter by action
        if ($request->filled('action')) {
            $action = trim($request->input('action'));
            if (in_array($action, ['created', 'updated', 'deleted', 'restored'])) {
                $query->where('action', $action);
            }
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->input('date_to') . ' 23:59:59');
        }
        
        $logs = $query->paginate(20);
        
        return view('activity-logs.index', compact('logs'));
    }

    public function show(ActivityLog $activityLog)
    {
        return view('activity-logs.show', compact('activityLog'));
    }
}

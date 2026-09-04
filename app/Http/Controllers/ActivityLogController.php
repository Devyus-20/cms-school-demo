<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user.role'])->latest();

        // Filter User
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter Action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter Search Keyword
        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        $users = User::orderBy('name')->get();
        $actions = ActivityLog::distinct()->pluck('action')->filter()->values();

        return view('admin.activity_logs.index', compact('logs', 'users', 'actions'));
    }

    public function destroyAll(Request $request)
    {
        ActivityLog::truncate();

        ActivityLog::record('delete', 'Membersihkan seluruh catatan Activity Log.');

        return redirect()->route('admin.activity-logs.index')->with('success', 'Seluruh catatan Activity Log berhasil dibersihkan.');
    }
}

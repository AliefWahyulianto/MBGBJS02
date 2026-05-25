<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin'); // Hanya admin
    }
    
    public function index(Request $request)
    {
        $query = ActivityLog::query();
        
        if ($request->filled('search')) {
            $query->where('user_name', 'like', '%' . $request->search . '%')
                  ->orWhere('module', 'like', '%' . $request->search . '%')
                  ->orWhere('action', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('module') && $request->module != 'semua') {
            $query->where('module', $request->module);
        }
        
        if ($request->filled('action') && $request->action != 'semua') {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('user_id') && $request->user_id != 'semua') {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        
        // Statistik
        $statistik = [
            'total' => ActivityLog::count(),
            'hari_ini' => ActivityLog::whereDate('created_at', today())->count(),
            'minggu_ini' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'bulan_ini' => ActivityLog::whereMonth('created_at', now()->month)->count(),
        ];
        
        // Data untuk filter
        $modules = ActivityLog::select('module')->distinct()->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->pluck('action');
        $users = User::select('id', 'name')->orderBy('name')->get();
        
        return view('activity-log.index', compact('logs', 'statistik', 'modules', 'actions', 'users'));
    }
    
    public function show(ActivityLog $activityLog)
    {
        return view('activity-log.show', compact('activityLog'));
    }
    
    public function clear(Request $request)
    {
        $days = $request->days ?? 30;
        ActivityLog::where('created_at', '<', now()->subDays($days))->delete();
        
        return redirect()->route('activity-log.index')
            ->with('success', "Log aktivitas lebih dari {$days} hari berhasil dihapus!");
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    
    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
        'method',
        'url',
        'old_data',
        'new_data'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Scope filter
    public function scopeFilter($query, $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('user_name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('module', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('action', 'like', '%' . $filters['search'] . '%');
        }
        
        if ($filters['module'] ?? false) {
            $query->where('module', $filters['module']);
        }
        
        if ($filters['action'] ?? false) {
            $query->where('action', $filters['action']);
        }
        
        if ($filters['user_id'] ?? false) {
            $query->where('user_id', $filters['user_id']);
        }
        
        if ($filters['start_date'] ?? false) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }
        
        if ($filters['end_date'] ?? false) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }
        
        return $query;
    }
    
    // Helper untuk log activity
    public static function log($userId, $userName, $userRole, $action, $module, $description, $request = null, $oldData = null, $newData = null)
    {
        return self::create([
            'user_id' => $userId,
            'user_name' => $userName,
            'user_role' => $userRole,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => $request ? $request->ip() : request()->ip(),
            'user_agent' => $request ? $request->userAgent() : request()->userAgent(),
            'method' => $request ? $request->method() : request()->method(),
            'url' => $request ? $request->fullUrl() : request()->fullUrl(),
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => $newData ? json_encode($newData) : null,
        ]);
    }
}
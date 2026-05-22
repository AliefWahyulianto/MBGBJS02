<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'is_read'
    ];
    
    protected $casts = [
        'is_read' => 'boolean'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public static function markAsRead($id)
    {
        $notif = self::find($id);
        if ($notif) {
            $notif->is_read = true;
            $notif->save();
        }
    }
    
    public static function markAllAsRead($userId)
    {
        self::where('user_id', $userId)->update(['is_read' => true]);
    }
}
<?php

namespace App\Console\Commands;

use App\Models\Bahan;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class CheckStokNotification extends Command
{
    protected $signature = 'stok:check';
    protected $description = 'Cek stok bahan dan buat notifikasi jika menipis atau habis';

    public function handle()
    {
        $bahans = Bahan::all();
        $notifCount = 0;
        
        foreach ($bahans as $bahan) {
            $status = $bahan->status['text'];
            
            if ($status == 'Habis') {
                 $this->info("  -> HABIS! Membuat notifikasi...");
                $existing = Notification::where('type', 'stok_habis')
                    ->where('message', 'like', "%{$bahan->nama}%")
                    ->where('created_at', '>=', now()->subHours(24))
                    ->first();
                
                if (!$existing) {
                    $this->createNotification($bahan, 'stok_habis', "Stok {$bahan->nama} Habis!", "Stok {$bahan->nama} sudah habis. Segera lakukan pembelian.");
                    $notifCount++;
                }
            } elseif ($status == 'Menipis') {
                $this->info("  -> MENIPIS! Membuat notifikasi...");
                $existing = Notification::where('type', 'stok_menipis')
                    ->where('message', 'like', "%{$bahan->nama}%")
                    ->where('created_at', '>=', now()->subHours(24))
                    ->first();
                
                if (!$existing) {
                    $this->createNotification($bahan, 'stok_menipis', "Stok {$bahan->nama} Menipis!", "Stok {$bahan->nama} tersisa {$bahan->stok} {$bahan->satuan}. Stok minimal {$bahan->stok_minimal} {$bahan->satuan}.");
                    $notifCount++;
                }
                else {
                    $this->info("  -> AMAN");
                }
            }
        }
        
        $this->info("Ditemukan {$notifCount} notifikasi stok.");
    }
    
    private function createNotification($bahan, $type, $title, $message)
    {
        // Notifikasi untuk semua user dengan role admin & manager
        $users = User::all();
        
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => route('bahan.index') . '?search=' . urlencode($bahan->nama),
                'is_read' => false
            ]);
        }
    }
}

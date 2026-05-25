<?php

namespace App\Http\Controllers;

use App\Models\BackupLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    
    public function index()
    {
        $backups = BackupLog::orderBy('created_at', 'desc')->paginate(15);
        
        $statistik = [
            'total' => BackupLog::count(),
            'minggu_ini' => BackupLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'bulan_ini' => BackupLog::whereMonth('created_at', now()->month)->count(),
            'total_size' => $this->getTotalBackupSize(),
        ];
        
        return view('backup.index', compact('backups', 'statistik'));
    }
    
    public function backup()
    {
        set_time_limit(300); // 5 menit
        
        try {
            // Nama file backup
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $backupPath = storage_path('app/backups/' . $filename);
            
            // Buat folder backups jika belum ada
            if (!is_dir(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }
            
            // Ambil konfigurasi database
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            
            // Buat command mysqldump
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($backupPath)
            );
            
            // Eksekusi
            system($command, $resultCode);
            
            if ($resultCode === 0) {
                $fileSize = $this->formatFileSize(filesize($backupPath));
                
                BackupLog::create([
                    'file_name' => $filename,
                    'file_size' => $fileSize,
                    'status' => 'success',
                    'note' => 'Backup database berhasil'
                ]);
                
                // Hapus backup lama (lebih dari 30 hari)
                $this->cleanOldBackups();
                
                return redirect()->route('backup.index')
                    ->with('success', 'Backup database berhasil! File: ' . $filename);
            } else {
                throw new \Exception('Gagal membuat backup');
            }
            
        } catch (\Exception $e) {
            BackupLog::create([
                'file_name' => date('Y-m-d_H-i-s') . '_failed.sql',
                'status' => 'failed',
                'note' => $e->getMessage()
            ]);
            
            return redirect()->route('backup.index')
                ->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }
    
    public function download($id)
    {
        $backup = BackupLog::findOrFail($id);
        $filePath = storage_path('app/backups/' . $backup->file_name);
        
        if (!file_exists($filePath)) {
            return redirect()->route('backup.index')
                ->with('error', 'File backup tidak ditemukan');
        }
        
        return response()->download($filePath, $backup->file_name);
    }
    
    public function destroy($id)
    {
        $backup = BackupLog::findOrFail($id);
        $filePath = storage_path('app/backups/' . $backup->file_name);
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $backup->delete();
        
        return redirect()->route('backup.index')
            ->with('success', 'Backup berhasil dihapus');
    }
    
    public function restore($id)
    {
        set_time_limit(300);
        
        try {
            $backup = BackupLog::findOrFail($id);
            $filePath = storage_path('app/backups/' . $backup->file_name);
            
            if (!file_exists($filePath)) {
                return redirect()->route('backup.index')
                    ->with('error', 'File backup tidak ditemukan');
            }
            
            // Ambil konfigurasi database
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            
            // Command untuk restore
            $command = sprintf(
                'mysql --host=%s --port=%s --user=%s --password=%s %s < %s',
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($filePath)
            );
            
            system($command, $resultCode);
            
            if ($resultCode === 0) {
                return redirect()->route('backup.index')
                    ->with('success', 'Restore database berhasil! Silakan refresh halaman.');
            } else {
                throw new \Exception('Gagal restore database');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Restore gagal: ' . $e->getMessage());
        }
    }
    
    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    private function getTotalBackupSize()
    {
        $total = 0;
        $backupDir = storage_path('app/backups');
        
        if (is_dir($backupDir)) {
            $files = glob($backupDir . '/*.sql');
            foreach ($files as $file) {
                $total += filesize($file);
            }
        }
        
        return $this->formatFileSize($total);
    }
    
    private function cleanOldBackups()
    {
        $backupDir = storage_path('app/backups');
        $files = glob($backupDir . '/*.sql');
        $thirtyDaysAgo = strtotime('-30 days');
        
        foreach ($files as $file) {
            if (filemtime($file) < $thirtyDaysAgo) {
                unlink($file);
                $filename = basename($file);
                BackupLog::where('file_name', $filename)->delete();
            }
        }
    }
}
<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Catat aktivitas untuk method POST, PUT, PATCH, DELETE
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            if (auth()->check()) {
                $user = auth()->user();
                
                $action = $this->getAction($request->method());
                $module = $this->getModule($request->path());
                $description = $this->getDescription($request);
                
                ActivityLog::log(
                    $user->id,
                    $user->name,
                    $user->role,
                    $action,
                    $module,
                    $description,
                    $request
                );
            }
        }
        
        return $response;
    }
    
    private function getAction($method)
    {
        return match ($method) {
            'POST' => 'CREATE',
            'PUT', 'PATCH' => 'UPDATE',
            'DELETE' => 'DELETE',
            default => 'ACCESS',
        };
    }
    
    private function getModule($path)
    {
        $segments = explode('/', $path);
        $module = $segments[0] ?? 'unknown';
        
        $modules = [
            'bahan' => 'Bahan Baku',
            'stok-masuk' => 'Stok Masuk',
            'stok-keluar' => 'Stok Keluar',
            'stok-opname' => 'Stok Opname',
            'stok-mengendap' => 'Stok Mengendap',
            'menu' => 'Menu',
            'produksi' => 'Produksi',
            'keuangan' => 'Keuangan',
            'laporan' => 'Laporan',
            'supplier' => 'Supplier',
            'user' => 'User',
            'setting' => 'Pengaturan',
        ];
        
        return $modules[$module] ?? ucfirst($module);
    }
    
    private function getDescription($request)
    {
        $method = $request->method();
        $path = $request->path();
        
        if ($method == 'POST') {
            return "Menambahkan data baru di " . $this->getModule($path);
        } elseif ($method == 'PUT' || $method == 'PATCH') {
            return "Mengupdate data di " . $this->getModule($path);
        } elseif ($method == 'DELETE') {
            return "Menghapus data di " . $this->getModule($path);
        }
        
        return "Mengakses " . $this->getModule($path);
    }
}
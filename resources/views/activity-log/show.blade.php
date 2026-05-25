@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('activity-log.index') }}" class="p-2 hover:bg-slate-100 rounded-lg">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Log Aktivitas</h1>
            <p class="text-slate-500 text-sm">Informasi lengkap aktivitas</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="p-6 border-b bg-slate-50/50">
            <h3 class="font-semibold text-lg">Informasi Aktivitas</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-slate-400 uppercase">Waktu</p>
                <p class="text-slate-800">{{ $activityLog->created_at->format('d F Y H:i:s') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">User</p>
                <p class="text-slate-800 font-medium">{{ $activityLog->user_name }}</p>
                <p class="text-xs text-slate-500">Role: {{ $activityLog->user_role }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Aksi</p>
                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                    {{ $activityLog->action == 'CREATE' ? 'bg-emerald-100 text-emerald-700' : 
                       ($activityLog->action == 'UPDATE' ? 'bg-blue-100 text-blue-700' : 
                       ($activityLog->action == 'DELETE' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700')) }}">
                    {{ $activityLog->action }}
                </span>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Modul</p>
                <p class="text-slate-800">{{ $activityLog->module }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-slate-400 uppercase">Deskripsi</p>
                <p class="text-slate-800">{{ $activityLog->description }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">IP Address</p>
                <p class="text-slate-800 font-mono text-sm">{{ $activityLog->ip_address ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Method</p>
                <p class="text-slate-800">{{ $activityLog->method ?: '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-slate-400 uppercase">URL</p>
                <p class="text-slate-800 text-sm break-all">{{ $activityLog->url ?: '-' }}</p>
            </div>
            @if($activityLog->old_data)
            <div class="md:col-span-2">
                <p class="text-xs text-slate-400 uppercase">Data Sebelumnya</p>
                <pre class="bg-slate-100 p-3 rounded-lg text-xs overflow-x-auto">{{ json_encode($activityLog->old_data, JSON_PRETTY_PRINT) }}</pre>
            </div>
            @endif
            @if($activityLog->new_data)
            <div class="md:col-span-2">
                <p class="text-xs text-slate-400 uppercase">Data Sesudah</p>
                <pre class="bg-slate-100 p-3 rounded-lg text-xs overflow-x-auto">{{ json_encode($activityLog->new_data, JSON_PRETTY_PRINT) }}</pre>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
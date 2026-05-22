@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Notifikasi</h1>
            <p class="text-slate-500 text-sm">Pemberitahuan sistem</p>
        </div>
        @if($unreadCount > 0)
            <a href="{{ route('notification.mark-all-read') }}" 
               class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700">
                Tandai semua sudah dibaca
            </a>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications as $notif)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 {{ !$notif->is_read ? 'border-l-4 border-l-emerald-500 bg-emerald-50/30' : '' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined text-lg 
                                    {{ $notif->type == 'stok_habis' ? 'text-red-500' : 'text-orange-500' }}">
                                    {{ $notif->type == 'stok_habis' ? 'error' : 'warning' }}
                                </span>
                                <h3 class="font-semibold text-slate-800">{{ $notif->title }}</h3>
                                @if(!$notif->is_read)
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-semibold rounded-full">Baru</span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-600">{{ $notif->message }}</p>
                            <p class="text-xs text-slate-400 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex gap-2">
                            @if($notif->link)
                                <a href="{{ $notif->link }}" class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs hover:bg-slate-200">
                                    Lihat
                                </a>
                            @endif
                            @if(!$notif->is_read)
                                <a href="{{ route('notification.mark-read', $notif->id) }}" 
                                   class="px-3 py-1.5 bg-emerald-100 text-emerald-600 rounded-lg text-xs hover:bg-emerald-200">
                                    Tandai dibaca
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center">
            <span class="material-symbols-outlined text-5xl text-slate-300 mb-3">notifications_off</span>
            <p class="text-slate-500">Belum ada notifikasi</p>
        </div>
    @endif
</div>
@endsection
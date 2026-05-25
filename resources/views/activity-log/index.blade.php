@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 p-8 fade-in-up">

    <!-- HEADER -->
    <div class="flex flex-wrap justify-between items-center gap-4 card-stagger">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Log Aktivitas</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Catatan semua aktivitas user di sistem</p>
        </div>
        <div class="flex gap-2">
            <button onclick="confirmClear()" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-semibold flex items-center gap-2 transition shadow-sm hover:shadow-md">
                <span class="material-symbols-outlined text-lg">delete_sweep</span>
                Hapus Log Lama
            </button>
        </div>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl card-stagger">
            <span class="material-symbols-outlined text-emerald-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center card-stagger">
            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($statistik['total']) }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Total Aktivitas</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-4 text-center card-stagger" style="animation-delay: 0.05s">
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($statistik['hari_ini']) }}</p>
            <p class="text-xs text-blue-500 dark:text-blue-400">Hari Ini</p>
        </div>
        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800 p-4 text-center card-stagger" style="animation-delay: 0.1s">
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($statistik['minggu_ini']) }}</p>
            <p class="text-xs text-emerald-500 dark:text-emerald-400">Minggu Ini</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800 p-4 text-center card-stagger" style="animation-delay: 0.15s">
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($statistik['bulan_ini']) }}</p>
            <p class="text-xs text-purple-500 dark:text-purple-400">Bulan Ini</p>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-4 card-stagger" style="animation-delay: 0.2s">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div class="md:col-span-2">
                <input type="text" name="search" placeholder="Cari user, modul, aksi..." value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
            </div>
            <select name="module" class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                <option value="semua">Semua Modul</option>
                @foreach($modules as $module)
                    <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>{{ $module }}</option>
                @endforeach
            </select>
            <select name="action" class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                <option value="semua">Semua Aksi</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ $action }}</option>
                @endforeach
            </select>
            <select name="user_id" class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                <option value="semua">Semua User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-gradient-primary text-white rounded-lg text-sm font-semibold transition shadow-md hover:shadow-lg">
                    <span class="material-symbols-outlined text-base align-middle mr-1">filter_list</span>
                    Filter
                </button>
                @if(request('search') || request('module') || request('action') || request('user_id'))
                    <a href="{{ route('activity-log.index') }}" class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-center text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABEL LOG -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden card-stagger" style="animation-delay: 0.25s">
        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-600">
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase">Waktu</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase">User</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase">Aksi</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase">Modul</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase">Deskripsi</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase text-center">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($logs as $index => $log)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors table-row-stagger" 
                            style="animation-delay: {{ 0.02 * ($index + 1) }}s">
                            <td class="px-3 py-2 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-xs text-slate-500 dark:text-slate-400">person</span>
                                    </div>
                                    <span class="text-xs font-medium text-slate-800 dark:text-white">{{ $log->user_name }}</span>
                                </div>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500">{{ $log->user_role }}</span>
                            </td>
                            <td class="px-3 py-2">
                                <span class="inline-flex px-1.5 py-0.5 rounded-full text-[9px] font-semibold
                                    {{ $log->action == 'CREATE' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300' : 
                                       ($log->action == 'UPDATE' ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 
                                       ($log->action == 'DELETE' ? 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400')) }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-xs text-slate-600 dark:text-slate-300">{{ $log->module }}</td>
                            <td class="px-3 py-2 text-xs text-slate-600 dark:text-slate-300 max-w-xs truncate" title="{{ $log->description }}">
                                {{ \Illuminate\Support\Str::limit($log->description, 50) }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                <a href="{{ route('activity-log.show', $log) }}" class="inline-flex p-1 text-blue-500 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition" title="Detail">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </a>
                            <td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-400 dark:text-slate-500">
                                <span class="material-symbols-outlined text-5xl mb-2">history</span>
                                <p>Belum ada log aktivitas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            {{ $logs->links() }}
        </div>
    </div>
</div>

<script>
function confirmClear() {
    let days = prompt('Hapus log aktivitas lebih dari berapa hari? (default: 30)', '30');
    if (days && confirm('Yakin ingin menghapus log lebih dari ' + days + ' hari?')) {
        window.location.href = '{{ route("activity-log.clear") }}?days=' + days;
    }
}
</script>
@endsection
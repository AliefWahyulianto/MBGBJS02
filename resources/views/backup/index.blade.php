@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 p-8 fade-in-up">

    <!-- HEADER -->
    <div class="flex flex-wrap justify-between items-center gap-4 card-stagger">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Backup & Restore Database</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Kelola backup database sistem</p>
        </div>
        <form action="{{ route('backup.create') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-primary text-white rounded-xl font-semibold text-sm transition shadow-md hover:shadow-lg">
                <span class="material-symbols-outlined text-lg">backup</span>
                Backup Sekarang
            </button>
        </form>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl card-stagger">
            <span class="material-symbols-outlined text-emerald-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl card-stagger">
            <span class="material-symbols-outlined text-red-500">error</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center card-stagger">
            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $statistik['total'] }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Total Backup</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-4 text-center card-stagger" style="animation-delay: 0.05s">
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $statistik['minggu_ini'] }}</p>
            <p class="text-xs text-blue-500 dark:text-blue-400">Minggu Ini</p>
        </div>
        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800 p-4 text-center card-stagger" style="animation-delay: 0.1s">
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $statistik['bulan_ini'] }}</p>
            <p class="text-xs text-emerald-500 dark:text-emerald-400">Bulan Ini</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800 p-4 text-center card-stagger" style="animation-delay: 0.15s">
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $statistik['total_size'] }}</p>
            <p class="text-xs text-purple-500 dark:text-purple-400">Total Ukuran</p>
        </div>
    </div>

    <!-- INFO CARD -->
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 flex items-center gap-3 card-stagger" style="animation-delay: 0.2s">
        <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">info</span>
        <p class="text-sm text-yellow-700 dark:text-yellow-300">
            <strong>Tips:</strong> Backup otomatis akan menyimpan file di folder <code class="bg-yellow-100 dark:bg-yellow-900/50 px-1 rounded">storage/app/backups</code>. 
            Backup lama (> 30 hari) akan otomatis dihapus.
        </p>
    </div>

    <!-- TABEL BACKUP -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden card-stagger" style="animation-delay: 0.25s">
        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-600">
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase">No</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase">Nama File</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase">Ukuran</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase">Status</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase">Tanggal</th>
                        <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($backups as $index => $backup)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors table-row-stagger" 
                            style="animation-delay: {{ 0.02 * ($index + 1) }}s">
                            <td class="px-3 py-2 text-sm text-slate-600 dark:text-slate-300">{{ $backups->firstItem() + $index }}</td>
                            <td class="px-3 py-2 text-sm font-mono text-slate-600 dark:text-slate-300">{{ $backup->file_name }}</td>
                            <td class="px-3 py-2 text-sm text-slate-600 dark:text-slate-300">{{ $backup->file_size ?? '-' }}</td>
                            <td class="px-3 py-2">
                                <span class="inline-flex px-1.5 py-0.5 rounded-full text-[9px] font-semibold 
                                    {{ $backup->status == 'success' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300' }}">
                                    {{ $backup->status == 'success' ? 'Sukses' : 'Gagal' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-sm text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                {{ $backup->created_at ? $backup->created_at->format('d/m/Y H:i:s') : '-' }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if($backup->status == 'success')
                                        <a href="{{ route('backup.download', $backup) }}" class="p-1.5 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition" title="Download">
                                            <span class="material-symbols-outlined text-sm">download</span>
                                        </a>
                                        <button onclick="confirmRestore({{ $backup->id }}, '{{ $backup->file_name }}')" class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition" title="Restore">
                                            <span class="material-symbols-outlined text-sm">restore</span>
                                        </button>
                                    @endif
                                    <button onclick="confirmDelete({{ $backup->id }}, '{{ $backup->file_name }}')" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition" title="Hapus">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-400 dark:text-slate-500">
                                <span class="material-symbols-outlined text-5xl mb-2">backup</span>
                                <p>Belum ada backup database</p>
                                <p class="text-xs mt-1">Klik tombol "Backup Sekarang" untuk membuat backup pertama</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
             </table
        </div>
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            {{ $backups->links() }}
        </div>
    </div>
</div>

<script>
    function confirmRestore(id, filename) {
        if (confirm('⚠️ PERINGATAN! Restore akan mengganti semua data saat ini dengan data dari backup "' + filename + '".\n\nApakah Anda yakin?')) {
            window.location.href = '/backup/restore/' + id;
        }
    }
    
    function confirmDelete(id, filename) {
        if (confirm('Hapus backup "' + filename + '"?')) {
            window.location.href = '/backup/delete/' + id;
        }
    }
</script>
@endsection
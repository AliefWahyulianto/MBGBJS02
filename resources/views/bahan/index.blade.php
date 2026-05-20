@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="space-y-6">
        <div class="max-w-7xl mx-auto">
            
            <!-- ALERT SUCCESS -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            <!-- ALERT ERROR -->
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Manajemen Bahan</h1>
                    <p class="text-slate-500 text-sm mt-1">Kelola stok bahan baku dapur Anda secara efisien</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">

                    <!-- Tombol Tambah Bahan -->
                    <a href="{{ route('bahan.create') }}" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-sm transition-all shadow-sm">
                        <span class="material-symbols-outlined text-lg">add</span>
                        Tambah Bahan
                    </a>
                    
                    <!-- Tombol Export Excel -->
                    <a href="{{ route('bahan.export.excel') }}" 
                    class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg text-sm font-medium transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">table_chart</span>
                     Export Excel
                    </a>
                    
                    <!-- Tombol Export PDF -->
                    <a href="{{ route('bahan.export.pdf') }}" 
                    class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-medium transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                    Export PDF
                    </a>
                    
                </div>
            </div>

            <!-- STATISTIK CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Bahan</p>
                            <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalBahan ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-emerald-600 text-2xl">inventory_2</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Stok Menipis</p>
                            <p class="text-3xl font-bold text-orange-500 mt-1">{{ $stokMenipis ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-orange-500 text-2xl">warning</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Stok Habis</p>
                            <p class="text-3xl font-bold text-red-500 mt-1">{{ $stokHabis ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-red-500 text-2xl">error_outline</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FILTER & SEARCH -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6 overflow-hidden">
                <div class="p-4 border-b border-slate-100">
                    <form method="GET" action="{{ route('bahan.index') }}" class="flex flex-wrap items-center gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                       placeholder="Cari nama bahan...">
                            </div>
                        </div>
                        
                        <div>
                            <select name="kategori" class="px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="semua">Semua Kategori</option>
                                @foreach($kategoris ?? [] as $kat)
                                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                        {{ $kat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <select name="status" class="px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="semua">Semua Status</option>
                                <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Stok Aman</option>
                                <option value="menipis" {{ request('status') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
                                <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
                            Filter
                        </button>
                        
                        @if(request('search') || request('kategori') || request('status'))
                            <a href="{{ route('bahan.index') }}" class="text-slate-500 text-sm hover:text-emerald-600">
                                Reset Filter
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- TABEL DATA -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bahan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Satuan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Harga Beli</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bahans ?? [] as $bahan)
                                @php 
                                    $statusColor = 'green';
                                    $statusText = 'Stok Aman';
                                    if($bahan->stok <= 0) {
                                        $statusColor = 'red';
                                        $statusText = 'Stok Habis';
                                    } elseif($bahan->stok <= $bahan->min_stok ?? 5) {
                                        $statusColor = 'orange';
                                        $statusText = 'Stok Menipis';
                                    }
                                @endphp
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($bahan->gambar)
                                                <img src="{{ asset('storage/' . $bahan->gambar) }}" class="w-10 h-10 rounded-lg object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-slate-400 text-xl">inventory_2</span>
                                                </div>
                                            @endif
                                            <span class="font-semibold text-slate-800">{{ $bahan->nama }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            {{ $bahan->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        {{ number_format($bahan->stok, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $bahan->satuan }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        Rp {{ number_format($bahan->harga_beli, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold 
                                            @if($statusColor == 'green') bg-emerald-100 text-emerald-700
                                            @elseif($statusColor == 'orange') bg-orange-100 text-orange-700
                                            @else bg-red-100 text-red-700 @endif">
                                            <span class="w-1.5 h-1.5 rounded-full 
                                                @if($statusColor == 'green') bg-emerald-600
                                                @elseif($statusColor == 'orange') bg-orange-600
                                                @else bg-red-600 @endif">
                                            </span>
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('bahan.show', $bahan->id) }}" 
                                               class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                               title="Detail">
                                                <span class="material-symbols-outlined text-base">visibility</span>
                                            </a>
                                            
                                            <a href="{{ route('bahan.edit', $bahan->id) }}" 
                                               class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                                               title="Edit">
                                                <span class="material-symbols-outlined text-base">edit</span>
                                            </a>
                                            
                                            <button onclick="confirmDelete({{ $bahan->id }}, '{{ $bahan->nama }}', 'bahan')" 
                                                    class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                                    title="Hapus">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </button>
                                            
                                            <form id="delete-form-{{ $bahan->id }}" action="{{ route('bahan.destroy', $bahan->id) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                        <span class="material-symbols-outlined text-5xl mb-2 text-slate-300">inventory_2</span>
                                        <p>Belum ada data bahan. Silakan tambah bahan baru.</p>
                                        <a href="{{ route('bahan.create') }}" class="inline-block mt-3 text-emerald-600 hover:text-emerald-700">
                                            + Tambah Bahan Sekarang
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if(isset($bahans) && method_exists($bahans, 'links'))
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                    {{ $bahans->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
@endsection
@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
<!-- MAIN CONTENT - Detail Bahan -->
<main class="space-y-6">
    <div class="max-w-4xl mx-auto">
        
        <!-- HEADER -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('bahan.index') }}" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detail Bahan</h1>
                <p class="text-slate-500 text-sm mt-1">Informasi lengkap tentang bahan baku</p>
            </div>
        </div>

        @php $status = $bahan->status; @endphp

        <!-- MAIN CARD -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            
            <!-- Banner / Header Image -->
            <div class="h-40 bg-gradient-to-r from-emerald-600 to-emerald-800 relative">
                @if($bahan->gambar)
                    <img src="{{ asset('storage/' . $bahan->gambar) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-7xl opacity-30">inventory_2</span>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold {{ $status['badge'] }} shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full 
                            @if($status['color'] == 'green') bg-emerald-600
                            @elseif($status['color'] == 'orange') bg-orange-600
                            @else bg-red-600 @endif">
                        </span>
                        {{ $status['text'] }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Kiri -->
                    <div>
                        <div class="mb-5">
                            <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Bahan</label>
                            <p class="text-xl font-bold text-slate-800 mt-1">{{ $bahan->nama }}</p>
                        </div>
                        
                        <div class="mb-5">
                            <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kategori</label>
                            <p class="mt-1">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                    {{ $bahan->kategori }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="mb-5">
                            <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Keterangan</label>
                            <p class="text-slate-600 mt-1">{{ $bahan->keterangan ?: '— Tidak ada keterangan —' }}</p>
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div>
                        <div class="mb-5">
                            <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Stok Saat Ini</label>
                            <div class="flex items-baseline gap-2 mt-1">
                                <p class="text-3xl font-bold text-slate-800">{{ number_format($bahan->stok, 2) }}</p>
                                <p class="text-slate-500">{{ $bahan->satuan }}</p>
                            </div>
                            
                            <!-- Progress Bar -->
                            @php
                                $persen = $bahan->stok_minimal > 0 ? min(100, ($bahan->stok / $bahan->stok_minimal) * 100) : 0;
                                $barColor = $status['color'] == 'green' ? 'bg-emerald-500' : ($status['color'] == 'orange' ? 'bg-orange-500' : 'bg-red-500');
                            @endphp
                            <div class="mt-2 w-full bg-slate-100 rounded-full h-2">
                                <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $persen }}%"></div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Minimal stok: {{ number_format($bahan->stok_minimal, 2) }} {{ $bahan->satuan }}</p>
                        </div>
                        
                        <div class="mb-5">
                            <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Harga Beli</label>
                            <p class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($bahan->harga_beli, 0, ',', '.') }}</p>
                            <p class="text-xs text-slate-400">Per {{ $bahan->satuan }}</p>
                        </div>
                        
                        <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Estimasi Nilai Stok</label>
                            <p class="text-xl font-bold text-slate-800 mt-1">
                                Rp {{ number_format($bahan->stok * $bahan->harga_beli, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="flex gap-3 mt-8 pt-6 border-t border-slate-100">
                    <a href="{{ route('bahan.edit', $bahan->id) }}" 
                       class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-semibold text-center transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">edit</span>
                        Edit Bahan
                    </a>
                    <a href="{{ route('bahan.index') }}" 
                       class="flex-1 border border-slate-300 text-slate-700 py-3 rounded-xl font-semibold text-center hover:bg-slate-50 transition">
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
@endsection
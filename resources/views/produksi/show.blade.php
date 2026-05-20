@extends('layouts.app')

@section('content')
<main class="space-y-6">
    <div class="max-w-5xl mx-auto p-8">
        <input type="hidden" name="unique_token" value="{{ uniqid() }}">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('produksi.index') }}" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detail Produksi</h1>
                <p class="text-slate-500 text-sm mt-1">Informasi lengkap produksi menu</p>
            </div>
        </div>

        <!-- Alert -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Informasi Produksi -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-h2 text-h2 text-slate-900">Informasi Produksi</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-slate-400 uppercase">Menu</p>
                    <p class="text-lg font-bold text-slate-800">{{ $produksi->menu->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase">Jumlah Porsi</p>
                    <p class="text-lg font-bold text-slate-800">{{ number_format($produksi->jumlah_porsi) }} Porsi</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase">Tanggal Produksi</p>
                    <p class="text-lg font-bold text-slate-800">{{ $produksi->tanggal_produksi->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase">Status</p>
                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700">
                        {{ $produksi->status == 'selesai' ? 'Selesai' : ucfirst($produksi->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Detail Penggunaan Bahan -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-h2 text-h2 text-slate-900">Detail Penggunaan Bahan</h3>
            </div>
            <div class="overflow-x-auto p-6">
                <table class="w-full text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Bahan</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Jumlah Diambil</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Satuan</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Stok Sebelum</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Stok Sesudah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($produksi->detail as $detail)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $detail->bahan->nama }}</td>
                                <td class="px-4 py-3 text-center">{{ number_format($detail->jumlah, 2) }}</td>
                                <td class="px-4 py-3 text-center">{{ $detail->satuan }}</td>
                                <td class="px-4 py-3 text-center">{{ number_format($detail->stok_sebelum, 2) }}</td>
                                <td class="px-4 py-3 text-center">{{ number_format($detail->stok_sesudah, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FORM UPDATE SISA BAHAN (CUKUP INPUT TERPAKAI) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-h2 text-h2 text-slate-900">Update Sisa Bahan</h3>
                <p class="text-sm text-slate-500">Input berapa banyak bahan yang BENAR-BENAR TERPAKAI. Sisa akan otomatis masuk ke Stok Mengendap.</p>
                <div class="mt-2 p-3 bg-blue-50 rounded-lg text-xs text-blue-700">
                    💡 <span class="font-semibold">Cara mengisi:</span> Input angka pada kolom TERPAKAI (berapa yang benar-benar habis dipakai).<br>
                    ➕ Sisa bahan akan otomatis tersimpan di <strong>Stok Mengendap</strong> dan bisa dipakai untuk produksi berikutnya.
                </div>
            </div>
            
            <form action="{{ route('produksi.update-sisa', $produksi) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="overflow-x-auto">
                    <table class="w-full text-left border border-slate-200 rounded-lg">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-3 py-2 text-xs font-semibold text-slate-600 uppercase">Bahan</th>
                                <th class="px-3 py-2 text-xs font-semibold text-slate-600 uppercase text-center">Diambil</th>
                                <th class="px-3 py-2 text-xs font-semibold text-slate-600 uppercase text-center">Terpakai <span class="text-red-500">*</span></th>
                                <th class="px-3 py-2 text-xs font-semibold text-slate-600 uppercase text-center bg-emerald-50">Sisa ➕</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($produksi->detail as $index => $detail)
                                @php
                                    $stokMengendap = \App\Models\StokMengendap::where('produksi_id', $produksi->id)
                                        ->where('bahan_id', $detail->bahan_id)
                                        ->first();
                                    $sisa = $stokMengendap ? $stokMengendap->jumlah_sisa : 0;
                                @endphp
                                <tr>
                                    <td class="px-3 py-2 text-xs font-medium">
                                        {{ $detail->bahan->nama }}
                                        <input type="hidden" name="items[{{ $index }}][bahan_id]" value="{{ $detail->bahan_id }}">
                                    </td>
                                    <td class="px-3 py-2 text-center text-xs">
                                        {{ number_format($detail->jumlah, 2) }} {{ $detail->satuan }}
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <input type="number" 
                                               name="items[{{ $index }}][terpakai]" 
                                               id="terpakai_{{ $index }}"
                                               value="{{ $detail->jumlah - $sisa }}" 
                                               step="0.01" 
                                               min="0"
                                               max="{{ $detail->jumlah }}"
                                               class="w-28 px-2 py-1 text-xs border rounded-lg text-center"
                                               onchange="updateSisa({{ $index }}, {{ $detail->jumlah }})">
                                        <span class="text-xs text-slate-400">{{ $detail->satuan }}</span>
                                    </td>
                                    <td class="px-3 py-2 text-center bg-emerald-50">
                                        <span id="sisa_{{ $index }}" class="text-emerald-600 font-semibold text-sm">
                                            {{ number_format($sisa, 2) }}
                                        </span>
                                        <span class="text-xs text-slate-400"> {{ $detail->satuan }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan</label>
                    <textarea name="catatan" rows="2" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm"
                              placeholder="Contoh: Sisa karena porsi berkurang"></textarea>
                </div>
                
                <button type="submit" class="mt-6 w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-lg text-sm font-semibold transition">
                    Simpan Update Sisa Bahan
                </button>
            </form>
        </div>

        <!-- Stok Mengendap dari Produksi Ini -->
        <div class="mt-8 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-h2 text-h2 text-slate-900">Stok Mengendap dari Produksi Ini</h3>
                <p class="text-sm text-slate-500">Bahan sisa yang dapat digunakan untuk produksi berikutnya</p>
            </div>
            <div class="overflow-x-auto p-6">
                <table class="w-full text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Bahan</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Diambil</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Terpakai</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase text-center">Sisa</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $stokMengendapList = \App\Models\StokMengendap::where('produksi_id', $produksi->id)
                                ->with('bahan')
                                ->get();
                        @endphp
                        @forelse($stokMengendapList as $item)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $item->bahan->nama ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">{{ number_format($item->jumlah_diambil, 2) }} {{ $item->satuan }}</td>
                                <td class="px-4 py-3 text-center">{{ number_format($item->jumlah_terpakai, 2) }} {{ $item->satuan }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-emerald-600">
                                    {{ number_format($item->jumlah_sisa, 2) }} {{ $item->satuan }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold 
                                        {{ $item->status == 'menunggu' ? 'bg-yellow-100 text-yellow-700' : 
                                           ($item->status == 'habis' ? 'bg-slate-100 text-slate-500' : 'bg-emerald-100 text-emerald-700') }}">
                                        {{ $item->status == 'menunggu' ? 'Menunggu' : ($item->status == 'habis' ? 'Habis' : 'Terpakai') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                    Belum ada data stok mengendap
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
    function updateSisa(index, diambil) {
        let terpakaiInput = document.getElementById(`terpakai_${index}`);
        let sisaSpan = document.getElementById(`sisa_${index}`);
        
        let terpakai = parseFloat(terpakaiInput.value) || 0;
        let sisa = Math.max(0, diambil - terpakai);
        
        sisaSpan.innerHTML = sisa.toFixed(2);
    }
</script>
@endsection
@extends('layouts.app')

@section('content')
<main class="space-y-6">
    <div class="max-w-2xl mx-auto p-8">
        
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('stok-opname.index') }}" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Stok Opname</h1>
                <p class="text-slate-500 text-sm mt-1">Cocokkan stok fisik dengan stok di sistem</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('stok-opname.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Pilih Bahan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Bahan</label>
                    <select name="bahan_id" id="bahan_id" required 
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Pilih bahan...</option>
                        @foreach($bahans as $bahan)
                            <option value="{{ $bahan->id }}" data-satuan="{{ $bahan->satuan }}" data-stok="{{ $bahan->stok }}">
                                {{ $bahan->nama }} (Stok sistem: {{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stok Sistem (Readonly) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Stok di Sistem</label>
                    <div class="relative">
                        <input type="text" id="stok_sistem" readonly
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-600">
                        <span id="satuan_sistem" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></span>
                    </div>
                </div>

                <!-- Stok Fisik -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Stok Fisik (Hasil Penghitungan)</label>
                    <div class="relative">
                        <input type="text" name="stok_fisik" id="stok_fisik" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="0"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <span id="satuan_fisik" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></span>
                    </div>
                </div>

                <!-- Selisih (Otomatis) -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Selisih</label>
                    <div id="selisihDisplay" class="px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-lg font-semibold">
                        Belum dihitung
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Selisih = Stok Fisik - Stok Sistem (positif = surplus, negatif = minus)</p>
                </div>

                <!-- Tanggal Opname -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Opname</label>
                    <input type="date" name="tanggal_opname" required value="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan</label>
                    <select name="keterangan" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Pilih alasan selisih...</option>
                        <option value="Susut/Penyusutan">Susut/Penyusutan (bahan menguap, menyusut)</option>
                        <option value="Tercecer/Tumpah">Tercecer/Tumpah saat produksi</option>
                        <option value="Rusak/Kadaluarsa">Rusak/Kadaluarsa</option>
                        <option value="Kesalahan Input">Kesalahan Input Sebelumnya</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Detail Keterangan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Detail Keterangan</label>
                    <textarea name="keterangan_detail" rows="2" 
                              class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                              placeholder="Jelaskan lebih detail jika perlu..."></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-semibold transition">
                        Simpan Opname
                    </button>
                    <a href="{{ route('stok-opname.index') }}" class="flex-1 border border-slate-300 text-slate-700 py-3 rounded-xl text-center hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    const bahanSelect = document.getElementById('bahan_id');
    const stokSistemInput = document.getElementById('stok_sistem');
    const satuanSistem = document.getElementById('satuan_sistem');
    const stokFisikInput = document.getElementById('stok_fisik');
    const satuanFisik = document.getElementById('satuan_fisik');
    const selisihDisplay = document.getElementById('selisihDisplay');

    // Hitung selisih
    function hitungSelisih() {
        const stokSistem = parseFloat(stokSistemInput.value) || 0;
        const stokFisik = parseFloat(stokFisikInput.value) || 0;
        const selisih = stokFisik - stokSistem;
        const satuan = satuanSistem.textContent || 'unit';
        
        if (selisih > 0) {
            selisihDisplay.innerHTML = `<span class="text-emerald-600">+${selisih.toLocaleString('id-ID')} ${satuan} (Surplus/Stok Bertambah)</span>`;
        } else if (selisih < 0) {
            selisihDisplay.innerHTML = `<span class="text-red-600">${selisih.toLocaleString('id-ID')} ${satuan} (Minus/Stok Berkurang)</span>`;
        } else {
            selisihDisplay.innerHTML = `<span class="text-slate-600">0 ${satuan} (Sesuai)</span>`;
        }
    }

    // Saat bahan dipilih
    bahanSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stok = selectedOption.getAttribute('data-stok');
        const satuan = selectedOption.getAttribute('data-satuan');
        
        if (stok) {
            stokSistemInput.value = parseFloat(stok).toLocaleString('id-ID');
            satuanSistem.textContent = satuan;
            satuanFisik.textContent = satuan;
            hitungSelisih();
        } else {
            stokSistemInput.value = '';
            satuanSistem.textContent = '';
            satuanFisik.textContent = '';
            selisihDisplay.innerHTML = 'Belum dihitung';
        }
    });

    // Saat stok fisik diinput
    stokFisikInput.addEventListener('input', function() {
        hitungSelisih();
    });
</script>
@endsection
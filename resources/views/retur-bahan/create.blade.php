@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-8 fade-in-up">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('retur-bahan.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Tambah Retur Bahan</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Catat bahan yang rusak, kadaluarsa, atau tercecer</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
        <form action="{{ route('retur-bahan.store') }}" method="POST">
            @csrf
            
            <div class="space-y-5">
                <!-- Pilih Bahan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Pilih Bahan <span class="text-red-500">*</span></label>
                    <select name="bahan_id" id="bahan_id" required 
                            class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                        <option value="">-- Pilih Bahan --</option>
                        @foreach($bahans as $bahan)
                            <option value="{{ $bahan->id }}" data-satuan="{{ $bahan->satuan }}" data-stok="{{ $bahan->stok }}">
                                {{ $bahan->nama }} (Stok: {{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }})
                            </option>
                        @endforeach
                    </select>
                    @error('bahan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Jumlah <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="jumlah" id="jumlah" required
                               class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200"
                               placeholder="0"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <span id="satuanText" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">UNIT</span>
                    </div>
                    <p id="stokWarning" class="text-xs text-red-500 hidden mt-1"></p>
                    @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Jenis Retur -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Jenis Retur <span class="text-red-500">*</span></label>
                    <select name="jenis" required class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="rusak">❌ Rusak</option>
                        <option value="kadaluarsa">⚠️ Kadaluarsa</option>
                        <option value="tercecer">📦 Tercecer</option>
                        <option value="lainnya">📝 Lainnya</option>
                    </select>
                    @error('jenis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Tanggal Retur -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Retur <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_retur" value="{{ date('Y-m-d') }}" required
                           class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                    @error('tanggal_retur') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="3" 
                              class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200"
                              placeholder="Contoh: Bahan rusak saat pengiriman, kadaluarsa bulan lalu, dll"></textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-8 pt-4 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="flex-1 bg-gradient-primary text-white py-2 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
                    Simpan Retur
                </button>
                <a href="{{ route('retur-bahan.index') }}" class="flex-1 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 py-2 rounded-lg text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const bahanSelect = document.getElementById('bahan_id');
    const satuanText = document.getElementById('satuanText');
    const jumlahInput = document.getElementById('jumlah');
    const stokWarning = document.getElementById('stokWarning');

    bahanSelect.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        const satuan = opt.getAttribute('data-satuan') || 'UNIT';
        const stok = parseFloat(opt.getAttribute('data-stok')) || 0;
        satuanText.innerText = satuan.toUpperCase();
        
        jumlahInput.addEventListener('input', function() {
            const jml = parseFloat(this.value) || 0;
            if (jml > stok) {
                stokWarning.innerText = `Stok tidak mencukupi! Tersedia: ${stok} ${satuan}`;
                stokWarning.classList.remove('hidden');
            } else {
                stokWarning.classList.add('hidden');
            }
        });
    });
</script>
@endsection
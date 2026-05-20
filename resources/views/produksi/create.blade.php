@extends('layouts.app')

@section('content')
<main class="space-y-6">
    <div class="max-w-4xl mx-auto p-8">
        
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('produksi.index') }}" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Produksi Baru</h1>
                <p class="text-slate-500 text-sm mt-1">Pilih menu dan jumlah porsi yang akan diproduksi</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form id="produksiForm" method="POST" action="{{ route('produksi.store') }}">
                @csrf
                
                <div class="space-y-6">
                    <!-- Pilih Menu -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Menu</label>
                        <select name="menu_id" id="menu_id" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="">-- Pilih Menu --</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->nama }} (Rp {{ number_format($menu->harga_jual, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jumlah Porsi -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Porsi</label>
                        <input type="number" name="jumlah_porsi" id="jumlah_porsi" required min="1" step="1"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Masukkan jumlah porsi">
                    </div>

                    <!-- Tanggal Produksi -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Produksi</label>
                        <input type="date" name="tanggal_produksi" required value="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" 
                                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                  placeholder="Contoh: Produksi untuk acara khusus..."></textarea>
                    </div>

                    <!-- Tombol Cek Kebutuhan -->
                    <button type="button" id="btnCek" class="w-full bg-slate-600 hover:bg-slate-700 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">checklist</span>
                        Cek Kebutuhan Bahan
                    </button>

                    <!-- Hasil Cek Kebutuhan -->
                    <div id="hasilKebutuhan" class="hidden mt-4 p-4 rounded-xl border"></div>

                    <!-- Submit Button -->
                    <button type="submit" id="btnSubmit" disabled class="w-full bg-emerald-600 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2 opacity-50 cursor-not-allowed">
                        <span class="material-symbols-outlined">production_quantity_limits</span>
                        Proses Produksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    const menuSelect = document.getElementById('menu_id');
    const jumlahPorsi = document.getElementById('jumlah_porsi');
    const btnCek = document.getElementById('btnCek');
    const hasilDiv = document.getElementById('hasilKebutuhan');
    const btnSubmit = document.getElementById('btnSubmit');

    btnCek.addEventListener('click', async () => {
        const menuId = menuSelect.value;
        const porsi = jumlahPorsi.value;

        if (!menuId) {
            alert('Pilih menu terlebih dahulu!');
            return;
        }
        if (!porsi || porsi < 1) {
            alert('Masukkan jumlah porsi yang valid!');
            return;
        }

        btnCek.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> Mengecek...';
        btnCek.disabled = true;

        try {
            const response = await fetch(`{{ route('produksi.cek') }}?menu_id=${menuId}&jumlah_porsi=${porsi}`);
            const data = await response.json();

            if (data.error) {
                alert(data.error);
                hasilDiv.classList.add('hidden');
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            // Tampilkan hasil
            let html = `
                <h4 class="font-bold text-slate-800 mb-3">Kebutuhan Bahan untuk ${data.menu} (${data.jumlah_porsi} porsi)</h4>
                <div class="space-y-2">
            `;
            
            let semuaCukup = true;
            for (const item of data.kebutuhan) {
                const statusColor = item.status === 'cukup' ? 'text-emerald-600' : 'text-red-600';
                const statusIcon = item.status === 'cukup' ? '✅' : '❌';
                if (item.status === 'kurang') semuaCukup = false;
                
                html += `
                    <div class="flex justify-between items-center p-2 border-b border-slate-100">
                        <div>
                            <span class="font-medium">${item.bahan_nama}</span>
                            <span class="text-xs text-slate-400 ml-2">${item.per_porsi} ${item.satuan}/porsi</span>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold">${item.dibutuhkan.toLocaleString()} ${item.satuan}</div>
                            <div class="text-xs ${statusColor}">Stok: ${item.stok_tersedia.toLocaleString()} ${item.satuan} ${statusIcon}</div>
                        </div>
                    </div>
                `;
            }
            
            html += `</div>`;
            html += `<div class="mt-4 pt-3 border-t ${semuaCukup ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'} p-3 rounded-lg font-semibold text-center">
                ${semuaCukup ? '✅ Semua bahan tersedia! Silakan lanjutkan produksi.' : '❌ Ada bahan yang tidak mencukupi! Silakan tambah stok terlebih dahulu.'}
            </div>`;
            
            hasilDiv.innerHTML = html;
            hasilDiv.classList.remove('hidden');
            
            if (semuaCukup) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            }
            
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal mengecek kebutuhan bahan');
        } finally {
            btnCek.innerHTML = '<span class="material-symbols-outlined">checklist</span> Cek Kebutuhan Bahan';
            btnCek.disabled = false;
        }
    });
</script>
@endsection
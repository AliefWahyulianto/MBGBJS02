@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-8">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('menu.index') }}" class="p-2 hover:bg-slate-100 rounded-lg">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Menu</h1>
            <p class="text-slate-500 text-sm">Ubah informasi menu dan komposisi bahan</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Informasi Menu -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
        <h3 class="font-h2 text-h2 text-slate-900 mb-4">Informasi Menu</h3>
        
        <form action="{{ route('menu.update', $menu) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $menu->nama) }}" required
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                        <option value="Makanan" {{ $menu->kategori == 'Makanan' ? 'selected' : '' }}>🍚 Makanan</option>
                        <option value="Snack" {{ $menu->kategori == 'Snack' ? 'selected' : '' }}>🍪 Snack</option>
                        <option value="Minuman" {{ $menu->kategori == 'Minuman' ? 'selected' : '' }}>🥤 Minuman</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Harga Jual (Rp)</label>
                    <input type="number" name="harga_jual" value="{{ old('harga_jual', $menu->harga_jual) }}"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Gambar</label>
                    @if($menu->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $menu->gambar) }}" class="w-20 h-20 rounded-lg object-cover">
                            <p class="text-xs text-slate-400 mt-1">Gambar saat ini</p>
                        </div>
                    @endif
                    <input type="file" name="gambar" accept="image/*"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                    <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" 
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-4 border-t">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg font-semibold">
                    Update Informasi Menu
                </button>
                <a href="{{ route('menu.index') }}" class="flex-1 border border-slate-300 text-slate-700 py-2 rounded-lg text-center hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Komposisi Bahan -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-h2 text-h2 text-slate-900">Komposisi Bahan</h3>
            <p class="text-sm text-slate-500">Atur bahan baku dan takaran untuk menu ini (per porsi)</p>
        </div>
        
        <div class="p-6">
            <div id="komposisiContainer">
                <div class="text-center py-8 text-slate-400">
                    <span class="material-symbols-outlined text-4xl animate-spin">progress_activity</span>
                    <p class="text-sm mt-2">Memuat komposisi bahan...</p>
                </div>
            </div>
            
            <button type="button" id="btnTambahBahan" class="mt-4 px-4 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">add</span>
                Tambah Bahan
            </button>
        </div>
    </div>
</div>

<script>
    const bahans = @json($bahans);
    const menuId = {{ $menu->id }};

    // Load komposisi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        loadKomposisi();
    });

    function loadKomposisi() {
        fetch(`/menu/${menuId}/resep`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    document.getElementById('komposisiContainer').innerHTML = `
                        <div class="text-center py-8 text-slate-400">
                            <span class="material-symbols-outlined text-4xl">inventory</span>
                            <p class="text-sm mt-2">Belum ada komposisi bahan. Klik "Tambah Bahan" untuk menambahkan.</p>
                        </div>
                    `;
                } else {
                    renderKomposisi(data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('komposisiContainer').innerHTML = `
                    <div class="text-center py-8 text-red-400">
                        <span class="material-symbols-outlined text-4xl">error</span>
                        <p class="text-sm mt-2">Gagal memuat komposisi</p>
                    </div>
                `;
            });
    }

    function renderKomposisi(resep) {
        let html = `
            <div class="space-y-3">
                <p class="text-xs font-semibold text-slate-400 uppercase">DAFTAR BAHAN</p>
                <div id="selectedIngredients" class="flex flex-col gap-2">
        `;
        
        resep.forEach(item => {
            html += `
                <div class="flex items-center justify-between bg-emerald-50/50 p-3 rounded-lg border border-emerald-100" data-bahan-id="${item.bahan_id}">
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-800">${item.bahan.nama}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <input type="number" class="jumlah-input w-28 text-sm px-2 py-1 border border-slate-200 rounded" value="${item.jumlah}" step="0.01" placeholder="Jumlah">
                            <span class="text-xs text-slate-500">${item.satuan}</span>
                        </div>
                    </div>
                    <button onclick="hapusBahan(${item.bahan_id})" class="text-slate-400 hover:text-red-500 transition-colors ml-2">
                        <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                </div>
            `;
        });
        
        html += `
                </div>
            </div>
            <button onclick="simpanKomposisi()" class="mt-4 w-full bg-slate-900 hover:bg-black text-white font-bold py-2 rounded-lg transition-colors">
                Simpan Komposisi
            </button>
        `;
        
        document.getElementById('komposisiContainer').innerHTML = html;
    }

    function hapusBahan(bahanId) {
        const element = document.querySelector(`#selectedIngredients [data-bahan-id="${bahanId}"]`);
        if (element) element.remove();
    }

    function simpanKomposisi() {
        const ingredients = [];
        const items = document.querySelectorAll('#selectedIngredients > div');
        
        items.forEach(item => {
            const bahanId = item.getAttribute('data-bahan-id');
            const jumlahInput = item.querySelector('.jumlah-input');
            const satuanSpan = item.querySelector('.text-xs.text-slate-500');
            
            if (jumlahInput && jumlahInput.value && parseFloat(jumlahInput.value) > 0) {
                ingredients.push({
                    bahan_id: bahanId,
                    jumlah: parseFloat(jumlahInput.value),
                    satuan: satuanSpan ? satuanSpan.innerText : 'unit'
                });
            }
        });
        
        if (ingredients.length === 0) {
            alert('Minimal satu bahan harus diisi!');
            return;
        }
        
        const btn = document.querySelector('#komposisiContainer button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">progress_activity</span> Menyimpan...';
        btn.disabled = true;
        
        fetch(`/menu/${menuId}/komposisi`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ komposisi: ingredients })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('✅ ' + result.message);
                loadKomposisi();
            } else {
                alert('❌ Gagal menyimpan: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan!');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    // Tombol tambah bahan
    document.getElementById('btnTambahBahan').addEventListener('click', function() {
        let options = '<option value="">-- Pilih Bahan --</option>';
        bahans.forEach(b => {
            options += `<option value="${b.id}" data-satuan="${b.satuan}">${b.nama} (${b.satuan})</option>`;
        });
        
        const newId = Date.now();
        const container = document.getElementById('komposisiContainer');
        
        // Pastikan container memiliki selectedIngredients
        let selectedDiv = document.getElementById('selectedIngredients');
        if (!selectedDiv) {
            container.innerHTML = `
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase">DAFTAR BAHAN</p>
                    <div id="selectedIngredients" class="flex flex-col gap-2"></div>
                </div>
                <button onclick="simpanKomposisi()" class="mt-4 w-full bg-slate-900 hover:bg-black text-white font-bold py-2 rounded-lg transition-colors">
                    Simpan Komposisi
                </button>
            `;
            selectedDiv = document.getElementById('selectedIngredients');
        }
        
        selectedDiv.insertAdjacentHTML('beforeend', `
            <div class="flex items-center justify-between bg-slate-50 p-3 rounded-lg border border-slate-200" id="new-bahan-${newId}">
                <div class="flex-1">
                    <select class="bahan-select w-full px-3 py-1.5 text-sm border border-slate-200 rounded-lg" data-id="${newId}">
                        ${options}
                    </select>
                    <div class="flex items-center gap-2 mt-2">
                        <input type="number" class="jumlah-input w-28 text-sm px-2 py-1 border border-slate-200 rounded" placeholder="Jumlah" step="0.01">
                        <span class="satuan-text text-xs text-slate-500">Unit</span>
                    </div>
                </div>
                <button onclick="hapusBahanBaru(${newId})" class="text-slate-400 hover:text-red-500 transition-colors ml-2">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
        `);
        
        const select = document.querySelector(`#new-bahan-${newId} .bahan-select`);
        const satuanSpan = document.querySelector(`#new-bahan-${newId} .satuan-text`);
        
        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const satuan = selectedOption.getAttribute('data-satuan');
            satuanSpan.innerText = satuan || 'unit';
            document.getElementById(`new-bahan-${newId}`).setAttribute('data-bahan-id', this.value);
        });
    });

    function hapusBahanBaru(id) {
        const element = document.getElementById(`new-bahan-${id}`);
        if (element) element.remove();
        
        // Cek apakah masih ada bahan
        const remainingItems = document.querySelectorAll('#selectedIngredients > div').length;
        if (remainingItems === 0) {
            document.getElementById('komposisiContainer').innerHTML = `
                <div class="text-center py-8 text-slate-400">
                    <span class="material-symbols-outlined text-4xl">inventory</span>
                    <p class="text-sm mt-2">Belum ada komposisi bahan. Klik "Tambah Bahan" untuk menambahkan.</p>
                </div>
            `;
        }
    }
</script>
@endsection
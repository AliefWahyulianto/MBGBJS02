@extends('layouts.app')

@section('content')
<main class="space-y-6 fade-in-up">
    <div class="max-w-7xl mx-auto">
        
        <!-- Alert -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="font-h1 text-h1 text-slate-900">Manajemen Menu Makanan</h2>
                <p class="font-body-sm text-body-sm text-slate-500 mt-1">Kelola daftar hidangan dan resep bahan baku operasional MBG.</p>
            </div>
            <div class="flex gap-3">
                <!-- Filter Kategori -->
                <select id="kategoriFilter" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    <option value="semua">Semua Kategori</option>
                    <option value="Makanan">🍚 Makanan</option>
                    <option value="Snack">🍪 Snack</option>
                    <option value="Minuman">🥤 Minuman</option>
                </select>
                <!-- Filter Status -->
                <select id="statusFilter" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    <option value="semua">Semua Status</option>
                    <option value="tersedia">✅ Tersedia</option>
                    <option value="terbatas">⚠️ Terbatas</option>
                    <option value="habis">❌ Habis</option>
                </select>
                <a href="{{ route('menu.create') }}" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-emerald-700 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Tambah Menu Baru
                </a>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-slate-500 text-xs font-semibold uppercase">Total Menu</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalMenu }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-slate-500 text-xs font-semibold uppercase">Menu Tersedia</p>
                <p class="text-2xl font-bold text-emerald-600">{{ $menuTersedia }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-slate-500 text-xs font-semibold uppercase">Menu Habis</p>
                <p class="text-2xl font-bold text-red-500">{{ $menuHabis }}</p>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-12 gap-8">
            
            <!-- Menu Cards Grid -->
            <div class="col-span-12 lg:col-span-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="menuCardsContainer">
                    @forelse($menus as $menu)
                        @php
                            $statusKetersediaan = $menu->statusKetersediaan;
                            $statusBadge = [
                                'tersedia' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'terbatas' => 'bg-orange-50 text-orange-600 border-orange-100',
                                'habis' => 'bg-red-50 text-red-600 border-red-100'
                            ];
                            $statusText = [
                                'tersedia' => 'Tersedia',
                                'terbatas' => 'Stok Terbatas',
                                'habis' => 'Habis'
                            ];
                        @endphp
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow group" data-menu-id="{{ $menu->id }}">
                            <div class="h-48 overflow-hidden relative">
                                @if($menu->gambar)
                                    <img src="{{ asset('storage/' . $menu->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $menu->nama }}">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-5xl text-slate-300">restaurant_menu</span>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3">
                                    <span class="text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider border {{ $statusBadge[$statusKetersediaan] }}">
                                        {{ $statusText[$statusKetersediaan] }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-h2 text-h2 text-slate-900">{{ $menu->nama }}</h3>
                                        <p class="text-emerald-600 font-bold mt-1">Rp {{ number_format($menu->harga_jual, 0, ',', '.') }}</p>
                                        @if($menu->kelompok)
                                            <p class="text-xs text-slate-400 mt-1">{{ $menu->kelompok }}</p>
                                        @endif
                                    </div>
                                    <div class="flex gap-1">
                                        <a href="{{ route('menu.edit', $menu) }}" class="p-2 text-slate-400 hover:text-emerald-500 transition-colors">
                                            <span class="material-symbols-outlined text-base">edit</span>
                                        </a>
                                        <button onclick="confirmDelete({{ $menu->id }}, '{{ $menu->nama }}')" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                        <form id="delete-form-{{ $menu->id }}" action="{{ route('menu.destroy', $menu) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <p class="font-label-caps text-label-caps text-slate-400 uppercase">Bahan Baku Utama</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse($menu->resep->take(3) as $item)
                                            <span class="bg-slate-50 text-slate-600 text-xs px-2.5 py-1 rounded-full border border-slate-100">
                                                {{ $item->bahan->nama }} ({{ $item->jumlah }} {{ $item->satuan }})
                                            </span>
                                        @empty
                                            <span class="bg-slate-50 text-slate-400 text-xs px-2.5 py-1 rounded-full">Belum ada resep</span>
                                        @endforelse
                                        @if($menu->resep->count() > 3)
                                            <span class="bg-slate-50 text-slate-600 text-xs px-2.5 py-1 rounded-full border border-slate-100">
                                                +{{ $menu->resep->count() - 3 }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12">
                            <span class="material-symbols-outlined text-5xl text-slate-300">restaurant_menu</span>
                            <p class="text-slate-500 mt-2">Belum ada menu. Silakan tambah menu baru.</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $menus->links() }}
                </div>
            </div>

            <!-- Komposisi Bahan Form -->
            <div class="col-span-12 lg:col-span-4">
                <div class="bg-white rounded-xl border border-slate-200 p-6 sticky top-24 shadow-sm">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="material-symbols-outlined text-emerald-600">list_alt</span>
                        <h3 class="font-h2 text-h2 text-slate-900">Komposisi Bahan</h3>
                    </div>
                    
                    <form id="komposisiForm" class="space-y-6">
                        @csrf
                        <!-- Select Menu -->
                        <div class="space-y-2">
                            <label class="font-label-caps text-label-caps text-slate-500 uppercase">Pilih Menu</label>
                            <select id="selectMenu" name="menu_id" class="w-full bg-slate-50 border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500 py-2.5 px-3">
                                <option value="">-- Pilih Menu --</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="komposisiContainer">
                            <!-- Komposisi akan dimuat via AJAX -->
                            <div class="text-center py-8 text-slate-400">
                                <span class="material-symbols-outlined text-4xl">info</span>
                                <p class="text-sm mt-2">Pilih menu untuk melihat dan mengedit komposisi bahan</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    // Konfirmasi hapus
    function confirmDelete(id, nama) {
        if (confirm('Apakah Anda yakin ingin menghapus menu "' + nama + '"?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    // Load komposisi saat menu dipilih
    const selectMenu = document.getElementById('selectMenu');
    const komposisiContainer = document.getElementById('komposisiContainer');

    selectMenu.addEventListener('change', async function() {
        const menuId = this.value;
        if (!menuId) {
            komposisiContainer.innerHTML = `
                <div class="text-center py-8 text-slate-400">
                    <span class="material-symbols-outlined text-4xl">info</span>
                    <p class="text-sm mt-2">Pilih menu untuk melihat dan mengedit komposisi bahan</p>
                </div>
            `;
            return;
        }

        komposisiContainer.innerHTML = '<div class="text-center py-8"><span class="material-symbols-outlined text-4xl animate-spin">progress_activity</span><p class="text-sm mt-2">Memuat...</p></div>';

        try {
            const response = await fetch(`/menu/${menuId}/resep`);
            const data = await response.json();
            
            if (response.ok) {
                renderKomposisiForm(menuId, data);
            } else {
                throw new Error('Gagal memuat data');
            }
        } catch (error) {
            komposisiContainer.innerHTML = `
                <div class="text-center py-8 text-red-400">
                    <span class="material-symbols-outlined text-4xl">error</span>
                    <p class="text-sm mt-2">Gagal memuat komposisi</p>
                </div>
            `;
        }
    });

    function renderKomposisiForm(menuId, resep) {
        const bahanList = @json($bahans);
        
        let html = `
            <div class="space-y-3">
                <label class="font-label-caps text-label-caps text-slate-500 uppercase">Tambah Bahan Baku</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                    <select id="tambahBahanSelect" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-slate-200 rounded-lg text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">-- Cari bahan --</option>
                        ${bahanList.map(b => `<option value="${b.id}" data-satuan="${b.satuan}">${b.nama} (${b.satuan})</option>`).join('')}
                    </select>
                </div>
            </div>

            <div class="space-y-3 mt-4">
                <p class="text-xs font-semibold text-slate-400">BAHAN TERPILIH</p>
                <div id="selectedIngredients" class="flex flex-col gap-2">
                    ${resep.map(item => `
                        <div class="flex items-center justify-between bg-emerald-50/50 p-3 rounded-lg border border-emerald-100" data-bahan-id="${item.bahan_id}">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-slate-800">${item.bahan.nama}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <input type="number" name="jumlah_${item.bahan_id}" value="${item.jumlah}" step="0.01" class="w-24 text-sm px-2 py-1 border border-slate-200 rounded" placeholder="Jumlah">
                                    <span class="text-xs text-slate-500">${item.satuan}</span>
                                </div>
                            </div>
                            <button onclick="hapusBahan(${item.bahan_id})" class="text-slate-400 hover:text-error transition-colors ml-2">
                                <span class="material-symbols-outlined text-lg">close</span>
                            </button>
                        </div>
                    `).join('')}
                </div>
            </div>

            <button type="button" onclick="simpanKomposisi(${menuId})" class="w-full bg-slate-900 text-white font-bold py-3 rounded-lg hover:bg-black transition-colors shadow-sm flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-lg">save</span>
                Simpan Komposisi
            </button>
        `;
        
        komposisiContainer.innerHTML = html;
        
        // Event untuk tambah bahan
        const tambahSelect = document.getElementById('tambahBahanSelect');
        tambahSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const bahanId = this.value;
            const bahanNama = option.text.split(' (')[0];
            const satuan = option.getAttribute('data-satuan');
            
            if (!bahanId) return;
            
            // Cek apakah sudah ada
            if (document.querySelector(`#selectedIngredients [data-bahan-id="${bahanId}"]`)) {
                alert('Bahan sudah ada dalam komposisi!');
                this.value = '';
                return;
            }
            
            const container = document.getElementById('selectedIngredients');
            container.insertAdjacentHTML('beforeend', `
                <div class="flex items-center justify-between bg-slate-50 p-3 rounded-lg border border-slate-100" data-bahan-id="${bahanId}">
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-800">${bahanNama}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <input type="number" name="jumlah_${bahanId}" value="1" step="0.01" class="w-24 text-sm px-2 py-1 border border-slate-200 rounded" placeholder="Jumlah">
                            <span class="text-xs text-slate-500">${satuan}</span>
                        </div>
                    </div>
                    <button onclick="hapusBahan(${bahanId})" class="text-slate-400 hover:text-error transition-colors ml-2">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>
            `);
            this.value = '';
        });
    }

    function hapusBahan(bahanId) {
        const element = document.querySelector(`#selectedIngredients [data-bahan-id="${bahanId}"]`);
        if (element) element.remove();
    }

    async function simpanKomposisi(menuId) {
        const ingredients = [];
        const items = document.querySelectorAll('#selectedIngredients > div');
        
        items.forEach(item => {
            const bahanId = item.getAttribute('data-bahan-id');
            const jumlahInput = item.querySelector(`input[name^="jumlah_"]`);
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
        
        const response = await fetch(`/menu/${menuId}/komposisi`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ komposisi: ingredients })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            location.reload();
        } else {
            alert('Gagal menyimpan: ' + result.message);
        }
    }

    // Filter menu
    const kategoriFilter = document.getElementById('kategoriFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    async function filterMenu() {
        const kategori = kategoriFilter.value;
        const status = statusFilter.value;
        
        const response = await fetch(`/menu/filter?kategori=${kategori}&status=${status}`);
        const data = await response.json();
        
        const container = document.getElementById('menuCardsContainer');
        if (data.length === 0) {
            container.innerHTML = '<div class="col-span-2 text-center py-12"><span class="material-symbols-outlined text-5xl text-slate-300">restaurant_menu</span><p class="text-slate-500 mt-2">Tidak ada menu yang sesuai filter</p></div>';
            return;
        }
        
        container.innerHTML = '';
        data.forEach(menu => {
            const statusBadgeClass = {
                'tersedia': 'bg-emerald-50 text-emerald-600 border-emerald-100',
                'terbatas': 'bg-orange-50 text-orange-600 border-orange-100',
                'habis': 'bg-red-50 text-red-600 border-red-100'
            };
            const statusText = {
                'tersedia': 'Tersedia',
                'terbatas': 'Stok Terbatas',
                'habis': 'Habis'
            };
            const realStatus = menu.status_realtime || menu.status;
            
            container.innerHTML += `
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow group">
                    <div class="h-48 overflow-hidden relative">
                        ${menu.gambar ? `<img src="/storage/${menu.gambar}" class="w-full h-full object-cover">` : `<div class="w-full h-full bg-slate-100 flex items-center justify-center"><span class="material-symbols-outlined text-5xl text-slate-300">restaurant_menu</span></div>`}
                        <div class="absolute top-3 right-3">
                            <span class="text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider border ${statusBadgeClass[realStatus]}">${statusText[realStatus]}</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-h2 text-h2 text-slate-900">${menu.nama}</h3>
                                <p class="text-emerald-600 font-bold mt-1">Rp ${new Intl.NumberFormat('id-ID').format(menu.harga_jual)}</p>
                            </div>
                            <div class="flex gap-1">
                                <a href="/menu/${menu.id}/edit" class="p-2 text-slate-400 hover:text-emerald-500"><span class="material-symbols-outlined text-base">edit</span></a>
                                <button onclick="confirmDelete(${menu.id}, '${menu.nama}')" class="p-2 text-slate-400 hover:text-red-500"><span class="material-symbols-outlined text-base">delete</span></button>
                                <form id="delete-form-${menu.id}" action="/menu/${menu.id}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="font-label-caps text-label-caps text-slate-400 uppercase">Bahan Baku Utama</p>
                            <div class="flex flex-wrap gap-1.5">
                                ${menu.resep && menu.resep.length > 0 ? menu.resep.slice(0,3).map(r => `<span class="bg-slate-50 text-slate-600 text-xs px-2.5 py-1 rounded-full border border-slate-100">${r.bahan.nama}</span>`).join('') : '<span class="bg-slate-50 text-slate-400 text-xs px-2.5 py-1 rounded-full">Belum ada resep</span>'}
                                ${menu.resep && menu.resep.length > 3 ? `<span class="bg-slate-50 text-slate-600 text-xs px-2.5 py-1 rounded-full border border-slate-100">+${menu.resep.length - 3} lainnya</span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
    }
    
    kategoriFilter.addEventListener('change', filterMenu);
    statusFilter.addEventListener('change', filterMenu);
</script>
@endsection
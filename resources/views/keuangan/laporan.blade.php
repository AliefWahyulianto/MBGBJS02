@extends('layouts.app')

@section('content')
<!-- MAIN CONTENT - Laporan Keuangan -->
<main class="space-y-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Laporan Keuangan</h1>
                <p class="text-slate-500 text-sm mt-1">Seluruh transaksi pemasukan dan pengeluaran dapur</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('keuangan.create') }}" 
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-semibold transition">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Tambah Transaksi
                </a>
                <a href="{{ route('keuangan.export') }}" 
                   class="inline-flex items-center gap-2 border border-slate-300 text-slate-700 px-5 py-2.5 rounded-xl font-semibold hover:bg-slate-50 transition">
                    <span class="material-symbols-outlined text-lg">download</span>
                    Export Excel
                </a>
            </div>
        </div>

        <!-- RINGKASAN KARTU -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                <p class="text-xs text-emerald-600 font-semibold uppercase">Total Pemasukan</p>
                <p class="text-xl font-bold text-emerald-700">Rp {{ number_format($ringkasan['total_masuk'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                <p class="text-xs text-red-600 font-semibold uppercase">Total Pengeluaran</p>
                <p class="text-xl font-bold text-red-700">Rp {{ number_format($ringkasan['total_keluar'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                <p class="text-xs text-blue-600 font-semibold uppercase">Saldo Akhir</p>
                <p class="text-xl font-bold text-blue-700">Rp {{ number_format($ringkasan['saldo'], 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- FILTER -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('keuangan.laporan') }}" class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[150px]">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari transaksi..." 
                           class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm">
                </div>
                <select name="jenis" class="px-4 py-2 border border-slate-200 rounded-lg text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
                <select name="kategori" class="px-4 py-2 border border-slate-200 rounded-lg text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="px-4 py-2 border border-slate-200 rounded-lg text-sm">
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="px-4 py-2 border border-slate-200 rounded-lg text-sm">
                <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm font-semibold">Filter</button>
                @if(request()->anyFilled(['search', 'jenis', 'kategori', 'tanggal_mulai', 'tanggal_selesai']))
                    <a href="{{ route('keuangan.laporan') }}" class="text-red-500 text-sm">Reset</a>
                @endif
            </form>
        </div>

        <!-- TABEL TRANSAKSI -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Kode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Sumber/Tujuan</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">Jumlah</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transaksis as $transaksi)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-sm font-mono text-slate-600">{{ $transaksi->kode_transaksi }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $transaksi->tanggal_transaksi->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $transaksi->jenis == 'masuk' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $transaksi->jenis == 'masuk' ? 'Pemasukan' : 'Pengeluaran' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $transaksi->kategori }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $transaksi->sumber_tujuan ?: '-' }}</td>
                                <td class="px-6 py-4 text-right font-semibold {{ $transaksi->jenis == 'masuk' ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $transaksi->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Tombol EDIT -->
                                        <a href="{{ route('keuangan.edit', $transaksi->id) }}" 
                                        class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                                        title="Edit">
                                            <span class="material-symbols-outlined text-base">edit</span>
                                        </a>
                                        
                                        <!-- Tombol DELETE -->
                                        <button onclick="confirmDelete({{ $transaksi->id }}, '{{ $transaksi->kode_transaksi }}')" 
                                                class="p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Hapus">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                        
                                        <form id="delete-form-{{ $transaksi->id }}" action="{{ route('keuangan.destroy', $transaksi->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    <span class="material-symbols-outlined text-5xl mb-2">receipt</span>
                                    <p>Belum ada data transaksi</p>
                                    <a href="{{ route('keuangan.create') }}" class="text-emerald-600 hover:underline">Tambah transaksi pertama</a>
                                 </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
</main>

<script>
    function confirmDelete(id, kode) {
        if (confirm('Apakah Anda yakin ingin menghapus transaksi "' + kode + '"?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
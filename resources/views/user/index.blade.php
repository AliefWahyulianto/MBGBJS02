@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 p-8 fade-in-up">

    <!-- HEADER -->
    <div class="flex flex-wrap justify-between items-center gap-4 card-stagger">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Manajemen User</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Kelola akun karyawan dapur</p>
        </div>
        <a href="{{ route('user.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-primary text-white rounded-xl font-semibold text-sm transition shadow-md hover:shadow-lg">
            <span class="material-symbols-outlined text-lg">person_add</span>
            Tambah User
        </a>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl card-stagger">
            <span class="material-symbols-outlined text-emerald-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center card-stagger">
            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $statistik['total'] }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Total User</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800 p-4 text-center card-stagger" style="animation-delay: 0.05s">
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $statistik['admin'] }}</p>
            <p class="text-xs text-purple-500 dark:text-purple-400">Admin</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-4 text-center card-stagger" style="animation-delay: 0.1s">
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $statistik['manager'] }}</p>
            <p class="text-xs text-blue-500 dark:text-blue-400">Manager</p>
        </div>
        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800 p-4 text-center card-stagger" style="animation-delay: 0.15s">
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $statistik['staff'] }}</p>
            <p class="text-xs text-emerald-500 dark:text-emerald-400">Staff</p>
        </div>
        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-xl border border-orange-200 dark:border-orange-800 p-4 text-center card-stagger" style="animation-delay: 0.2s">
            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $statistik['driver'] }}</p>
            <p class="text-xs text-orange-500 dark:text-orange-400">Driver</p>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800 p-4 text-center card-stagger" style="animation-delay: 0.25s">
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $statistik['aktif'] }}</p>
            <p class="text-xs text-green-500 dark:text-green-400">Aktif</p>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-4 card-stagger" style="animation-delay: 0.3s">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[180px]">
                <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}" 
                       class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
            </div>
            <select name="role" class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                <option value="semua">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="driver" {{ request('role') == 'driver' ? 'selected' : '' }}>Driver</option>
            </select>
            <select name="status" class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="px-5 py-2 bg-gradient-primary text-white rounded-lg text-sm font-semibold transition shadow-md hover:shadow-lg">
                <span class="material-symbols-outlined text-base align-middle mr-1">filter_list</span>
                Filter
            </button>
            @if(request('search') || request('role') || request('status'))
                <a href="{{ route('user.index') }}" class="text-red-500 dark:text-red-400 text-sm hover:underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- TABEL USER -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden card-stagger" style="animation-delay: 0.35s">
        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-600">
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">User</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Email</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Role</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Bergabung</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors table-row-stagger" 
                            style="animation-delay: {{ 0.02 * ($index + 1) }}s">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="material-symbols-outlined text-slate-400 dark:text-slate-500 text-2xl">person</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $user->phone ?: 'No telepon' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 rounded-full text-[10px] font-semibold {{ $user->roleBadge }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($user->is_active)
                                    <span class="inline-flex px-2 py-1 rounded-full text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300">Aktif</span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-full text-[10px] font-semibold bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('user.show', $user) }}" class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition" title="Detail">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('user.edit', $user) }}" class="p-1.5 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition" title="Edit">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    <form action="{{ route('user.toggle-status', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 {{ $user->is_active ? 'text-orange-500 hover:text-orange-700 hover:bg-orange-50 dark:hover:bg-orange-900/30' : 'text-green-500 hover:text-green-700 hover:bg-green-50 dark:hover:bg-green-900/30' }} rounded-lg transition" 
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <span class="material-symbols-outlined text-base">{{ $user->is_active ? 'block' : 'check_circle' }}</span>
                                        </button>
                                    </form>
                                    @if(auth()->id() != $user->id)
                                    <form action="{{ route('user.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition" title="Hapus">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-slate-400 dark:text-slate-500">
                                <span class="material-symbols-outlined text-5xl mb-2">people</span>
                                <p>Belum ada data user</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
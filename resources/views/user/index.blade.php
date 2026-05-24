@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-8 space-y-8">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen User</h1>
            <p class="text-slate-500 text-sm">Kelola akun karyawan dapur</p>
        </div>
        <a href="{{ route('user.create') }}" 
        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">person_add</span>
            Tambah User
        </a>
            </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl border p-4 text-center">
            <p class="text-2xl font-bold text-slate-800">{{ $statistik['total'] }}</p>
            <p class="text-xs text-slate-500">Total User</p>
        </div>
        <div class="bg-purple-50 rounded-xl border border-purple-200 p-4 text-center">
            <p class="text-2xl font-bold text-purple-600">{{ $statistik['admin'] }}</p>
            <p class="text-xs text-purple-500">Admin</p>
        </div>
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-4 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $statistik['manager'] }}</p>
            <p class="text-xs text-blue-500">Manager</p>
        </div>
        <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-4 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $statistik['staff'] }}</p>
            <p class="text-xs text-emerald-500">Staff</p>
        </div>
        <div class="bg-orange-50 rounded-xl border border-orange-200 p-4 text-center">
            <p class="text-2xl font-bold text-orange-600">{{ $statistik['driver'] }}</p>
            <p class="text-xs text-orange-500">Driver</p>
        </div>
        <div class="bg-green-50 rounded-xl border border-green-200 p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $statistik['aktif'] }}</p>
            <p class="text-xs text-green-500">Aktif</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border shadow-sm p-4">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}"
                       class="w-full px-4 py-2 border rounded-lg text-sm">
            </div>
            <select name="role" class="px-4 py-2 border rounded-lg text-sm">
                <option value="semua">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="driver" {{ request('role') == 'driver' ? 'selected' : '' }}>Driver</option>
            </select>
            <select name="status" class="px-4 py-2 border rounded-lg text-sm">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-sm">Filter</button>
            @if(request('search') || request('role') || request('status'))
                <a href="{{ route('user.index') }}" class="text-red-500 text-sm">Reset</a>
            @endif
        </form>
    </div>

    <!-- Tabel User -->
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase">User</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Email</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Role</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Bergabung</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center overflow-hidden">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-outlined text-slate-400">person</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $user->phone ?: 'No telepon' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $user->roleBadge }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->is_active)
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Aktif</span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('user.show', $user) }}" class="text-blue-500 hover:text-blue-700" title="Detail">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                </a>
                                <a href="{{ route('user.edit', $user) }}" class="text-emerald-500 hover:text-emerald-700" title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>
                                <form action="{{ route('user.toggle-status', $user) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="{{ $user->is_active ? 'text-orange-500 hover:text-orange-700' : 'text-green-500 hover:text-green-700' }}" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <span class="material-symbols-outlined text-base">{{ $user->is_active ? 'block' : 'check_circle' }}</span>
                                    </button>
                                </form>
                                @if(auth()->id() != $user->id)
                                <form action="{{ route('user.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                Belum ada data user
                            </td>
                        </tr>T
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
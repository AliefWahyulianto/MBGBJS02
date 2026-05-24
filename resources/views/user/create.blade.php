@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('user.index') }}" class="p-2 hover:bg-slate-100 rounded-lg">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah User</h1>
            <p class="text-slate-500 text-sm">Tambahkan akun karyawan baru</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm p-6">
        <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded-lg px-4 py-2">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-4 py-2">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="w-full border rounded-lg px-4 py-2">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required class="w-full border rounded-lg px-4 py-2">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-1">Role <span class="text-red-500">*</span></label>
                    <select name="role" class="w-full border rounded-lg px-4 py-2">
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>🧑‍🍳 Staff</option>
                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>📋 Manager</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>👑 Admin</option>
                        <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>🚚 Driver</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-1">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Alamat</label>
                    <textarea name="address" rows="2" class="w-full border rounded-lg px-4 py-2">{{ old('address') }}</textarea>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Avatar</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full border rounded-lg px-4 py-2">
                </div>
            </div>
            
            <div class="flex gap-3 mt-8 pt-4 border-t">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg font-semibold">Simpan User</button>
                <a href="{{ route('user.index') }}" class="flex-1 border text-center py-2 rounded-lg hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
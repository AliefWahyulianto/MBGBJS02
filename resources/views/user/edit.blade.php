@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('user.index') }}" class="p-2 hover:bg-slate-100 rounded-lg">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit User</h1>
            <p class="text-slate-500 text-sm">Ubah informasi akun karyawan</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm p-6">
        <form action="{{ route('user.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                           class="w-full border rounded-lg px-4 py-2">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                           class="w-full border rounded-lg px-4 py-2">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Role -->
                <div>
                    <label class="block text-sm font-semibold mb-1">Role <span class="text-red-500">*</span></label>
                    <select name="role" class="w-full border rounded-lg px-4 py-2">
                        <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>🧑‍🍳 Staff</option>
                        <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>📋 Manager</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>👑 Admin</option>
                        <option value="driver" {{ old('role', $user->role) == 'driver' ? 'selected' : '' }}>🚚 Driver</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Telepon -->
                <div>
                    <label class="block text-sm font-semibold mb-1">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                           class="w-full border rounded-lg px-4 py-2">
                </div>
                
                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Alamat</label>
                    <textarea name="address" rows="2" class="w-full border rounded-lg px-4 py-2">{{ old('address', $user->address) }}</textarea>
                </div>
                
                <!-- Avatar -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Avatar</label>
                    @if($user->avatar)
                        <div class="mb-3 flex items-center gap-3">
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-16 h-16 rounded-full object-cover">
                            <span class="text-xs text-slate-500">Avatar saat ini</span>
                        </div>
                    @endif
                    <input type="file" name="avatar" accept="image/*" class="w-full border rounded-lg px-4 py-2">
                    <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah avatar</p>
                </div>
            </div>
            
            <div class="flex gap-3 mt-8 pt-4 border-t">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg font-semibold">Update User</button>
                <a href="{{ route('user.index') }}" class="flex-1 border text-center py-2 rounded-lg hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>

    <!-- Reset Password Section -->
    <div class="mt-8 bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-h2 text-h2 text-slate-900">Reset Password</h3>
            <p class="text-sm text-slate-500">Reset password user ini</p>
        </div>
        <div class="p-6">
            <form action="{{ route('user.reset-password', $user) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Password Baru</label>
                        <input type="password" name="password" required class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required class="w-full border rounded-lg px-4 py-2">
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-semibold">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
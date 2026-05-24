@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('user.index') }}" class="p-2 hover:bg-slate-100 rounded-lg">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail User</h1>
            <p class="text-slate-500 text-sm">Informasi lengkap akun</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="p-6 border-b bg-slate-50/50 flex items-center gap-4">
            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center overflow-hidden">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                @else
                    <span class="material-symbols-outlined text-4xl text-slate-400">person</span>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $user->name }}</h2>
                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $user->roleBadge }}">
                    {{ ucfirst($user->role) }}
                </span>
                @if($user->is_active)
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 ml-2">Aktif</span>
                @else
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 ml-2">Nonaktif</span>
                @endif
            </div>
        </div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-slate-400 uppercase">Email</p>
                <p class="text-slate-800">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Telepon</p>
                <p class="text-slate-800">{{ $user->phone ?: '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-slate-400 uppercase">Alamat</p>
                <p class="text-slate-800">{{ $user->address ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Bergabung</p>
                <p class="text-slate-800">{{ $user->created_at->format('d F Y H:i:s') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Terakhir Update</p>
                <p class="text-slate-800">{{ $user->updated_at->format('d F Y H:i:s') }}</p>
            </div>
        </div>
        
        <div class="p-6 border-t bg-slate-50/50 flex gap-3">
            <a href="{{ route('user.edit', $user) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg">Edit User</a>
            <button onclick="showResetPassword({{ $user->id }})" class="px-4 py-2 bg-orange-500 text-white rounded-lg">Reset Password</button>
            @if(auth()->id() != $user->id)
            <form action="{{ route('user.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg">Hapus User</button>
            </form>
            @endif
        </div>
    </div>
</div>

<script>
function showResetPassword(userId) {
    let password = prompt('Masukkan password baru:');
    if (password && password.length >= 8) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = `/user/${userId}/reset-password`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="password" value="${password}">
            <input type="hidden" name="password_confirmation" value="${password}">
        `;
        document.body.appendChild(form);
        form.submit();
    } else if (password) {
        alert('Password minimal 8 karakter!');
    }
}
</script>
@endsection
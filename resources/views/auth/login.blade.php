<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KMS Admin</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <!-- KMS Admin Custom CSS -->
    <link href="{{ asset('css/kms-admin.css') }}" rel="stylesheet">
    
    <style>
        /* Light mode background */
        body {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8ecf1 100%);
            min-height: 100vh;
        }
        
        /* Dark mode background */
        .dark body {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f172a 100%);
        }
        
        /* Light mode card */
        .login-card {
            background-color: #ffffff;
            border-color: #f1f5f9;
        }
        
        /* Dark mode card */
        .dark .login-card {
            background-color: #1e293b;
            border-color: #334155;
        }
        
        /* Light mode text */
        .login-title {
            color: #1e293b;
        }
        
        .dark .login-title {
            color: #f1f5f9;
        }
        
        .login-subtitle {
            color: #64748b;
        }
        
        .dark .login-subtitle {
            color: #94a3b8;
        }
        
        /* Light mode labels */
        .login-label {
            color: #334155;
        }
        
        .dark .login-label {
            color: #cbd5e1;
        }
        
        /* Light mode input */
        .login-input {
            background-color: #ffffff;
            border-color: #e2e8f0;
            color: #1e293b;
        }
        
        .login-input:focus {
            border-color: #10b981;
            ring-color: #10b981;
        }
        
        /* Dark mode input */
        .dark .login-input {
            background-color: #0f172a;
            border-color: #334155;
            color: #f1f5f9;
        }
        
        .dark .login-input::placeholder {
            color: #64748b;
        }
        
        /* Light mode icon */
        .login-icon {
            color: #94a3b8;
        }
        
        .dark .login-icon {
            color: #64748b;
        }
        
        /* Light mode remember text */
        .remember-text {
            color: #475569;
        }
        
        .dark .remember-text {
            color: #94a3b8;
        }
        
        /* Demo credentials card */
        .demo-card {
            background-color: rgba(255, 255, 255, 0.5);
            border-color: #f1f5f9;
        }
        
        .dark .demo-card {
            background-color: rgba(30, 41, 59, 0.5);
            border-color: #334155;
        }
        
        .demo-text-light {
            color: #475569;
        }
        
        .dark .demo-text-light {
            color: #94a3b8;
        }
        
        .demo-value-light {
            color: #334155;
        }
        
        .dark .demo-value-light {
            color: #cbd5e1;
        }
        
        /* ============================================ */
        /* TOMBOL LOGIN - LIGHT MODE */
        /* ============================================ */
        .btn-login {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            font-weight: bold;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        /* Dark mode tombol login */
        .dark .btn-login {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .dark .btn-login:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }
                /* Light mode logo background */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        /* Dark mode logo tetap sama */
        .dark .bg-gradient-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md fade-in-up">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-primary rounded-2xl shadow-lg mb-4 logo-animate">
                <span class="material-symbols-outlined text-white text-3xl">restaurant_menu</span>
            </div>
            <h1 class="text-2xl font-bold login-title">Dapur MBG</h1>
            <p class="text-sm login-subtitle">Bojongsari 02</p>
        </div>

        <!-- Card Login -->
        <div class="login-card rounded-2xl shadow-xl border overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold login-title">Masuk ke Akun</h2>
                    <p class="text-sm login-subtitle mt-1">Silakan login untuk mengakses dashboard</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold login-label mb-2">Alamat Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 login-icon text-lg">mail</span>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-10 pr-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition login-input"
                                   placeholder="admin@mbg.com">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-semibold login-label mb-2">Password</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 login-icon text-lg">lock</span>
                            <input type="password" name="password" required
                                   class="w-full pl-10 pr-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition login-input"
                                   placeholder="********">
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                            <span class="text-sm remember-text">Ingat saya</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-emerald-600 hover:text-emerald-700 hover:underline">
                            Lupa password?
                        </a>
                    </div>

                    <!-- Tombol Login -->
                    <button type="submit" class="btn-login">
                        <span class="material-symbols-outlined text-base">login</span>
                        Masuk
                    </button>

                    <!-- Link ke Register -->
                    <div class="text-center pt-4">
                        <p class="text-sm login-subtitle">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold hover:underline">
                                Daftar di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-6 demo-card backdrop-blur-sm rounded-xl p-4 border">
            <p class="text-xs font-semibold demo-text-light text-center mb-2">📋 Demo Akun</p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="demo-text-light">Admin:</div>
                <div class="demo-value-light font-mono">admin@mbg.com / password</div>
                <div class="demo-text-light">Manager:</div>
                <div class="demo-value-light font-mono">manager@mbg.com / password</div>
                <div class="demo-text-light">Staff:</div>
                <div class="demo-value-light font-mono">staff@mbg.com / password</div>
                <div class="demo-text-light">Driver:</div>
                <div class="demo-value-light font-mono">driver@mbg.com / password</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-xs demo-text-light">
                &copy; {{ date('Y') }} Dapur MBG - Kitchen Management System
            </p>
        </div>
    </div>

    <!-- Dark Mode Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</body>
</html>
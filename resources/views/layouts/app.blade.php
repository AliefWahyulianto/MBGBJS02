<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Kitchen Management System - Dapur MBG</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Dark Mode Script (sebelum CSS) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.add('light');
            }
        })();
    </script>
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "tertiary-fixed": "#ffdad7",
                        "error-container": "#ffdad6",
                        "surface-bright": "#f8f9ff",
                        "primary": "#006c49",
                        "surface-variant": "#d3e4fe",
                        "on-secondary-fixed": "#2a1700",
                        "surface-container-low": "#eff4ff",
                        "inverse-primary": "#4edea3",
                        "on-tertiary": "#ffffff",
                        "inverse-surface": "#213145",
                        "on-primary-fixed": "#002113",
                        "background": "#f8f9ff",
                        "secondary-container": "#fea619",
                        "surface-container-lowest": "#ffffff",
                        "error": "#ba1a1a",
                        "tertiary-container": "#fc7c78",
                        "secondary": "#855300",
                        "outline-variant": "#bbcabf",
                        "surface-dim": "#cbdbf5",
                        "surface": "#f8f9ff",
                        "on-primary": "#ffffff",
                        "on-surface-variant": "#3c4a42",
                        "tertiary-fixed-dim": "#ffb3af",
                        "on-background": "#0b1c30",
                        "on-tertiary-container": "#711419",
                        "primary-fixed-dim": "#4edea3",
                        "on-primary-fixed-variant": "#005236",
                        "outline": "#6c7a71",
                        "on-secondary": "#ffffff",
                        "primary-fixed": "#6ffbbe",
                        "on-error-container": "#93000a",
                        "on-primary-container": "#00422b",
                        "primary-container": "#10b981",
                        "surface-container": "#e5eeff",
                        "inverse-on-surface": "#eaf1ff",
                        "secondary-fixed": "#ffddb8",
                        "surface-tint": "#006c49",
                        "on-tertiary-fixed": "#410005",
                        "on-surface": "#0b1c30",
                        "on-tertiary-fixed-variant": "#842225",
                        "on-error": "#ffffff",
                        "surface-container-highest": "#d3e4fe",
                        "surface-container-high": "#dce9ff",
                        "tertiary": "#a43a3a",
                        "on-secondary-container": "#684000",
                        "secondary-fixed-dim": "#ffb95f",
                        "on-secondary-fixed-variant": "#653e00"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "sidebar-width": "260px",
                        "container-padding": "2rem",
                        "inline-padding-x": "1rem",
                        "stack-gap": "1.5rem",
                        "grid-gutter": "1rem",
                        "inline-padding-y": "0.75rem"
                    },
                    fontFamily: {
                        "body-sm": ["Inter"],
                        "h2": ["Inter"],
                        "status-badge": ["Inter"],
                        "h1": ["Inter"],
                        "display-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "label-caps": ["Inter"]
                    },
                    fontSize: {
                        "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "h2": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "status-badge": ["12px", {"lineHeight": "12px", "fontWeight": "500"}],
                        "h1": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "display-lg": ["30px", {"lineHeight": "38px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}]
                    }
                }
            }
        }
    </script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .chart-gradient {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0) 100%);
        }
        
        /* Smooth transition untuk dark mode */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }
        
        /* Light mode (default) */
        body {
            background-color: #f8f9ff;
        }
        
        /* Dark mode */
        .dark body {
            background-color: #1a1a2e;
        }
        
        .dark .bg-white {
            background-color: #16213e;
            border-color: #2c3e66;
        }
        
        .dark .bg-slate-50,
        .dark .bg-slate-50/50 {
            background-color: #0f172a;
        }
        
        .dark .text-slate-800,
        .dark .text-slate-900,
        .dark .text-on-background {
            color: #e2e8f0;
        }
        
        .dark .text-slate-500,
        .dark .text-slate-400,
        .dark .text-on-surface-variant {
            color: #94a3b8;
        }
        
        .dark .border-slate-200,
        .dark .border-slate-100,
        .dark .border-slate-300 {
            border-color: #2c3e66;
        }
        
        .dark .bg-slate-100 {
            background-color: #1e293b;
        }
        
        .dark .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
        }
        
        /* Sidebar dark mode */
        .dark aside {
            background-color: #0f172a;
            border-color: #2c3e66;
        }
        
        /* Table dark mode */
        .dark table thead tr {
            background-color: #0f172a;
        }
        
        .dark table tbody tr:hover {
            background-color: #1e293b;
        }
        
        /* Card dark mode */
        .dark .bg-emerald-50,
        .dark .bg-emerald-50\/50 {
            background-color: #064e3b;
        }
        
        .dark .bg-orange-50 {
            background-color: #7c2d12;
        }
        
        .dark .bg-red-50 {
            background-color: #7f1d1d;
        }
        
        .dark .bg-blue-50 {
            background-color: #1e3a8a;
        }

        /* Top Bar */
        .dark header {
            background-color: #0f172a !important;
            border-bottom-color: #2c3e66 !important;
        }

        .dark header .bg-slate-50 {
            background-color: #1e293b !important;
        }

        .dark header input {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
        }

        .dark header input::placeholder {
            color: #64748b !important;
        }

        .dark header .text-slate-900 {
            color: #e2e8f0 !important;
        }

        .dark header .border-slate-200 {
            border-color: #2c3e66 !important;
        }

        .dark header .bg-white\/90 {
            background-color: #0f172a !important;
            backdrop-filter: blur(4px);
        }

        /* Dark Mode Toggle Button */
        .dark #darkModeToggle {
            color: #94a3b8 !important;
        }

        .dark #darkModeToggle:hover {
            color: #10b981 !important;
        }

        /* Sidebar User Profile */
        .dark aside .bg-slate-50 {
            background-color: #1e293b !important;
        }

        .dark aside .text-slate-900 {
            color: #e2e8f0 !important;
        }

        .dark aside .text-slate-500 {
            color: #94a3b8 !important;
        }

        .dark aside .border-slate-100 {
            border-color: #2c3e66 !important;
        }

        .dark aside .bg-emerald-100 {
            background-color: #064e3b !important;
        }

        .dark aside .text-emerald-600 {
            color: #34d399 !important;
        }

        /* Dropdown/Modal */
        .dark .bg-white\/90 {
            background-color: #0f172a !important;
        }

        .dark .bg-slate-100 {
            background-color: #1e293b !important;
        }

        /* Button hover states */
        .dark button:hover {
            background-color: #1e293b !important;
        }

        /* Scrollbar (opsional) */
        .dark ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .dark ::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #2c3e66;
            border-radius: 4px;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #3b5a9a;
        }
        .dark header {
        background-color: #0f172a !important;
        border-bottom-color: #2c3e66 !important;
    }
    
        .dark header .bg-slate-50 {
            background-color: #1e293b !important;
        }
        
        .dark header input {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
        }
        
        .dark header input::placeholder {
            color: #64748b !important;
        }
        
        .dark header .text-slate-900 {
            color: #e2e8f0 !important;
        }
        
        .dark header .border-slate-200 {
            border-color: #2c3e66 !important;
        }
        
        .dark header .bg-white\/90 {
            background-color: #0f172a !important;
        }
        
        .dark #darkModeToggle {
            color: #94a3b8 !important;
        }
        
        .dark #darkModeToggle:hover {
            color: #10b981 !important;
        }
        
        .dark aside .bg-slate-50 {
            background-color: #1e293b !important;
        }
        
        .dark aside .text-slate-900 {
            color: #e2e8f0 !important;
        }
        
        .dark aside .text-slate-500 {
            color: #94a3b8 !important;
        }
        
        .dark aside .border-slate-100 {
            border-color: #2c3e66 !important;
        }
        
        .dark aside .bg-emerald-100 {
            background-color: #064e3b !important;
        }
        
        .dark aside .text-emerald-600 {
            color: #34d399 !important;
        }
        
        .dark .bg-white\/90 {
            background-color: #0f172a !important;
        }
        
        .dark .bg-slate-100 {
            background-color: #1e293b !important;
        }
        
        .dark button:hover {
            background-color: #1e293b !important;
        }
        
        /* Dark mode scrollbar */
        .dark ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .dark ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: #2c3e66;
            border-radius: 4px;
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #3b5a9a;
        }
        /* Dark mode untuk form input */
        .dark input:not([type="checkbox"]):not([type="radio"]),
        .dark select,
        .dark textarea {
            background-color: #1e293b !important;
            border-color: #2c3e66 !important;
            color: #e2e8f0 !important;
        }

        .dark input:focus,
        .dark select:focus,
        .dark textarea:focus {
            border-color: #10b981 !important;
            outline: none !important;
            ring: 2px solid #10b981 !important;
        }

        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #64748b !important;
        }

        .dark label {
            color: #94a3b8 !important;
        }

        /* Dark mode untuk select option */
        .dark select option {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
        }

        /* Dark mode untuk file input */
        .dark input[type="file"] {
            background-color: #1e293b !important;
            border-color: #2c3e66 !important;
            color: #e2e8f0 !important;
        }

        .dark input[type="file"]::file-selector-button {
            background-color: #2c3e66 !important;
            color: #e2e8f0 !important;
            border: none !important;
            padding: 8px 16px !important;
            border-radius: 8px !important;
            cursor: pointer !important;
        }

        .dark input[type="file"]::file-selector-button:hover {
            background-color: #3b5a9a !important;
        }

        /* Dark mode untuk card dan border */
        .dark .bg-white {
            background-color: #16213e !important;
        }

        .dark .border-slate-200,
        .dark .border-slate-300 {
            border-color: #2c3e66 !important;
        }

        .dark .bg-slate-50,
        .dark .bg-slate-50\/50 {
            background-color: #0f172a !important;
        }

        .dark .text-slate-500,
        .dark .text-slate-400,
        .dark .text-slate-600 {
            color: #94a3b8 !important;
        }

        .dark .text-slate-700,
        .dark .text-slate-800,
        .dark .text-slate-900 {
            color: #e2e8f0 !important;
        }
            /* TAMBAHKAN CSS FORM INPUT DARK MODE DI SINI */
        .dark input:not([type="checkbox"]):not([type="radio"]),
        .dark select,
        .dark textarea {
            background-color: #1e293b !important;
            border-color: #2c3e66 !important;
            color: #e2e8f0 !important;
        }
    </style>
</head>
<body class="bg-background font-body-md text-on-background antialiased">

    <!-- Sidebar Navigation -->
    <aside class="fixed left-0 top-0 h-full w-64 bg-white border-r border-slate-200 shadow-sm z-50 flex flex-col">
        <!-- Logo Area -->
        <div class="p-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center text-white">
                <span class="material-symbols-outlined">restaurant_menu</span>
            </div>
            <div>
                <h1 class="text-lg font-black text-primary leading-tight">Dapur MBG</h1>
                <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Bojongsari 02</p>
            </div>
        </div>

        <!-- Navigation Menu dengan Treeview -->
        <nav class="flex-1 mt-4 overflow-y-auto">
            <ul class="space-y-1 px-2">
                
                <!-- DASHBOARD -->
                <li>
                    <a href="{{ route('dashboard.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                       {{ request()->routeIs('dashboard.index') 
                              ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                              : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span class="text-sm">Dashboard</span>
                    </a>
                </li>

                <!-- INVENTARIS -->
                <li x-data="{ 
                    open: localStorage.getItem('sidebar_inventaris') === 'true',
                    toggle() {
                        this.open = !this.open;
                        localStorage.setItem('sidebar_inventaris', this.open);
                    }
                }">
                    <button @click="toggle()" 
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 text-slate-500 hover:text-primary hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined">inventory</span>
                            <span class="text-sm font-medium">Inventaris</span>
                        </div>
                        <span class="material-symbols-outlined text-sm transition-all duration-300 ease-out" 
                              :class="{ 'rotate-90': open, 'rotate-0': !open }">chevron_right</span>
                    </button>
                    
                    <ul x-show="open" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="ml-6 mt-1 space-y-1 border-l border-slate-200 pl-2 overflow-hidden">
                        <li>
                            <a href="{{ route('bahan.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                               {{ request()->routeIs('bahan.*') 
                                      ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                                      : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                                <span class="material-symbols-outlined text-sm">inventory_2</span>
                                <span class="text-sm">Bahan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('stok-masuk.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                               {{ request()->routeIs('stok-masuk.*') 
                                      ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                                      : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                                <span class="material-symbols-outlined text-sm">move_to_inbox</span>
                                <span class="text-sm">Stok Masuk</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('stok-keluar.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                               {{ request()->routeIs('stok-keluar.*') 
                                      ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                                      : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                                <span class="material-symbols-outlined text-sm">outbox</span>
                                <span class="text-sm">Stok Keluar</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('stok-opname.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                               {{ request()->routeIs('stok-opname.*') 
                                      ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                                      : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                                <span class="material-symbols-outlined text-sm">inventory</span>
                                <span class="text-sm">Stok Opname</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('supplier.index') }}" 
                            class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                            {{ request()->routeIs('supplier.*') 
                                    ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                                    : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                                <span class="material-symbols-outlined text-sm">storefront</span>
                                <span class="text-sm">Supplier</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('stok-mengendap.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                               {{ request()->routeIs('stok-mengendap.*') 
                                      ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                                      : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                                <span class="material-symbols-outlined text-sm">inventory</span>
                                <span class="text-sm">Stok Mengendap</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- OPERASIONAL -->
                <li x-data="{ 
                    open: localStorage.getItem('sidebar_operasional') === 'true',
                    toggle() {
                        this.open = !this.open;
                        localStorage.setItem('sidebar_operasional', this.open);
                    }
                }">
                    <button @click="toggle()" 
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 text-slate-500 hover:text-primary hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined">restaurant</span>
                            <span class="text-sm font-medium">Operasional</span>
                        </div>
                        <span class="material-symbols-outlined text-sm transition-all duration-300 ease-out" 
                              :class="{ 'rotate-90': open, 'rotate-0': !open }">chevron_right</span>
                    </button>
                    
                    <ul x-show="open" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="ml-6 mt-1 space-y-1 border-l border-slate-200 pl-2 overflow-hidden">
                        <li>
                            <a href="{{ route('menu.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                               {{ request()->routeIs('menu.*') 
                                      ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                                      : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                                <span class="material-symbols-outlined text-sm">restaurant_menu</span>
                                <span class="text-sm">Menu</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('produksi.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                               {{ request()->routeIs('produksi.*') 
                                      ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                                      : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                                <span class="material-symbols-outlined text-sm">factory</span>
                                <span class="text-sm">Produksi</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- KEUANGAN -->
                <li x-data="{ 
                    open: localStorage.getItem('sidebar_keuangan') === 'true',
                    toggle() {
                        this.open = !this.open;
                        localStorage.setItem('sidebar_keuangan', this.open);
                    }
                }">
                    <button @click="toggle()" 
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 text-slate-500 hover:text-primary hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined">payments</span>
                            <span class="text-sm font-medium">Keuangan</span>
                        </div>
                        <span class="material-symbols-outlined text-sm transition-all duration-300 ease-out" 
                              :class="{ 'rotate-90': open, 'rotate-0': !open }">chevron_right</span>
                    </button>
                    
                    <ul x-show="open" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="ml-6 mt-1 space-y-1 border-l border-slate-200 pl-2 overflow-hidden">
                        <li>
                            <a href="{{ route('keuangan.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                               {{ request()->routeIs('keuangan.*') 
                                      ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                                      : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                                <span class="material-symbols-outlined text-sm">payments</span>
                                <span class="text-sm">Transaksi</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- LAPORAN -->
                <li x-data="{ 
                    open: localStorage.getItem('sidebar_laporan') === 'true',
                    toggle() {
                        this.open = !this.open;
                        localStorage.setItem('sidebar_laporan', this.open);
                    }
                }">
                    <button @click="toggle()" 
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-all duration-200 text-slate-500 hover:text-primary hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined">assessment</span>
                            <span class="text-sm font-medium">Laporan</span>
                        </div>
                        <span class="material-symbols-outlined text-sm transition-all duration-300 ease-out" 
                              :class="{ 'rotate-90': open, 'rotate-0': !open }">chevron_right</span>
                    </button>
                    
                    <ul x-show="open" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="ml-6 mt-1 space-y-1 border-l border-slate-200 pl-2 overflow-hidden">
                        <li>
                            <a href="{{ route('laporan.index') }}" 
                               class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                               {{ request()->routeIs('laporan.index') 
                                      ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                                      : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                                <span class="material-symbols-outlined text-sm">assessment</span>
                                <span class="text-sm">Laporan Operasional</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- PENGATURAN -->
                <li>
                    <a href="{{ route('setting.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                       {{ request()->routeIs('setting.*') 
                              ? 'text-primary bg-emerald-50/50 border-l-4 border-primary font-bold' 
                              : 'text-slate-500 hover:text-primary hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined">settings</span>
                        <span class="text-sm font-medium">Pengaturan</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- User Profile -->
        <div class="p-4 border-t border-slate-100">
            <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-50">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-600">person</span>
                </div>
                <div class="overflow-hidden flex-1">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name ?? 'Guest' }}</p>
                    <p class="text-[10px] text-slate-500 truncate">
                        @auth
                            @if(Auth::user()->role == 'admin') Administrator
                            @elseif(Auth::user()->role == 'manager') Manager
                            @elseif(Auth::user()->role == 'staff') Staff
                            @else Driver
                            @endif
                        @else
                            Belum Login
                        @endauth
                    </p>
                </div>
                @auth
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="p-1 text-slate-400 hover:text-red-500 transition-colors" title="Logout">
                        <span class="material-symbols-outlined text-sm">logout</span>
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="ml-64 min-h-screen flex flex-col">
        
        <!-- Top Bar -->
        <header class="fixed top-0 right-0 z-40 w-[calc(100%-16rem)] h-16 bg-white/90 backdrop-blur-md border-b border-slate-200 flex justify-between items-center px-8">
            <!-- Search Bar -->
            <div class="flex items-center gap-4 flex-1">
                <div class="relative w-full max-w-md">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-slate-50 border-none rounded-full text-sm focus:ring-2 focus:ring-primary-container transition-all" 
                           type="text" placeholder="Cari bahan atau laporan...">
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-6 pl-6">
                <div class="flex items-center gap-2">
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" class="p-2 text-slate-500 hover:text-primary transition-transform active:scale-95 duration-150">
                        <span class="material-symbols-outlined" id="darkModeIcon">dark_mode</span>
                    </button>
                    <div class="relative">
                        <button id="notifButton" class="relative p-2 text-slate-500 hover:text-primary transition-transform active:scale-95 duration-150">
                            <span class="material-symbols-outlined">notifications</span>
                            <span id="notifBadge" class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full border-2 border-white hidden"></span>
                        </button>
                        
                        <!-- Dropdown Notifikasi -->
                        <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl border border-slate-200 shadow-lg z-50">
                            <div class="p-3 border-b border-slate-100 font-semibold text-slate-800">Notifikasi</div>
                            <div id="notifList" class="max-h-96 overflow-y-auto">
                                <div class="p-4 text-center text-slate-400 text-sm">Memuat...</div>
                            </div>
                            <div class="p-2 border-t border-slate-100 text-center">
                                <a href="{{ route('notification.index') }}" class="text-xs text-emerald-600 hover:underline">Lihat Semua</a>
                            </div>
                        </div>
                    </div>
                    <button class="p-2 text-slate-500 hover:text-primary transition-transform active:scale-95 duration-150">
                        <span class="material-symbols-outlined">help_outline</span>
                    </button>
                </div>
                <div class="h-8 w-[1px] bg-slate-200"></div>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-slate-900">Kitchen Management</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="mt-16 p-8 flex-1">
            @yield('content')
        </main>
    </div>

    <!-- Floating Action Button -->
    <button class="fixed bottom-8 right-8 w-14 h-14 bg-primary text-white rounded-full shadow-lg flex items-center justify-center hover:scale-110 active:scale-95 transition-all z-50">
        <span class="material-symbols-outlined text-3xl">history_edu</span>
    </button>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeIcon = document.getElementById('darkModeIcon');
        const html = document.documentElement;

        // Cek preferensi dari localStorage
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            html.classList.add('dark');
            html.classList.remove('light');
            darkModeIcon.textContent = 'light_mode';
        } else {
            html.classList.add('light');
            html.classList.remove('dark');
            darkModeIcon.textContent = 'dark_mode';
        }

        darkModeToggle.addEventListener('click', () => {
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
                darkModeIcon.textContent = 'dark_mode';
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                darkModeIcon.textContent = 'light_mode';
            }
            
            // Kirim ke server
            fetch('{{ route("setting.theme") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ theme: html.classList.contains('dark') ? 'dark' : 'light' })
            }).catch(err => console.log('Theme saved locally'));
        });

        // Notifikasi Dropdown
        const notifButton = document.getElementById('notifButton');
        const notifDropdown = document.getElementById('notifDropdown');
        const notifList = document.getElementById('notifList');
        const notifBadge = document.getElementById('notifBadge');

        if (notifButton) {
            notifButton.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.classList.toggle('hidden');
                if (!notifDropdown.classList.contains('hidden')) {
                    loadNotifications();
                }
            });
            
            document.addEventListener('click', () => {
                notifDropdown.classList.add('hidden');
            });
            
            notifDropdown.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }

        function loadNotifications() {
            fetch('{{ route("notification.unread-count") }}')
                .then(res => res.json())
                .then(data => {
                    if (data.count > 0) {
                        notifBadge.classList.remove('hidden');
                    } else {
                        notifBadge.classList.add('hidden');
                    }
                });
            
            fetch('{{ route("notification.latest") }}')
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        notifList.innerHTML = '<div class="p-4 text-center text-slate-400 text-sm">Tidak ada notifikasi</div>';
                        return;
                    }
                    
                    let html = '';
                    data.forEach(notif => {
                        html += `
                            <div class="p-3 border-b border-slate-100 hover:bg-slate-50 ${!notif.is_read ? 'bg-emerald-50/30' : ''}">
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-sm ${notif.type == 'stok_habis' ? 'text-red-500' : 'text-orange-500'}">
                                        ${notif.type == 'stok_habis' ? 'error' : 'warning'}
                                    </span>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-slate-800">${notif.title}</p>
                                        <p class="text-[10px] text-slate-500 mt-1">${notif.message.substring(0, 50)}${notif.message.length > 50 ? '...' : ''}</p>
                                        <p class="text-[10px] text-slate-400 mt-1">${new Date(notif.created_at).toLocaleString()}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    notifList.innerHTML = html;
                });
        }

        // Load notifikasi setiap 1 menit
        setInterval(loadNotifications, 60000);
        loadNotifications();
    </script>
</body>
</html>
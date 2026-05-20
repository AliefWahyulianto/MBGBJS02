<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Bahan;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::with('resep.bahan')->orderBy('created_at', 'desc')->paginate(8);
        $bahans = Bahan::orderBy('nama')->get();
        
        $totalMenu = Menu::count();
        $menuTersedia = Menu::where('status', 'tersedia')->count();
        $menuHabis = Menu::where('status', 'habis')->count();

        return view('menu.index', compact('menus', 'bahans', 'totalMenu', 'menuTersedia', 'menuHabis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bahans = Bahan::orderBy('nama')->get();
        return view('menu.create', compact('bahans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150|unique:menus',
            'kategori' => 'required|string',
            'harga_jual' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('menus', 'public');
        }

        $validated['status'] = 'tersedia';

        Menu::create($validated);

        return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        $menu->load('resep.bahan');
        return view('menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $bahans = Bahan::orderBy('nama')->get();
        $resep = $menu->resep->keyBy('bahan_id');
        return view('menu.edit', compact('menu', 'bahans', 'resep'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150|unique:menus,nama,' . $menu->id,
            'kategori' => 'required|string',
            'harga_jual' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('gambar')) {
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('menus', 'public');
        }

        $menu->update($validated);

        return redirect()->route('menu.index')->with('success', 'Menu berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        // Hapus gambar
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }
        
        // Hapus resep terkait
        Resep::where('menu_id', $menu->id)->delete();
        
        $menu->delete();

        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus!');
    }

    /**
     * Update komposisi bahan (resep) untuk menu
     */
    public function updateKomposisi(Request $request, Menu $menu)
    {
        $request->validate([
            'komposisi' => 'required|array',
            'komposisi.*.bahan_id' => 'required|exists:bahans,id',
            'komposisi.*.jumlah' => 'required|numeric|min:0.01',
            'komposisi.*.satuan' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            // Hapus resep lama
            Resep::where('menu_id', $menu->id)->delete();

            // Simpan resep baru
            foreach ($request->komposisi as $item) {
                if (!empty($item['bahan_id']) && !empty($item['jumlah'])) {
                    Resep::create([
                        'menu_id' => $menu->id,
                        'bahan_id' => $item['bahan_id'],
                        'jumlah' => $item['jumlah'],
                        'satuan' => $item['satuan']
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Komposisi bahan berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Ambil data resep untuk menu (AJAX)
     */
    public function getResep(Menu $menu)
    {
        $resep = $menu->resep()->with('bahan')->get();
        return response()->json($resep);
    }

    /**
     * Filter menu (AJAX)
     */
    public function filter(Request $request)
    {
        $query = Menu::with('resep.bahan');
        
        if ($request->kategori && $request->kategori != 'semua') {
            $query->where('kategori', $request->kategori);
        }
        if ($request->status && $request->status != 'semua') {
            $query->where('status', $request->status);
        }
        
        $menus = $query->get();
        
        // Hitung status ketersediaan realtime
        foreach ($menus as $menu) {
            $menu->status_realtime = $this->getStatusKetersediaan($menu);
        }
        
        return response()->json($menus);
    }

    /**
     * Hitung status ketersediaan menu berdasarkan stok bahan
     */
    private function getStatusKetersediaan($menu)
    {
        foreach ($menu->resep as $item) {
            if ($item->bahan->stok < $item->jumlah) {
                return 'habis';
            }
            if ($item->bahan->stok <= $item->bahan->stok_minimal) {
                return 'terbatas';
            }
        }
        return 'tersedia';
    }
}
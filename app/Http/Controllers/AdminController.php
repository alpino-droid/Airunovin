<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Club;
use App\Models\event;
use App\Models\Marketplace;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $events = event::with('user')->orderBy('tanggal', 'desc')->get();
        $clubs  = Club::with('user')->orderBy('created_at', 'desc')->get();
        $provinsi = Provinsi::orderBy('provinsi')->get();
        $totalUsers = User::count();
        $totalProducts = Product::count();

        return view('admin.dashboard', compact('events', 'clubs', 'provinsi', 'totalUsers', 'totalProducts'));
    }

    // ==========================================
    // CRUD EVENT
    // ==========================================
    public function eventStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'penyelenggara' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'id_provinsi' => 'nullable|integer',
            'kota' => 'required|string|max:255',
            'sumber' => 'nullable|string|max:255',
            'htm' => 'nullable|numeric|min:0',
            'kelasPertandingan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'poster' => ['nullable', 'array', 'max:' . event::MAX_POSTERS],
            'poster.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $posterPaths = [];
        if ($request->hasFile('poster')) {
            foreach ($request->file('poster') as $file) {
                $posterPaths[] = $file->store('PosterEvent', 'public');
            }
        }

        $userId = Auth::id() ?? User::first()?->id ?? 1;

        event::create([
            'id_user' => $userId,
            'nama' => $request->nama,
            'tanggal' => $request->tanggal,
            'penyelenggara' => $request->penyelenggara,
            'lokasi' => $request->lokasi,
            'id_provinsi' => $request->id_provinsi,
            'kota' => $request->kota,
            'sumber' => $request->sumber,
            'htm' => $request->htm,
            'kelasPertandingan' => $request->kelasPertandingan,
            'deskripsi' => $request->deskripsi,
            'poster' => $posterPaths ?: null,
        ]);

        return redirect()->back()->with('success', 'Event berhasil ditambahkan!');
    }

    public function eventUpdate(Request $request, $id)
    {
        $event = event::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'penyelenggara' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'id_provinsi' => 'nullable|integer',
            'kota' => 'required|string|max:255',
            'sumber' => 'nullable|string|max:255',
            'htm' => 'nullable|numeric|min:0',
            'kelasPertandingan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'poster' => ['nullable', 'array', 'max:' . event::MAX_POSTERS],
            'poster.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        if ($request->hasFile('poster')) {
            if (!empty($event->poster) && is_array($event->poster)) {
                foreach ($event->poster as $oldPoster) {
                    Storage::disk('public')->delete($oldPoster);
                }
            }
            $posterPaths = [];
            foreach ($request->file('poster') as $file) {
                $posterPaths[] = $file->store('PosterEvent', 'public');
            }
            $validated['poster'] = $posterPaths;
        }

        $event->update($validated);

        return redirect()->back()->with('success', 'Event berhasil diperbarui!');
    }

    public function eventDestroy($id)
    {
        $event = event::findOrFail($id);

        if (!empty($event->poster) && is_array($event->poster)) {
            foreach ($event->poster as $poster) {
                Storage::disk('public')->delete($poster);
            }
        }

        $event->delete();

        return redirect()->back()->with('success', 'Event berhasil dihapus!');
    }

    // ==========================================
    // CRUD CLUB
    // ==========================================
    public function clubStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'induk_organisasi' => 'required|string|max:255',
            'id_provinsi' => 'required|integer',
            'city' => 'required|string|max:255',
            'gform_link' => 'nullable|string|max:500',
            'deskripsi' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('club_logos', 'public');
        } else {
            $validated['logo'] = 'default_club.png';
        }

        $validated['id_user'] = Auth::id() ?? User::first()?->id ?? 1;

        Club::create($validated);

        return redirect()->back()->with('success', 'Club berhasil ditambahkan!');
    }

    public function clubUpdate(Request $request, $id)
    {
        $club = Club::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'induk_organisasi' => 'required|string|max:255',
            'id_provinsi' => 'required|integer',
            'city' => 'required|string|max:255',
            'gform_link' => 'nullable|string|max:500',
            'deskripsi' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($club->logo && $club->logo !== 'default_club.png') {
                Storage::disk('public')->delete($club->logo);
            }
            $validated['logo'] = $request->file('logo')->store('club_logos', 'public');
        }

        $club->update($validated);

        return redirect()->back()->with('success', 'Club berhasil diperbarui!');
    }

    public function clubDestroy($id)
    {
        $club = Club::findOrFail($id);

        if ($club->logo && $club->logo !== 'default_club.png') {
            Storage::disk('public')->delete($club->logo);
        }

        $club->delete();

        return redirect()->back()->with('success', 'Club berhasil dihapus!');
    }

    // ==========================================
    // CRUD MARKETPLACE / PRODUK
    // ==========================================
    public function productStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'merk' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'kondisi' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'id_marketplace' => 'nullable|integer',
            'payment_methods' => 'nullable|array',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('Product', 'public');
        }

        $validated['id_user'] = Auth::id() ?? User::first()?->id ?? 1;

        if (empty($validated['id_marketplace'])) {
            $firstMarket = Marketplace::first();
            $validated['id_marketplace'] = $firstMarket?->id ?? 1;
        }

        if (empty($validated['payment_methods'])) {
            $validated['payment_methods'] = ['Transfer Bank', 'QRIS'];
        }

        Product::create($validated);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function productUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'merk' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'kondisi' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'id_marketplace' => 'nullable|integer',
            'payment_methods' => 'nullable|array',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('Product', 'public');
        }

        if ($request->has('payment_methods')) {
            $validated['payment_methods'] = $request->input('payment_methods');
        }

        $product->update($validated);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function productDestroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    // ==========================================
    // VIEW PAGES ADMIN
    // ==========================================
    public function club(Request $request)
    {
        $provinsi = Provinsi::orderBy('provinsi')->get();
        $query = Club::with('user');

        if ($request->filled('province')) {
            $query->where('id_provinsi', $request->integer('province'));
        }
        if ($request->filled('city')) {
            $query->where('city', $request->string('city'));
        }

        $clubs = $query->orderBy('created_at', 'desc')->get();
        $users = User::all();
        $cities = Club::query()->whereNotNull('city')->distinct()->orderBy('city')->pluck('city');

        return view('admin.club', compact('clubs', 'provinsi', 'users', 'cities'));
    }

    public function event()
    {
        $events = event::with('user')->orderBy('tanggal', 'desc')->get();
        $provinsi = Provinsi::orderBy('provinsi')->get();

        return view('admin.event', compact('events', 'provinsi'));
    }

    public function marketplace()
    {
        $products = Product::with(['user', 'marketplace'])->orderBy('created_at', 'desc')->get();
        $marketplaces = Marketplace::orderBy('nama')->get();
        $users = User::orderBy('nama')->get();

        return view('admin.marketplace', compact('products', 'marketplaces', 'users'));
    }

    public function registrasi()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.registrasi', compact('users'));
    }

    public function settings()
    {
        return view('admin.settings');
    }
}

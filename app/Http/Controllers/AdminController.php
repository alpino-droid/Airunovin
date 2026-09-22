<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Club;
use App\Models\event;
use App\Models\Marketplace;
use App\Models\Product;
use App\Models\User;
use App\Models\Notification;
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
            'status' => 'nullable|in:panding,tolak,diterima',
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
            'status' => $request->status ?? 'diterima',
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
            'status' => 'nullable|in:panding,tolak,diterima',
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
            'status' => 'nullable|in:panding,tolak,diterima',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('club_logos', 'public');
        } else {
            $validated['logo'] = 'default_club.png';
        }

        $validated['id_user'] = Auth::id() ?? User::first()?->id ?? 1;
        $validated['status'] = $request->status ?? 'diterima';

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
            'status' => 'nullable|in:panding,tolak,diterima',
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
    // CRUD MARKETPLACE (TOKO)
    // ==========================================
    public function marketplaceStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'id_user' => 'nullable|integer|exists:users,id',
            'status' => 'nullable|in:panding,tolak,diterima',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('MarketplaceLogo', 'public');
        } else {
            $validated['logo'] = 'default_marketplace.png';
        }

        $validated['id_user'] = $validated['id_user'] ?? (Auth::id() ?? User::first()?->id ?? 1);
        $validated['status'] = $request->status ?? 'diterima';

        Marketplace::create($validated);

        return redirect()->back()->with('success', 'Toko Marketplace berhasil ditambahkan!');
    }

    public function marketplaceUpdate(Request $request, $id)
    {
        $marketplace = Marketplace::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'id_user' => 'nullable|integer|exists:users,id',
            'status' => 'nullable|in:panding,tolak,diterima',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($marketplace->logo && $marketplace->logo !== 'default_marketplace.png' && Storage::disk('public')->exists($marketplace->logo)) {
                Storage::disk('public')->delete($marketplace->logo);
            }
            $validated['logo'] = $request->file('logo')->store('MarketplaceLogo', 'public');
        }

        $marketplace->update($validated);

        return redirect()->back()->with('success', 'Toko Marketplace berhasil diperbarui!');
    }

    public function marketplaceDestroy($id)
    {
        $marketplace = Marketplace::findOrFail($id);

        if ($marketplace->logo && $marketplace->logo !== 'default_marketplace.png' && Storage::disk('public')->exists($marketplace->logo)) {
            Storage::disk('public')->delete($marketplace->logo);
        }

        $marketplace->delete();

        return redirect()->back()->with('success', 'Toko Marketplace berhasil dihapus!');
    }

    // ==========================================
    // CRUD PRODUK
    // ==========================================
    public function productStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'merk' => 'required|string|max:255',
            'unit' => 'nullable|in:rifle,shootgun,macinegun,sniper,handgun',
            'sparepart' => 'nullable|string|max:255',
            'aksesoris' => 'nullable|string|max:255',
            'jenis' => 'nullable|string|max:100',
            'kondisi' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'status' => 'nullable|in:panding,tolak,diterima',
            'id_marketplace' => 'nullable|integer',
            'payment_methods' => 'nullable|array',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if (empty($validated['jenis'])) {
            $validated['jenis'] = $validated['unit'] ?: ($validated['sparepart'] ?: ($validated['aksesoris'] ?: $validated['merk']));
        }

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('Product', 'public');
        }

        $validated['id_user'] = Auth::id() ?? User::first()?->id ?? 1;
        $validated['status'] = $request->status ?? 'diterima';

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
            'unit' => 'nullable|in:rifle,shootgun,macinegun,sniper,handgun',
            'sparepart' => 'nullable|string|max:255',
            'aksesoris' => 'nullable|string|max:255',
            'jenis' => 'nullable|string|max:100',
            'kondisi' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'status' => 'nullable|in:panding,tolak,diterima',
            'id_marketplace' => 'nullable|integer',
            'payment_methods' => 'nullable|array',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if (empty($validated['jenis'])) {
            $validated['jenis'] = $validated['unit'] ?: ($validated['sparepart'] ?: ($validated['aksesoris'] ?: $validated['merk']));
        }

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
        $marketplaces = Marketplace::with('user')->withCount('products')->orderBy('created_at', 'desc')->get();
        $users = User::orderBy('nama')->get();

        return view('admin.marketplace', compact('marketplaces', 'users'));
    }

    public function product()
    {
        $products = Product::with(['user', 'marketplace'])->orderBy('created_at', 'desc')->get();
        $marketplaces = Marketplace::orderBy('nama')->get();
        $users = User::orderBy('nama')->get();

        return view('admin.product', compact('products', 'marketplaces', 'users'));
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

    // ==========================================
    // MODERASI STATUS (TERIMA / TOLAK)
    // ==========================================
    public function eventStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:panding,tolak,diterima',
        ]);

        $event = event::findOrFail($id);
        $event->status = $request->status;
        $event->save();

        try {
            if ($event->id_user) {
                $isAccepted = $event->status === 'diterima';
                Notification::create([
                    'id_user' => $event->id_user,
                    'title' => $isAccepted ? 'Event Disetujui' : 'Event Ditolak',
                    'message' => "Event '{$event->nama}' Anda telah " . ($isAccepted ? 'disetujui oleh admin dan kini tayang ke publik.' : 'ditolak oleh admin.'),
                    'type' => $isAccepted ? 'success' : 'danger',
                    'category' => 'all',
                    'link' => route('isiEvent', $event->id),
                    'icon' => $isAccepted ? 'bi-calendar-check' : 'bi-calendar-x',
                ]);
            }
        } catch (\Throwable $e) {}

        $label = $request->status === 'diterima' ? 'diterima (disetujui)' : ($request->status === 'tolak' ? 'ditolak' : 'diubah menjadi pending');
        return redirect()->back()->with('success', "Event '{$event->nama}' berhasil {$label}!");
    }

    public function clubStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:panding,tolak,diterima',
        ]);

        $club = Club::findOrFail($id);
        $club->status = $request->status;
        $club->save();

        try {
            if ($club->id_user) {
                $isAccepted = $club->status === 'diterima';
                Notification::create([
                    'id_user' => $club->id_user,
                    'title' => $isAccepted ? 'Club Disetujui' : 'Club Ditolak',
                    'message' => "Club '{$club->nama}' Anda telah " . ($isAccepted ? 'disetujui oleh admin dan kini tayang ke publik.' : 'ditolak oleh admin.'),
                    'type' => $isAccepted ? 'success' : 'danger',
                    'category' => 'all',
                    'link' => route('isiClub', $club->id),
                    'icon' => $isAccepted ? 'bi-shield-check' : 'bi-shield-x',
                ]);
            }
        } catch (\Throwable $e) {}

        $label = $request->status === 'diterima' ? 'diterima (disetujui)' : ($request->status === 'tolak' ? 'ditolak' : 'diubah menjadi pending');
        return redirect()->back()->with('success', "Club '{$club->nama}' berhasil {$label}!");
    }

    public function marketplaceStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:panding,tolak,diterima',
        ]);

        $marketplace = Marketplace::findOrFail($id);
        $marketplace->status = $request->status;
        $marketplace->save();

        try {
            if ($marketplace->id_user) {
                $isAccepted = $marketplace->status === 'diterima';
                Notification::create([
                    'id_user' => $marketplace->id_user,
                    'title' => $isAccepted ? 'Toko Marketplace Disetujui' : 'Toko Marketplace Ditolak',
                    'message' => "Toko '{$marketplace->nama}' Anda telah " . ($isAccepted ? 'disetujui oleh admin dan kini aktif.' : 'ditolak oleh admin.'),
                    'type' => $isAccepted ? 'success' : 'danger',
                    'category' => 'seller',
                    'link' => route('market', $marketplace->id),
                    'icon' => $isAccepted ? 'bi-shop' : 'bi-x-circle',
                ]);
            }
        } catch (\Throwable $e) {}

        $label = $request->status === 'diterima' ? 'diterima (disetujui)' : ($request->status === 'tolak' ? 'ditolak' : 'diubah menjadi pending');
        return redirect()->back()->with('success', "Toko Marketplace '{$marketplace->nama}' berhasil {$label}!");
    }

    public function productStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:panding,tolak,diterima',
        ]);

        $product = Product::findOrFail($id);
        $product->status = $request->status;
        $product->save();

        try {
            if ($product->id_user) {
                $isAccepted = $product->status === 'diterima';
                Notification::create([
                    'id_user' => $product->id_user,
                    'title' => $isAccepted ? 'Produk Disetujui' : 'Produk Ditolak',
                    'message' => "Produk '{$product->nama}' Anda telah " . ($isAccepted ? 'disetujui oleh admin dan kini tayang di marketplace.' : 'ditolak oleh admin.'),
                    'type' => $isAccepted ? 'success' : 'danger',
                    'category' => 'seller',
                    'link' => route('isiMarketplace', $product->id),
                    'icon' => $isAccepted ? 'bi-box-seam' : 'bi-x-circle',
                ]);
            }
        } catch (\Throwable $e) {}

        $label = $request->status === 'diterima' ? 'diterima (disetujui)' : ($request->status === 'tolak' ? 'ditolak' : 'diubah menjadi pending');
        return redirect()->back()->with('success', "Produk '{$product->nama}' berhasil {$label}!");
    }
}

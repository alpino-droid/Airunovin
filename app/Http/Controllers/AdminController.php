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
    public function dashboard(Request $request)
    {
        $eventQuery = event::with('user');
        $clubQuery = Club::with('user');

        if ($request->filled('search') || $request->filled('q')) {
            $s = trim((string) ($request->input('search') ?? $request->input('q')));
            $eventQuery->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('kota', 'like', "%{$s}%")
                  ->orWhere('penyelenggara', 'like', "%{$s}%");
            });
            $clubQuery->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%")
                  ->orWhere('induk_organisasi', 'like', "%{$s}%");
            });
        }

        $events = $eventQuery->orderBy('tanggal', 'desc')->get();
        $clubs  = $clubQuery->orderBy('created_at', 'desc')->get();
        $provinsi = Provinsi::orderBy('provinsi')->get();
        $totalUsers = User::count();
        $totalProducts = Product::count();

        // ==========================================
        // TREN AKTIVITAS 12 BULAN & PEMILIHAN TAHUN
        // ==========================================
        $currentYear = (int) date('Y');
        $selectedYear = (int) $request->input('year', $currentYear);
        if ($selectedYear < 2000 || $selectedYear > 2100) {
            $selectedYear = $currentYear;
        }

        // Tentukan daftar tahun yang tersedia
        $yearsInDb = collect()
            ->merge(event::selectRaw('YEAR(tanggal) as y')->whereNotNull('tanggal')->pluck('y'))
            ->merge(event::selectRaw('YEAR(created_at) as y')->whereNotNull('created_at')->pluck('y'))
            ->merge(Club::selectRaw('YEAR(created_at) as y')->whereNotNull('created_at')->pluck('y'))
            ->merge(User::selectRaw('YEAR(created_at) as y')->whereNotNull('created_at')->pluck('y'))
            ->merge(Product::selectRaw('YEAR(created_at) as y')->whereNotNull('created_at')->pluck('y'))
            ->filter(fn($y) => $y >= 2000 && $y <= 2100)
            ->map(fn($y) => (int) $y)
            ->unique();

        $minYear = $yearsInDb->min() ? min($yearsInDb->min(), $currentYear - 2) : ($currentYear - 2);
        $maxYear = $yearsInDb->max() ? max($yearsInDb->max(), $currentYear + 2) : ($currentYear + 2);
        $availableYears = range($minYear, $maxYear);

        // Agregasi aktivitas 12 bulan untuk tahun yang dipilih
        $monthNames = [
            1 => ['short' => 'Jan', 'full' => 'Januari'],
            2 => ['short' => 'Feb', 'full' => 'Februari'],
            3 => ['short' => 'Mar', 'full' => 'Maret'],
            4 => ['short' => 'Apr', 'full' => 'April'],
            5 => ['short' => 'Mei', 'full' => 'Mei'],
            6 => ['short' => 'Jun', 'full' => 'Juni'],
            7 => ['short' => 'Jul', 'full' => 'Juli'],
            8 => ['short' => 'Agu', 'full' => 'Agustus'],
            9 => ['short' => 'Sep', 'full' => 'September'],
            10 => ['short' => 'Okt', 'full' => 'Oktober'],
            11 => ['short' => 'Nov', 'full' => 'November'],
            12 => ['short' => 'Des', 'full' => 'Desember'],
        ];

        // Query data aktivitas per bulan
        $eventsActive = event::where(function($q) use ($selectedYear) {
                $q->whereYear('tanggal', $selectedYear)
                  ->orWhereYear('created_at', $selectedYear);
            })
            ->get(['id', 'tanggal', 'created_at']);

        $clubsByMonth = Club::whereYear('created_at', $selectedYear)
            ->selectRaw('MONTH(created_at) as m, count(*) as c')
            ->groupBy('m')
            ->pluck('c', 'm')
            ->toArray();

        $usersByMonth = User::whereYear('created_at', $selectedYear)
            ->selectRaw('MONTH(created_at) as m, count(*) as c')
            ->groupBy('m')
            ->pluck('c', 'm')
            ->toArray();

        $productsByMonth = Product::whereYear('created_at', $selectedYear)
            ->selectRaw('MONTH(created_at) as m, count(*) as c')
            ->groupBy('m')
            ->pluck('c', 'm')
            ->toArray();

        $monthlyTrend = [];
        $currentMonth = (int) date('n');

        for ($m = 1; $m <= 12; $m++) {
            $eCount = $eventsActive->filter(function($ev) use ($selectedYear, $m) {
                $hasTanggal = $ev->tanggal && date('Y', strtotime($ev->tanggal)) == $selectedYear && (int) date('n', strtotime($ev->tanggal)) === $m;
                $hasCreated = $ev->created_at && (int)$ev->created_at->year === $selectedYear && (int)$ev->created_at->month === $m;
                return $hasTanggal || $hasCreated;
            })->count();

            $cCount = (int) ($clubsByMonth[$m] ?? 0);
            $uCount = (int) ($usersByMonth[$m] ?? 0);
            $pCount = (int) ($productsByMonth[$m] ?? 0);
            $total = $eCount + $cCount + $uCount + $pCount;

            $monthlyTrend[$m] = [
                'month' => $m,
                'short' => $monthNames[$m]['short'],
                'full' => $monthNames[$m]['full'],
                'events' => $eCount,
                'clubs' => $cCount,
                'users' => $uCount,
                'products' => $pCount,
                'total' => $total,
                'is_current' => ($selectedYear === $currentYear && $m === $currentMonth),
            ];
        }

        $maxActivity = max(array_merge([1], array_column($monthlyTrend, 'total')));
        foreach ($monthlyTrend as $m => &$item) {
            $item['height'] = $item['total'] > 0 
                ? max(14, min(100, (int) round(($item['total'] / $maxActivity) * 100))) 
                : 4;
        }
        unset($item);

        $yearTotalActivity = array_sum(array_column($monthlyTrend, 'total'));

        // Jika request via AJAX / JSON untuk pergantian tahun instan
        if ($request->ajax() || $request->wantsJson() || $request->has('ajax')) {
            return response()->json([
                'selectedYear' => $selectedYear,
                'yearTotalActivity' => $yearTotalActivity,
                'availableYears' => $availableYears,
                'monthlyTrend' => array_values($monthlyTrend),
            ]);
        }

        return view('admin.dashboard', compact(
            'events', 
            'clubs', 
            'provinsi', 
            'totalUsers', 
            'totalProducts',
            'availableYears',
            'selectedYear',
            'monthlyTrend',
            'yearTotalActivity'
        ));
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
        if ($request->filled('search') || $request->filled('q')) {
            $s = trim((string) ($request->input('search') ?? $request->input('q')));
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%")
                  ->orWhere('induk_organisasi', 'like', "%{$s}%");
            });
        }

        $clubs = $query->orderBy('created_at', 'desc')->get();
        $users = User::all();
        $cities = Club::query()->whereNotNull('city')->distinct()->orderBy('city')->pluck('city');

        return view('admin.club', compact('clubs', 'provinsi', 'users', 'cities'));
    }

    public function event(Request $request)
    {
        $query = event::with('user');

        if ($request->filled('search') || $request->filled('q')) {
            $s = trim((string) ($request->input('search') ?? $request->input('q')));
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('kota', 'like', "%{$s}%")
                  ->orWhere('penyelenggara', 'like', "%{$s}%");
            });
        }

        $events = $query->orderBy('tanggal', 'desc')->get();
        $provinsi = Provinsi::orderBy('provinsi')->get();

        return view('admin.event', compact('events', 'provinsi'));
    }

    public function marketplace(Request $request)
    {
        $query = Marketplace::with('user')->withCount('products');

        if ($request->filled('search') || $request->filled('q')) {
            $s = trim((string) ($request->input('search') ?? $request->input('q')));
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('deskripsi', 'like', "%{$s}%");
            });
        }

        $marketplaces = $query->orderBy('created_at', 'desc')->get();
        $users = User::orderBy('nama')->get();

        return view('admin.marketplace', compact('marketplaces', 'users'));
    }

    public function product(Request $request)
    {
        $query = Product::with(['user', 'marketplace']);

        if ($request->filled('search') || $request->filled('q')) {
            $s = trim((string) ($request->input('search') ?? $request->input('q')));
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('merk', 'like', "%{$s}%")
                  ->orWhere('jenis', 'like', "%{$s}%")
                  ->orWhere('lokasi', 'like', "%{$s}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')->get();
        $marketplaces = Marketplace::orderBy('nama')->get();
        $users = User::orderBy('nama')->get();

        return view('admin.product', compact('products', 'marketplaces', 'users'));
    }

    public function registrasi(Request $request)
    {
        $query = User::query();

        if ($request->filled('search') || $request->filled('q')) {
            $s = trim((string) ($request->input('search') ?? $request->input('q')));
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();
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

    // ==========================================
    // MESIN PENCARIAN ADMIN (SMART ADMIN ROUTER)
    // ==========================================
    public function adminSearch(Request $request)
    {
        $q = trim((string) ($request->input('q') ?? $request->input('search') ?? ''));

        if ($q === '') {
            return redirect()->route('admin.dashboard');
        }

        $lower = strtolower($q);

        // 1. Kamus Kata Kunci Niat (Explicit Intent Keywords)
        $eventKeywords = [
            'event', 'acara', 'turnamen', 'tournament', 'lomba', 'kompetisi', 'competition',
            'cup', 'championship', 'tanding', 'skirmish', 'milsim', 'gathering', 'gath',
            'jadwal', 'tiket', 'htm', 'walikota', 'bupati', 'gubernur', 'challenge', 'piala'
        ];

        $clubKeywords = [
            'club', 'klub', 'komunitas', 'community', 'team', 'tim', 'squad', 'regiment',
            'divisi', 'batalyon', 'roster', 'organisasi', 'fai', 'porgasi', 'inassoc', 'inasoc',
            'airsofter', 'anggota', 'member', 'brotherhood', 'troops', 'troopers'
        ];

        $marketKeywords = [
            'marketplace', 'market', 'toko', 'shop', 'store', 'seller', 'lapak', 'penjual'
        ];

        $productKeywords = [
            'produk', 'product', 'beli', 'jual', 'harga', 'stok', 'unit', 'sparepart', 'part', 'aksesoris', 'aksesori',
            'kondisi', 'baru', 'bekas', 'second', 'rifle', 'shootgun', 'shotgun', 'macinegun',
            'machinegun', 'sniper', 'handgun', 'pistol', 'aeg', 'gbb', 'gbbr', 'spring', 'co2',
            'inbar', 'gearbox', 'hopup', 'hop up', 'scope', 'red dot', 'vest', 'mag', 'magazine',
            'bb', 'patch', 'tactical', 'helm', 'kacamata', 'goggle', 'holster', 'sling'
        ];

        $userKeywords = [
            'user', 'pengguna', 'member', 'registrasi', 'pendaftar', 'akun', 'profil', 'email'
        ];

        $eventScore = 0;
        $clubScore = 0;
        $marketScore = 0;
        $productScore = 0;
        $userScore = 0;

        foreach ($eventKeywords as $kw) {
            if (preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $lower)) {
                $eventScore += 100;
            }
        }

        foreach ($clubKeywords as $kw) {
            if (preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $lower)) {
                $clubScore += 100;
            }
        }

        foreach ($marketKeywords as $kw) {
            if (preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $lower)) {
                $marketScore += 100;
            }
        }

        foreach ($productKeywords as $kw) {
            if (preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $lower)) {
                $productScore += 100;
            }
        }

        foreach ($userKeywords as $kw) {
            if (preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $lower)) {
                $userScore += 100;
            }
        }

        // 2. Cek Kecocokan Data Aktual di Database (Admin melihat seluruh data)
        $stopWords = ['airsoft', 'airsoftgun', 'gun', 'dan', 'di', 'ke', 'dari', 'yang', 'untuk', 'pada'];
        $rawTerms = array_filter(explode(' ', $lower), fn($t) => strlen($t) >= 2);
        $significantTerms = array_values(array_diff($rawTerms, $stopWords));

        // A. Event DB Matches
        $eventExact = event::where('nama', 'like', "%{$q}%")->count();
        $eventTermMatches = 0;
        if (!empty($significantTerms)) {
            $eventTermMatches = event::where(function ($sub) use ($significantTerms) {
                foreach ($significantTerms as $t) {
                    $sub->orWhere('nama', 'like', "%{$t}%")
                        ->orWhere('kota', 'like', "%{$t}%")
                        ->orWhere('penyelenggara', 'like', "%{$t}%");
                }
            })->count();
        }
        $eventScore += ($eventExact * 80) + ($eventTermMatches * 25);

        // B. Club DB Matches
        $clubExact = Club::where('nama', 'like', "%{$q}%")->count();
        $clubTermMatches = 0;
        if (!empty($significantTerms)) {
            $clubTermMatches = Club::where(function ($sub) use ($significantTerms) {
                foreach ($significantTerms as $t) {
                    $sub->orWhere('nama', 'like', "%{$t}%")
                        ->orWhere('city', 'like', "%{$t}%")
                        ->orWhere('induk_organisasi', 'like', "%{$t}%");
                }
            })->count();
        }
        $clubScore += ($clubExact * 80) + ($clubTermMatches * 25);

        // C. Marketplace DB Matches
        $marketExact = Marketplace::where('nama', 'like', "%{$q}%")->count();
        $marketScore += ($marketExact * 80);

        // D. Product DB Matches
        $productExact = Product::where('nama', 'like', "%{$q}%")->count();
        $productTermMatches = 0;
        if (!empty($significantTerms)) {
            $productTermMatches = Product::where(function ($sub) use ($significantTerms) {
                foreach ($significantTerms as $t) {
                    $sub->orWhere('nama', 'like', "%{$t}%")
                        ->orWhere('merk', 'like', "%{$t}%")
                        ->orWhere('unit', 'like', "%{$t}%")
                        ->orWhere('sparepart', 'like', "%{$t}%")
                        ->orWhere('aksesoris', 'like', "%{$t}%");
                }
            })->count();
        }
        $productScore += ($productExact * 80) + ($productTermMatches * 25);

        // E. User DB Matches
        $userExact = User::where('nama', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->orWhere('username', 'like', "%{$q}%")
            ->count();
        $userScore += ($userExact * 80);

        // Bersihkan awalan kata pengenal kategori
        $cleanSearch = $q;
        $cleanSearch = preg_replace('/^(event|acara|turnamen|lomba)\s+/i', '', $cleanSearch);
        $cleanSearch = preg_replace('/^(club|klub|komunitas)\s+/i', '', $cleanSearch);
        $cleanSearch = preg_replace('/^(marketplace|market|toko)\s+/i', '', $cleanSearch);
        $cleanSearch = preg_replace('/^(produk|product|barang)\s+/i', '', $cleanSearch);
        $cleanSearch = preg_replace('/^(user|pengguna|member|registrasi|pendaftar)\s+/i', '', $cleanSearch);
        $cleanSearch = trim($cleanSearch);
        $searchTerm = !empty($cleanSearch) ? $cleanSearch : $q;

        // 3. Evaluasi Skor dan Alihkan Antar Halaman Admin
        $scores = [
            'event' => $eventScore,
            'club' => $clubScore,
            'product' => $productScore,
            'marketplace' => $marketScore,
            'registrasi' => $userScore,
        ];

        arsort($scores);
        $topCategory = array_key_first($scores);
        $topScore = $scores[$topCategory];

        if ($topScore > 0) {
            return match ($topCategory) {
                'event' => redirect()->route('admin.event', ['search' => $searchTerm]),
                'club' => redirect()->route('admin.club', ['search' => $searchTerm]),
                'product' => redirect()->route('admin.product', ['search' => $searchTerm]),
                'marketplace' => redirect()->route('admin.marketplace', ['search' => $searchTerm]),
                'registrasi' => redirect()->route('admin.registrasi', ['search' => $searchTerm]),
                default => redirect()->route('admin.event', ['search' => $searchTerm]),
            };
        }

        // Standar jika tidak ada kategori spesifik: bawa ke admin event dengan filter
        return redirect()->route('admin.event', ['search' => $searchTerm]);
    }

    /**
     * Live search suggestion khusus admin
     */
    public function searchSuggest(Request $request)
    {
        $q = trim((string) ($request->input('q') ?? ''));
        if (strlen($q) < 2) {
            return response()->json([
                'events' => [],
                'clubs' => [],
                'marketplaces' => [],
                'products' => [],
                'users' => []
            ]);
        }

        $events = event::where(function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('kota', 'like', "%{$q}%");
            })
            ->take(3)
            ->get(['id', 'nama', 'kota', 'status'])
            ->map(fn($e) => [
                'type' => 'event',
                'title' => $e->nama,
                'sub' => $e->kota . ' • Status: ' . $e->status,
                'url' => route('admin.event', ['search' => $e->nama]),
                'icon' => 'bi-calendar-event'
            ]);

        $clubs = Club::where(function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('city', 'like', "%{$q}%");
            })
            ->take(3)
            ->get(['id', 'nama', 'city', 'status'])
            ->map(fn($c) => [
                'type' => 'club',
                'title' => $c->nama,
                'sub' => $c->city . ' • Status: ' . $c->status,
                'url' => route('admin.club', ['search' => $c->nama]),
                'icon' => 'bi-shield-shaded'
            ]);

        $marketplaces = Marketplace::where(function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('deskripsi', 'like', "%{$q}%");
            })
            ->take(3)
            ->get(['id', 'nama', 'status'])
            ->map(fn($m) => [
                'type' => 'marketplace',
                'title' => $m->nama,
                'sub' => 'Toko • Status: ' . $m->status,
                'url' => route('admin.marketplace', ['search' => $m->nama]),
                'icon' => 'bi-shop'
            ]);

        $products = Product::where(function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('merk', 'like', "%{$q}%");
            })
            ->take(3)
            ->get(['id', 'nama', 'harga', 'status'])
            ->map(fn($p) => [
                'type' => 'product',
                'title' => $p->nama,
                'sub' => 'Rp ' . number_format($p->harga, 0, ',', '.') . ' • Status: ' . $p->status,
                'url' => route('admin.product', ['search' => $p->nama]),
                'icon' => 'bi-box-seam'
            ]);

        $users = User::where(function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('username', 'like', "%{$q}%");
            })
            ->take(3)
            ->get(['id', 'nama', 'email'])
            ->map(fn($u) => [
                'type' => 'user',
                'title' => $u->nama,
                'sub' => $u->email,
                'url' => route('admin.registrasi', ['search' => $u->nama]),
                'icon' => 'bi-person'
            ]);

        return response()->json([
            'events' => $events,
            'clubs' => $clubs,
            'marketplaces' => $marketplaces,
            'products' => $products,
            'users' => $users,
        ]);
    }
}
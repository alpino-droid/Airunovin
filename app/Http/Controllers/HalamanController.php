<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Marketplace;
use App\Models\ProductPurchase;
use App\Models\Provinsi;
use App\Models\event;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HalamanController extends Controller
{
    public function marketplace(Request $request)
    {
        $query = Product::with(['user', 'marketplace'])
            ->where('status', 'diterima')
            ->where(function ($q) {
                $q->whereNull('id_marketplace')
                  ->orWhereHas('marketplace', fn ($mq) => $mq->where('status', 'diterima'));
            });

        if ($request->filled('province')) {
            $query->whereHas('user', fn ($userQuery) => $userQuery->where('province', $request->string('province')));
        }

        if ($request->filled('city')) {
            $query->where('lokasi', 'like', '%' . $request->string('city') . '%');
        }

        // Filter Unit
        if ($request->filled('unit') || $request->string('jenis') === 'unit') {
            $rawUnit = $request->string('unit') ?: 'all';
            if ($rawUnit === 'all') {
                $query->where(fn ($q) => $q->whereNotNull('unit')->where('unit', '!=', '')->orWhere('jenis', 'like', '%unit%'));
            } else {
                $normalized = strtolower(trim((string) $rawUnit));
                if (str_contains($normalized, 'shotgun') || str_contains($normalized, 'shootgun')) $normalized = 'shootgun';
                elseif (str_contains($normalized, 'machine') || str_contains($normalized, 'macine')) $normalized = 'macinegun';
                elseif (str_contains($normalized, 'sniper')) $normalized = 'sniper';
                elseif (str_contains($normalized, 'handgun') || str_contains($normalized, 'pistol')) $normalized = 'handgun';
                elseif (str_contains($normalized, 'rifle')) $normalized = 'rifle';

                $query->where(fn ($q) => $q->where('unit', $normalized)->orWhere('unit', 'like', "%{$rawUnit}%")->orWhere('nama', 'like', "%{$rawUnit}%"));
            }
        }

        // Filter Sparepart
        if ($request->filled('sparepart') || $request->string('jenis') === 'sparepart') {
            $partVal = $request->string('sparepart') ?: 'all';
            if ($partVal === 'all') {
                $query->where(fn ($q) => $q->whereNotNull('sparepart')->where('sparepart', '!=', '')->orWhere('jenis', 'like', '%sparepart%'));
            } else {
                $query->where(fn ($q) => $q->where('sparepart', 'like', "%{$partVal}%")->orWhere('nama', 'like', "%{$partVal}%"));
            }
        }

        // Filter Aksesoris
        if ($request->filled('aksesoris') || in_array($request->string('jenis'), ['aksesoris', 'accessories', 'Accessories'])) {
            $accVal = $request->string('aksesoris') ?: 'all';
            if ($accVal === 'all') {
                $query->where(fn ($q) => $q->whereNotNull('aksesoris')->where('aksesoris', '!=', '')->orWhere('jenis', 'like', '%aksesoris%')->orWhere('jenis', 'like', '%accessories%')->orWhere('jenis', 'like', '%perlengkapan%'));
            } else {
                $query->where(fn ($q) => $q->where('aksesoris', 'like', "%{$accVal}%")->orWhere('nama', 'like', "%{$accVal}%"));
            }
        }

        // Filter Merk
        if ($request->filled('merk') || $request->string('jenis') === 'merk') {
            $merkVal = $request->string('merk') ?: 'all';
            if ($merkVal !== 'all') {
                $query->where('merk', 'like', "%{$merkVal}%");
            } else {
                $query->whereNotNull('merk')->where('merk', '!=', '');
            }
        }

        if ($request->filled('brand')) {
            $query->where('merk', $request->string('brand'));
        }

        if ($request->filled('condition')) {
            $query->where('kondisi', $request->string('condition'));
        }

        if ($request->filled('payment_method')) {
            $pm = $request->string('payment_method');
            $query->where(function ($q) use ($pm) {
                $q->whereJsonContains('payment_methods', $pm)
                  ->orWhere('payment_methods', 'like', "%\"{$pm}\"%")
                  ->orWhere('payment_methods', 'like', "%{$pm}%");
            });
        }

        if ($request->filled('q') || $request->filled('search')) {
            $keyword = trim((string) ($request->input('q') ?? $request->input('search')));
            $terms = array_filter(explode(' ', $keyword), fn($t) => strlen($t) >= 2);

            $query->where(function ($q) use ($keyword, $terms) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('merk', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%")
                  ->orWhere('lokasi', 'like', "%{$keyword}%")
                  ->orWhere('unit', 'like', "%{$keyword}%")
                  ->orWhere('sparepart', 'like', "%{$keyword}%")
                  ->orWhere('aksesoris', 'like', "%{$keyword}%");

                foreach ($terms as $term) {
                    $q->orWhere('nama', 'like', "%{$term}%")
                      ->orWhere('merk', 'like', "%{$term}%")
                      ->orWhere('unit', 'like', "%{$term}%")
                      ->orWhere('sparepart', 'like', "%{$term}%")
                      ->orWhere('aksesoris', 'like', "%{$term}%");
                }
            });
        }

        if ($request->filled('min_price')) {
            $query->where('harga', '>=', (int) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('harga', '<=', (int) $request->input('max_price'));
        }

        switch ($request->query('sort')) {
            case 'price_asc':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('harga', 'desc');
                break;
            case 'popular':
                $query->orderBy('stok', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->latest()->get();
        $provinsi = Provinsi::orderBy('provinsi')->get();
        $cities = Product::query()->where('status', 'diterima')->whereNotNull('lokasi')->distinct()->orderBy('lokasi')->pluck('lokasi');
        $brands = Product::query()->where('status', 'diterima')->whereNotNull('merk')->where('merk', '!=', '')->distinct()->orderBy('merk')->pluck('merk');

        return view('pages.marketplace', compact('products', 'provinsi', 'cities', 'brands'));
    }

    public function DM()
    {
        $sellerId = Auth::id();
        $marketplaces = Marketplace::where('id_user', $sellerId)->withCount('products')->latest()->get();
        $marketplace = $marketplaces->first();
        $products = Product::where('id_user', $sellerId)->with('marketplace')->latest()->get();

        $sellerMarketplaceIds = $marketplaces->pluck('id')->filter()->values();
        $sellerProductIds = $products->pluck('id')->filter()->values();

        $orders = ProductPurchase::query()
            ->where(function ($q) use ($sellerMarketplaceIds, $sellerProductIds, $sellerId) {
                $q->where('id_seller', $sellerId);
                if ($sellerMarketplaceIds->isNotEmpty()) {
                    $q->orWhereIn('id_marketplace', $sellerMarketplaceIds);
                }
                if ($sellerProductIds->isNotEmpty()) {
                    $q->orWhereIn('id_product', $sellerProductIds);
                }
                $q->orWhereHas('product', function ($pq) use ($sellerId) {
                    $pq->where('id_user', $sellerId);
                });
            })
            ->with(['product', 'user'])
            ->latest()
            ->get();

        $totalItemsSold = (int) $orders->sum('jumlah');

        $pendingOrdersCount = $orders->filter(function ($o) {
            return in_array(strtolower(trim($o->status ?? '')), ['menunggu diproses', 'diproses', 'proses', 'pending']);
        })->count();

        $completedOrdersCount = $orders->filter(function ($o) {
            return in_array(strtolower(trim($o->status ?? '')), ['selesai', 'success']);
        })->count();

        $soldProducts = $orders->groupBy(function ($order) {
            return $order->id_product ?: $order->nama_produk;
        })->map(function ($group) {
            $first = $group->first();
            return (object) [
                'id' => $first->id_product,
                'nama' => $first->nama_produk,
                'gambar' => $first->gambar_produk ?? $first->product?->gambar,
                'product' => $first->product,
                'total_qty' => $group->sum('jumlah'),
                'total_revenue' => $group->sum('total_harga'),
                'order_count' => $group->count(),
            ];
        })->values();

        return view('pages.dashboardMarketplace', compact(
            'products',
            'marketplaces',
            'marketplace',
            'orders',
            'soldProducts',
            'totalItemsSold',
            'pendingOrdersCount',
            'completedOrdersCount'
        ));
    }


    public function productStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'merk' => 'required|string|max:255',
            'unit' => 'nullable|in:rifle,shootgun,macinegun,sniper,handgun',
            'sparepart' => 'nullable|string|max:255',
            'aksesoris' => 'nullable|string|max:255',
            'kondisi' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'payment_methods' => 'required|array|min:1',
            'payment_methods.*' => 'in:QRIS,DANA,GoPay,Transfer Bank,COD',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['jenis'] = $validated['unit'] ?: ($validated['sparepart'] ?: ($validated['aksesoris'] ?: $validated['merk']));

        if ($request->hasFile('gambar')) {
            $productDirectory = public_path('storage/Product');
            File::ensureDirectoryExists($productDirectory);

            $image = $request->file('gambar');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move($productDirectory, $filename);
            $validated['gambar'] = 'Product/' . $filename;
        }

        $validated['id_user'] = Auth::id();
        Product::create($validated);

        return redirect()->route('dashboardMarketplace')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Mesin Pencari Global Pintar (Smart Global Search Router)
     * Mengarahkan pengguna secara otomatis ke Event, Club, atau Marketplace sesuai konteks pencarian.
     */
    public function globalSearch(Request $request)
    {
        $q = trim((string) ($request->input('q') ?? $request->input('search') ?? ''));

        if ($q === '') {
            return redirect()->route('home');
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
            'produk', 'product', 'marketplace', 'market', 'toko', 'shop', 'store', 'seller',
            'beli', 'jual', 'harga', 'stok', 'unit', 'sparepart', 'part', 'aksesoris', 'aksesori',
            'kondisi', 'baru', 'bekas', 'second', 'rifle', 'shootgun', 'shotgun', 'macinegun',
            'machinegun', 'sniper', 'handgun', 'pistol', 'aeg', 'gbb', 'gbbr', 'spring', 'co2',
            'inbar', 'gearbox', 'hopup', 'hop up', 'scope', 'red dot', 'vest', 'mag', 'magazine',
            'bb', 'patch', 'tactical', 'helm', 'kacamata', 'goggle', 'holster', 'sling'
        ];

        $eventScore = 0;
        $clubScore = 0;
        $marketScore = 0;

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

        // 2. Cek Kecocokan Data Aktual di Database (Real Records Matching)
        // Hilangkan kata umum / stop words agar tidak bias (seperti kata 'airsoft', 'dan', 'di')
        $stopWords = ['airsoft', 'airsoftgun', 'gun', 'dan', 'di', 'ke', 'dari', 'yang', 'untuk', 'pada'];
        $rawTerms = array_filter(explode(' ', $lower), fn($t) => strlen($t) >= 2);
        $significantTerms = array_values(array_diff($rawTerms, $stopWords));

        // A. Event DB Matches
        $eventExact = event::where('status', 'diterima')->where('nama', 'like', "%{$q}%")->count();
        $eventTermMatches = 0;
        if (!empty($significantTerms)) {
            $eventTermMatches = event::where('status', 'diterima')->where(function ($sub) use ($significantTerms) {
                foreach ($significantTerms as $t) {
                    $sub->orWhere('nama', 'like', "%{$t}%")
                        ->orWhere('kota', 'like', "%{$t}%")
                        ->orWhere('penyelenggara', 'like', "%{$t}%");
                }
            })->count();
        }
        $eventScore += ($eventExact * 80) + ($eventTermMatches * 25);

        // B. Club DB Matches
        $clubExact = Club::where('status', 'diterima')->where('nama', 'like', "%{$q}%")->count();
        $clubTermMatches = 0;
        if (!empty($significantTerms)) {
            $clubTermMatches = Club::where('status', 'diterima')->where(function ($sub) use ($significantTerms) {
                foreach ($significantTerms as $t) {
                    $sub->orWhere('nama', 'like', "%{$t}%")
                        ->orWhere('city', 'like', "%{$t}%")
                        ->orWhere('induk_organisasi', 'like', "%{$t}%");
                }
            })->count();
        }
        $clubScore += ($clubExact * 80) + ($clubTermMatches * 25);

        // C. Product & Marketplace DB Matches
        $marketExact = Product::where('status', 'diterima')->where('nama', 'like', "%{$q}%")->count();
        $marketTermMatches = 0;
        if (!empty($significantTerms)) {
            $marketTermMatches = Product::where('status', 'diterima')->where(function ($sub) use ($significantTerms) {
                foreach ($significantTerms as $t) {
                    $sub->orWhere('nama', 'like', "%{$t}%")
                        ->orWhere('merk', 'like', "%{$t}%")
                        ->orWhere('unit', 'like', "%{$t}%")
                        ->orWhere('sparepart', 'like', "%{$t}%")
                        ->orWhere('aksesoris', 'like', "%{$t}%");
                }
            })->count();
        }
        $storeMatches = Marketplace::where('status', 'diterima')->where('nama', 'like', "%{$q}%")->count();
        $marketScore += ($marketExact * 80) + ($marketTermMatches * 25) + ($storeMatches * 50);

        // Bersihkan awalan kata pengenal kategori untuk filter yang lebih bersih bila ada
        $cleanSearch = $q;
        $cleanSearch = preg_replace('/^(event|acara|turnamen|lomba)\s+/i', '', $cleanSearch);
        $cleanSearch = preg_replace('/^(club|klub|komunitas)\s+/i', '', $cleanSearch);
        $cleanSearch = preg_replace('/^(produk|product|toko|marketplace|beli|jual)\s+/i', '', $cleanSearch);
        $cleanSearch = trim($cleanSearch);
        $searchTerm = !empty($cleanSearch) ? $cleanSearch : $q;

        // 3. Evaluasi Skor dan Alihkan (Redirect)
        if ($eventScore > $clubScore && $eventScore > $marketScore) {
            return redirect()->route('event', ['search' => $searchTerm]);
        }

        if ($clubScore > $eventScore && $clubScore > $marketScore) {
            return redirect()->route('club', ['search' => $searchTerm]);
        }

        if ($marketScore > $eventScore && $marketScore > $clubScore) {
            return redirect()->route('marketplace', ['q' => $searchTerm]);
        }

        // Jika skor sama / imbang: prioritaskan berdasarkan kecocokan nama langsung
        if ($eventExact > 0 && $eventExact >= $clubExact && $eventExact >= $marketExact) {
            return redirect()->route('event', ['search' => $searchTerm]);
        }

        if ($clubExact > 0 && $clubExact >= $marketExact) {
            return redirect()->route('club', ['search' => $searchTerm]);
        }

        if ($marketExact > 0) {
            return redirect()->route('marketplace', ['q' => $searchTerm]);
        }

        // Standar bawaan jika tidak ada kata kunci khusus: bawa ke event
        return redirect()->route('event', ['search' => $searchTerm]);
    }

    /**
     * Endpoint saran live search (AJAX preview)
     */
    public function searchSuggest(Request $request)
    {
        $q = trim((string) ($request->input('q') ?? ''));
        if (strlen($q) < 2) {
            return response()->json(['events' => [], 'clubs' => [], 'products' => []]);
        }

        $events = event::where('status', 'diterima')
            ->where(function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('kota', 'like', "%{$q}%");
            })
            ->take(3)
            ->get(['id', 'nama', 'kota', 'tanggal'])
            ->map(fn($e) => [
                'type' => 'event',
                'title' => $e->nama,
                'sub' => $e->kota . ' • ' . ($e->tanggal ? $e->tanggal->format('d M Y') : ''),
                'url' => route('isiEvent', $e->id),
                'icon' => 'bi-calendar-event'
            ]);

        $clubs = Club::where('status', 'diterima')
            ->where(function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('city', 'like', "%{$q}%");
            })
            ->take(3)
            ->get(['id', 'nama', 'city', 'induk_organisasi'])
            ->map(fn($c) => [
                'type' => 'club',
                'title' => $c->nama,
                'sub' => $c->city . ' • ' . $c->induk_organisasi,
                'url' => route('isiClub', $c->id),
                'icon' => 'bi-shield-shaded'
            ]);

        $products = Product::where('status', 'diterima')
            ->where(function ($query) use ($q) {
                $query->where('nama', 'like', "%{$q}%")
                      ->orWhere('merk', 'like', "%{$q}%");
            })
            ->take(3)
            ->get(['id', 'nama', 'harga', 'merk'])
            ->map(fn($p) => [
                'type' => 'product',
                'title' => $p->nama,
                'sub' => 'Rp ' . number_format($p->harga, 0, ',', '.') . ' • ' . $p->merk,
                'url' => route('isiMarketplace', $p->id),
                'icon' => 'bi-box-seam'
            ]);

        return response()->json([
            'events' => $events,
            'clubs' => $clubs,
            'products' => $products,
        ]);
    }
}
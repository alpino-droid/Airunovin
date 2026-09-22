<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MarketplaceController extends Controller
{
    public function create()
    {
        return view('pages.marketplaceCreate');
    }

    public function market(Request $request, $marketplace = null)
    {
        // 1. Tentukan toko (marketplace) yang akan ditampilkan
        $selectedMarketplace = null;

        if ($marketplace instanceof Marketplace) {
            $selectedMarketplace = $marketplace;
        } elseif ($marketplace) {
            $selectedMarketplace = Marketplace::with('user')->find($marketplace);
            if (!$selectedMarketplace) {
                // Jika yang dikirimkan adalah id_user penjual
                $selectedMarketplace = Marketplace::with('user')->where('id_user', $marketplace)->first();
            }
        }

        // Cek query string jika belum ditemukan
        if (!$selectedMarketplace && ($request->filled('store') || $request->filled('marketplace'))) {
            $storeId = $request->query('store') ?: $request->query('marketplace');
            $selectedMarketplace = Marketplace::with('user')->find($storeId)
                ?: Marketplace::with('user')->where('id_user', $storeId)->first();
        }

        // Jika belum ada, dan user sedang login, prioritaskan toko milik user yang login
        if (!$selectedMarketplace && Auth::check()) {
            $selectedMarketplace = Marketplace::with('user')->where('id_user', Auth::id())->first();
        }

        // Fallback ke toko pertama yang sudah disetujui
        if (!$selectedMarketplace) {
            $selectedMarketplace = Marketplace::where('status', 'diterima')->with('user')->first();
        }

        // Cek hak akses jika toko belum disetujui
        $isOwnerOrAdmin = Auth::check() && $selectedMarketplace && (
            Auth::id() === $selectedMarketplace->id_user ||
            Auth::user()->isAdmin()
        );

        if ($selectedMarketplace && $selectedMarketplace->status !== 'diterima' && !$isOwnerOrAdmin) {
            abort(404, 'Toko marketplace belum disetujui atau tidak tersedia.');
        }

        $isOwner = $isOwnerOrAdmin ?: (Auth::check() && Auth::id() === $selectedMarketplace?->id_user);

        // 2. Query produk toko
        $query = Product::with(['user', 'marketplace']);

        // Jika bukan pemilik atau admin, hanya tampilkan produk yang diterima
        if (!$isOwnerOrAdmin) {
            $query->where('status', 'diterima');
        }

        if ($selectedMarketplace && $selectedMarketplace->id) {
            $query->where(function ($q) use ($selectedMarketplace) {
                $q->where('id_marketplace', $selectedMarketplace->id)
                  ->orWhere('id_user', $selectedMarketplace->id_user);
            });
        }

        // Filter pencarian teks
        if ($request->filled('q')) {
            $keyword = $request->string('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('merk', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%")
                  ->orWhere('jenis', 'like', "%{$keyword}%");
            });
        }

        // Filter kategori / jenis
        if ($request->filled('jenis')) {
            $jenisVal = $request->string('jenis');
            if ($jenisVal === 'Accessories' || $jenisVal === 'Aksesoris') {
                $query->whereIn('jenis', ['Accessories', 'Aksesoris']);
            } else {
                $query->where('jenis', $jenisVal);
            }
        }

        // Filter subtipe / kata kunci unit atau sparepart (Rifle, Scope, Inbar, dsb.)
        if ($request->filled('subtype')) {
            $sub = $request->string('subtype');
            $query->where(function ($q) use ($sub) {
                $q->where('nama', 'like', "%{$sub}%")
                  ->orWhere('deskripsi', 'like', "%{$sub}%")
                  ->orWhere('merk', 'like', "%{$sub}%");
            });
        }

        // Filter merk / brand
        if ($request->filled('merk')) {
            $query->where('merk', $request->string('merk'));
        }

        // Filter kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->string('kondisi'));
        }

        $products = $query->latest()->get();

        // 3. Data pendukung untuk filter & informasi (hanya toko yang diterima)
        $allMarketplaces = Marketplace::where('status', 'diterima')->with('user')->orderBy('nama')->get();

        // Ambil daftar merk yang ada dari produk yang berstatus diterima
        $storeBrands = Product::where('status', 'diterima')
            ->when($selectedMarketplace, function ($q) use ($selectedMarketplace) {
                $q->where('id_marketplace', $selectedMarketplace->id);
            })->whereNotNull('merk')->distinct()->pluck('merk');

        if ($storeBrands->isEmpty()) {
            $storeBrands = Product::where('status', 'diterima')->whereNotNull('merk')->distinct()->pluck('merk');
        }

        $totalProductsCount = $selectedMarketplace
            ? Product::where('status', 'diterima')->where('id_marketplace', $selectedMarketplace->id)->count()
            : Product::where('status', 'diterima')->count();

        return view('pages.market', [
            'marketplace' => $selectedMarketplace,
            'marketplaces' => $allMarketplaces,
            'products' => $products,
            'brands' => $storeBrands,
            'isOwner' => $isOwner,
            'totalProductsCount' => $totalProductsCount,
        ]);
    }

    public function store(Request $request)
    {
        $marketplace = Marketplace::create($this->validatedData($request) + [
            'id_user' => Auth::id(),
        ]);

        return redirect()->route('dashboardMarketplace')->with('success', 'Marketplace berhasil dibuat.');
    }

    public function edit(Marketplace $marketplace)
    {
        $this->ensureOwner($marketplace);

        return view('pages.marketplaceCreate', compact('marketplace'));
    }

    public function update(Request $request, Marketplace $marketplace)
    {
        $this->ensureOwner($marketplace);
        $data = $this->validatedData($request);

        if ($request->hasFile('logo')) {
            $this->deleteLogo($marketplace);
        } else {
            unset($data['logo']);
        }

        $marketplace->update($data);

        return redirect()->route('dashboardMarketplace')->with('success', 'Marketplace berhasil diperbarui.');
    }

    public function destroy(Marketplace $marketplace)
    {
        $this->ensureOwner($marketplace);
        $this->deleteLogo($marketplace);
        $marketplace->delete();

        return redirect()->route('dashboardMarketplace')->with('success', 'Marketplace berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'status' => 'nullable|in:panding,tolak,diterima,terimakasih,active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('marketplace_logos', 'public');
        }

        return $data;
    }

    private function deleteLogo(Marketplace $marketplace): void
    {
        if ($marketplace->logo && Storage::disk('public')->exists($marketplace->logo)) {
            Storage::disk('public')->delete($marketplace->logo);
        }
    }

    private function ensureOwner(Marketplace $marketplace): void
    {
        abort_unless($marketplace->id_user === Auth::id(), 403);
    }
}

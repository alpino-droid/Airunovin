<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HalamanController extends Controller
{
    public function marketplace(Request $request)
    {
        $query = Product::with('user');

        if ($request->filled('province')) {
            $query->whereHas('user', fn ($userQuery) => $userQuery->where('province', $request->string('province')));
        }
        if ($request->filled('city')) {
            $query->where('lokasi', 'like', '%' . $request->string('city') . '%');
        }

        $products = $query->latest()->get();
        $provinsi = Provinsi::orderBy('provinsi')->get();
        $cities = Product::query()->whereNotNull('lokasi')->distinct()->orderBy('lokasi')->pluck('lokasi');

        return view('pages.marketplace', compact('products', 'provinsi', 'cities'));
    }

    public function isiMarketplace()
    {
        return view('pages.isiMarketplace');
    }

    public function DM()
    {
        $products = Product::where('id_user', Auth::id())->latest()->get();

        return view('pages.dashboardMarketplace', compact('products'));
    }

    public function productCreate()
    {
        return view('pages.productCreate');
    }

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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

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
}
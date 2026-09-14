<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HalamanController extends Controller
{
    public function marketplace()
    {
        return view('pages.marketplace');
    }

    public function isiMarketplace()
    {
        return view('pages.isiMarketplace');
    }

    public function DM()
    {
        return view('pages.dashboardMarketplace');
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
            $validated['gambar'] = $request->file('gambar')->store('Product', 'public');
        }

        $validated['id_user'] = Auth::id();
        Product::create($validated);

        return redirect()->route('dashboardMarketplace')->with('success', 'Produk berhasil ditambahkan.');
    }
}
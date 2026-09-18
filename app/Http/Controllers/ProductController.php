<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create()
    {
        $marketplaces = Marketplace::where('id_user', Auth::id())->orderBy('nama')->get();

        return view('pages.productCreate', compact('marketplaces'));
    }

    public function isiMarketplace(Product $product)
    {
        $product->load(['user', 'marketplace']);

        // Ambil produk serupa berdasarkan kategori/jenis, kecualikan produk saat ini
        $products = Product::where('id', '!=', $product->id)
            ->where('jenis', $product->jenis)
            ->take(6)
            ->get();

        if ($products->count() < 4) {
            $fallback = Product::where('id', '!=', $product->id)
                ->whereNotIn('id', $products->pluck('id'))
                ->take(6 - $products->count())
                ->get();
            $products = $products->merge($fallback);
        }

        return view('pages.isiMarketplace', compact('product', 'products'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $marketplace = $this->ownedMarketplace($data['id_marketplace']);

        Product::create($data + [
            'id_user' => Auth::id(),
            'id_marketplace' => $marketplace->id,
        ]);

        return redirect()->route('dashboardMarketplace')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $product = $this->ownedProduct($product);
        $marketplaces = Marketplace::where('id_user', Auth::id())->orderBy('nama')->get();

        return view('pages.productCreate', compact('product', 'marketplaces'));
    }

    public function update(Request $request, Product $product)
    {
        $product = $this->ownedProduct($product);
        $data = $this->validatedData($request);
        $this->ownedMarketplace($data['id_marketplace']);

        if (!$request->hasFile('gambar')) {
            unset($data['gambar']);
        } else {
            $this->deleteImage($product);
        }

        $product->update($data + ['id_user' => Auth::id()]);

        return redirect()->route('dashboardMarketplace')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product = $this->ownedProduct($product);
        $this->deleteImage($product);
        $product->delete();

        return redirect()->route('dashboardMarketplace')->with('success', 'Produk berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'id_marketplace' => 'required|integer|exists:marketplaces,id',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'merk' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'kondisi' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'payment_methods' => 'required|array|min:1',
            'payment_methods.*' => 'in:QRIS,DANA,GoPay,Transfer Bank,COD',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('Product', 'public');
        }

        return $data;
    }

    private function ownedMarketplace(int $id): Marketplace
    {
        return Marketplace::whereKey($id)->where('id_user', Auth::id())->firstOrFail();
    }

    private function ownedProduct(Product $product): Product
    {
        abort_unless($product->id_user === Auth::id(), 403);

        return $product;
    }

    private function deleteImage(Product $product): void
    {
        if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }
    }
}

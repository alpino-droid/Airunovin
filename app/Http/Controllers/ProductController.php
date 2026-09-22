<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\Notification;
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
        // Cegah akses jika status belum disetujui (kecuali pemilik atau admin)
        if ($product->status !== 'diterima') {
            if (!Auth::check() || (Auth::id() !== $product->id_user && !Auth::user()->isAdmin())) {
                abort(404, 'Produk belum disetujui atau tidak tersedia.');
            }
        }

        $product->load(['user', 'marketplace']);

        // Ambil produk serupa berdasarkan kategori/jenis yang sudah disetujui
        $products = Product::where('status', 'diterima')
            ->where('id', '!=', $product->id)
            ->where('jenis', $product->jenis)
            ->take(6)
            ->get();

        if ($products->count() < 4) {
            $fallback = Product::where('status', 'diterima')
                ->where('id', '!=', $product->id)
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

        $data['unit'] = $data['unit'] ?? null;
        $data['sparepart'] = $data['sparepart'] ?? null;
        $data['aksesoris'] = $data['aksesoris'] ?? null;

        $data['jenis'] = $data['unit'] ? 'Unit': ($data['sparepart'] ? 'Sparepart': ($data['aksesoris'] ? 'Aksesoris': $data['merk']));

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

    public function buy(Request $request, Product $product)
    {
        abort_unless(Auth::check(), 401, 'Silakan login terlebih dahulu untuk melakukan pemesanan.');

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . max(1, (int) $product->stok),
            'delivery_method' => 'nullable|string|max:150',
            'delivery_cost' => 'nullable|integer|min:0',
            'payment_method' => 'nullable|string|max:100',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:30',
        ]);

        $qty = (int) $validated['quantity'];
        $deliveryCost = (int) ($validated['delivery_cost'] ?? 0);
        $totalCost = ($qty * (int) $product->harga) + $deliveryCost;

        // Generate unique order code e.g. #BELI-2048
        $orderCode = '#BELI-' . rand(1000, 9999);
        while (ProductPurchase::where('order_code', $orderCode)->exists()) {
            $orderCode = '#BELI-' . rand(1000, 9999);
        }

        $sellerStore = $product->marketplace->nama ?? null;
        $sellerUser = $product->user->nama ?? 'Penjual Airsoft';
        $sellerName = $sellerStore ?: $sellerUser;

        $sellerUserId = $product->id_user ?? ($product->marketplace?->id_user ?? null);

        $purchase = ProductPurchase::create([
            'id_user' => Auth::id(),
            'id_seller' => $sellerUserId,
            'id_product' => $product->id,
            'id_marketplace' => $product->id_marketplace,
            'order_code' => $orderCode,
            'nama_produk' => $product->nama,
            'gambar_produk' => $product->gambar,
            'nama_penjual' => $sellerName,
            'harga_satuan' => (int) $product->harga,
            'jumlah' => $qty,
            'biaya_pengiriman' => $deliveryCost,
            'total_harga' => $totalCost,
            'metode_pengiriman' => $validated['delivery_method'] ?? 'Ambil di lokasi penjual (COD)',
            'metode_pembayaran' => $validated['payment_method'] ?? 'Transfer Bank',
            'nama_pembeli' => $validated['buyer_name'],
            'no_wa_pembeli' => $validated['buyer_phone'],
            'status' => 'Menunggu diproses',
        ]);

        if ($product->stok >= $qty) {
            $product->decrement('stok', $qty);
        }

        try {
            Notification::create([
                'id_user' => Auth::id(),
                'title' => 'Pembelian Berhasil',
                'message' => 'Pesanan ' . $product->nama . ' (' . $orderCode . ') berhasil dicatat.',
                'type' => 'Marketplace',
                'category' => 'buyer',
                'link' => route('dashboard'),
                'icon' => 'bi-bag-check-fill',
            ]);

            if ($product->id_user && $product->id_user !== Auth::id()) {
                Notification::create([
                    'id_user' => $product->id_user,
                    'title' => 'Pesanan Baru Masuk',
                    'message' => $validated['buyer_name'] . ' memesan ' . $qty . ' unit ' . $product->nama . ' (' . $orderCode . ').',
                    'type' => 'Marketplace',
                    'category' => 'seller',
                    'link' => route('dashboardMarketplace'),
                    'icon' => 'bi-cart-check-fill',
                ]);
            }
        } catch (\Throwable $e) {
            // Notification table fallback
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat dan dicatat di dashboard!',
            'order_code' => $orderCode,
            'purchase_id' => $purchase->id,
        ]);
    }

    public function updateOrderStatus(Request $request, ProductPurchase $purchase)
    {
        $isSeller = $purchase->id_seller === Auth::id()
            || ($purchase->product && $purchase->product->id_user === Auth::id())
            || ($purchase->marketplace && $purchase->marketplace->id_user === Auth::id());

        abort_unless($isSeller || (Auth::check() && Auth::user()->isAdmin()), 403);

        $validated = $request->validate([
            'status' => 'required|in:Menunggu diproses,Diproses,Selesai,Dibatalkan',
        ]);

        $purchase->update(['status' => $validated['status']]);

        try {
            Notification::create([
                'id_user' => $purchase->id_user,
                'title' => 'Status Pesanan Diperbarui',
                'message' => 'Pesanan ' . $purchase->nama_produk . ' (' . $purchase->order_code . ') statusnya kini: ' . $validated['status'],
                'type' => 'Marketplace',
                'category' => 'buyer',
                'link' => route('dashboard'),
                'icon' => $validated['status'] === 'Selesai' ? 'bi-check-circle-fill' : 'bi-info-circle-fill',
            ]);
        } catch (\Throwable $e) {
            // Notification table fallback
        }

        return back()->with('success', 'Status pesanan ' . $purchase->order_code . ' berhasil diubah menjadi ' . $validated['status'] . '.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MarketplaceController extends Controller
{
    public function create()
    {
        return view('pages.marketplaceCreate');
    }

    public function market()
    {
        return view('pages.market');
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
            'status' => 'required|in:active,inactive',
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

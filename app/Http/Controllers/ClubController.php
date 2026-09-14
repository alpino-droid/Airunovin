<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClubController extends Controller
{
    public function home(Request $request)
    {
        $provinsi = Provinsi::orderBy('provinsi')->get();
        $query = Club::query();

        if ($request->filled('province')) {
            $query->where('id_provinsi', $request->integer('province'));
        }
        if ($request->filled('city')) {
            $query->where('city', $request->string('city'));
        }

        $clubs = $query->latest()->get();
        $cities = Club::query()->whereNotNull('city')->distinct()->orderBy('city')->pluck('city');

        return view('pages.club', compact('clubs', 'provinsi', 'cities'));
    }

    public function isiClub()
    {
        return view('pages.isiClub');
    }

    public function club()
    {
        return $this->home(request());
    }

    public function detail($id)
    {
        $club = Club::with('user')->findOrFail($id);
        $provinsi = Provinsi::find($club->id_provinsi);

        return view('pages.isiClub', compact('club', 'provinsi'));
    }

    public function DC()
    {
        return view('pages.dashboardClub');
    }

    public function clubCreate()
    {
        $club = Club::where('id_user', Auth::id())->first();
        $provinsi = Provinsi::all();
        return view('pages.dashboardClub', compact('club', 'provinsi'));
    }

    public function clubStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'induk_organisasi' => 'required|string|max:255',
            'id_provinsi' => 'required|integer|exists:provinsi,id',
            'city' => 'required|string|max:255',
            'gform_link' => 'nullable|url|max:500',
            'deskripsi' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('club_logos', 'public');
        }
        $validated['id_user'] = Auth::id();
        Club::create($validated);
        return redirect()->route('dashboardClub')->with('success', 'Club berhasil dibuat!');
    }

    public function clubShow($id)
    {
        $club = Club::with('user')->findOrFail($id);
        $this->ensureOwner($club);
        $provinsi = Provinsi::all();
        return view('pages.dashboardClub', compact('club', 'provinsi'));
    }

    public function clubEdit($id)
    {
        $club = Club::findOrFail($id);
        $this->ensureOwner($club);
        $provinsi = Provinsi::all();
        return view('pages.dashboardClub', compact('club', 'provinsi'));
    }

    public function clubUpdate(Request $request, $id)
    {
        $club = Club::findOrFail($id);
        $this->ensureOwner($club);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'induk_organisasi' => 'required|string|max:255',
            'id_provinsi' => 'required|integer|exists:provinsi,id',
            'city' => 'required|string|max:255',
            'gform_link' => 'nullable|url|max:500',
            'deskripsi' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('logo')) {
            if ($club->logo && Storage::disk('public')->exists($club->logo)) {
                Storage::disk('public')->delete($club->logo);
            }
            $validated['logo'] = $request->file('logo')->store('club_logos', 'public');
        }
        $club->fill($validated);
        $club->save();
        return redirect()->route('dashboardClub')->with('success', 'Club berhasil diupdate!');
    }

    public function clubDestroy($id)
    {
        $club = Club::findOrFail($id);
        $this->ensureOwner($club);
        if ($club->logo && Storage::disk('public')->exists($club->logo)) {
            Storage::disk('public')->delete($club->logo);
        }
        $club->delete();
        return redirect()->route('dashboardClub')->with('success', 'Club berhasil dihapus!');
    }

    private function ensureOwner(Club $club): void
    {
        abort_unless($club->id_user === Auth::id(), 403, 'Anda tidak memiliki akses!');
    }
}
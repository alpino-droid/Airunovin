<?php

namespace App\Http\Controllers;

use App\Models\event;
use App\Models\Club;
use App\Models\product;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function home()
    {
        $eventsTerbaru = event::latest('created_at')->take(6)->get();
        $events = event::orderBy('tanggal', 'desc')->take(6)->get();
        $clubs = Club::latest()->take(6)->get();
        $products = Product::orderBy('merk', 'desc')->take(6)->get();   

        return view('pages.home', compact('eventsTerbaru', 'events', 'clubs', 'products'));
    }

    public function event(Request $request)
    {
        $provinsi = Provinsi::orderBy('provinsi')->get();
        $query = event::query();

        if ($request->filled('province')) {
            $query->where('id_provinsi', $request->integer('province'));
        }
        if ($request->filled('city')) {
            $query->where('kota', $request->string('city'));
        }

        $events = $query->orderBy('tanggal', 'desc')->get();
        $cities = event::query()->whereNotNull('kota')->distinct()->orderBy('kota')->pluck('kota');

        return view('pages.event', compact('events', 'provinsi', 'cities'));
    }

    public function isiEvent(event $event)
    {
        $events = event::whereKeyNot($event->getKey())
            ->orderBy('tanggal', 'desc')
            ->take(6)
            ->get();

        $event->load('user');

        return view('pages.isiEvent', compact('event', 'events'));
    }

    public function eventCreate()
    {
        $provinsi = Provinsi::all();
        return view('pages.eventSubmit', compact('provinsi'));
    }

    public function eventStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'penyelenggara' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'id_provinsi' => 'required|integer',
            'kota' => 'required|string|max:255',
            'sumber' => 'nullable|string|max:255',
            'htm' => 'nullable|numeric|min:0',
            'kelasPertandingan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'poster' => ['nullable', 'array', 'max:' . event::MAX_POSTERS],
            'poster.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);
        $posterPaths = [];
        if ($request->hasFile('poster')) {
            foreach ($request->file('poster') as $file) $posterPaths[] = $file->store('PosterEvent', 'public');
        }
        event::create([
            'id_user' => Auth::id(),
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
            'poster' => $posterPaths ?: null,
        ]);
        return redirect()->route('event')->with('success', 'Event berhasil dibuat.');
    }

    public function eventEdit(event $event)
    {
        $event = $this->findOwnedEvent($event);
        $provinsi = Provinsi::orderBy('provinsi')->get();

        return view('pages.eventSubmit', compact('event', 'provinsi'));
    }

    public function eventUpdate(Request $request, event $event)
    {
        $event = $this->findOwnedEvent($event);
        $validated = $this->validateEvent($request);

        if ($request->hasFile('poster')) {
            foreach ($event->poster as $poster) {
                Storage::disk('public')->delete($poster);
            }

            $validated['poster'] = collect($request->file('poster'))
                ->map(fn ($file) => $file->store('PosterEvent', 'public'))
                ->all();
        }

        $event->update($validated);

        return redirect()->route('dashboard')->with('success', 'Event berhasil diperbarui.');
    }

    public function eventDestroy(event $event)
    {
        $event = $this->findOwnedEvent($event);

        foreach ($event->poster as $poster) {
            Storage::disk('public')->delete($poster);
        }

        $event->delete();

        return redirect()->route('dashboard')->with('success', 'Event berhasil dihapus.');
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'penyelenggara' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'id_provinsi' => 'required|integer|exists:provinsi,id',
            'kota' => 'required|string|max:255',
            'sumber' => 'nullable|string|max:255',
            'htm' => 'nullable|numeric|min:0',
            'kelasPertandingan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'poster' => ['nullable', 'array', 'max:' . event::MAX_POSTERS],
            'poster.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);
    }

    private function findOwnedEvent(event $event): event
    {
        return event::whereKey($event->getKey())
            ->where('id_user', Auth::id())
            ->firstOrFail();
    }
}
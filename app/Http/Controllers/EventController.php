<?php

namespace App\Http\Controllers;

use App\Models\event;
use App\Models\Club;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function home()
    {
        $eventsTerbaru = event::latest('created_at')->take(6)->get();
        $events = event::orderBy('tanggal', 'desc')->take(6)->get();
        $clubs = Club::latest()->take(6)->get();

        return view('pages.home', compact('eventsTerbaru', 'events', 'clubs'));
    }

    public function event()
    {
        $events = event::orderBy('tanggal', 'desc')->get();
        return view('pages.event', compact('events'));
    }

    public function isiEvent()
    {
        $eventsTerbaru = event::latest('created_at')->take(6)->get();
        $events = event::orderBy('tanggal', 'desc')->take(6)->get();
        return view('pages.isiEvent', compact('events'));
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
            'deskripsi' => $request->deskripsi,
            'poster' => $posterPaths ? json_encode($posterPaths) : null,
        ]);
        return redirect()->route('event')->with('success', 'Event berhasil dibuat.');
    }
}
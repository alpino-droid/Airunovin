<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function login()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('pages.login');
    }

    public function register()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('pages.register');
    }

    public function dashboard()
    {
        if (!Auth::check()) return redirect()->route('login');
        $user = Auth::user();
        return view('pages.dashboard', compact('user'));
    }

    public function profil()
    {
        if (!Auth::check()) return redirect()->route('login');
        $user = Auth::user();
        return view('pages.dashboard', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'province' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->fill($request->only(['username', 'nama', 'email', 'phone', 'province', 'city']));
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }
        $user->save();

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function prosesRegister(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . $user->nama . '!');
    }

    public function prosesLogin(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function prosesLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }
}
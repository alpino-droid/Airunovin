<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tampilkan daftar notifikasi dengan filter kategori (semua, penjual, pembeli).
     */
    public function index(Request $request, string $mode = 'all')
    {
        // Normalisasi mode filter
        $mode = match (strtolower($mode)) {
            'penjual', 'seller' => 'seller',
            'pembeli', 'buyer'  => 'buyer',
            default             => 'all',
        };

        $query = Notification::query()->orderBy('created_at', 'desc');

        if ($mode !== 'all') {
            $query->forCategory($mode);
        }

        $notifications = $query->paginate(10)->withQueryString();

        // Statistik ringkasan
        $unreadTotal = Notification::unread()->count();
        $unreadActive = (clone $query)->unread()->count();
        $totalNotifications = Notification::count();
        $sellerCount = Notification::forCategory('seller')->count();
        $buyerCount = Notification::forCategory('buyer')->count();

        return view('pages.notifications', compact(
            'notifications',
            'mode',
            'unreadTotal',
            'unreadActive',
            'totalNotifications',
            'sellerCount',
            'buyerCount'
        ));
    }

    /**
     * Tampilkan detail notifikasi dan otomatis tandai telah dibaca.
     */
    public function show(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();

        $mode = $request->query('mode', 'all');

        return view('pages.notificationDetail', compact('notification', 'mode'));
    }

    /**
     * Tandai satu notifikasi sebagai telah dibaca.
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi ditandai sebagai telah dibaca.',
                'unread_count' => Notification::unread()->count(),
            ]);
        }

        return redirect()->back()->with('success', 'Notifikasi berhasil ditandai sebagai telah dibaca.');
    }

    /**
     * Tandai seluruh notifikasi yang belum dibaca menjadi dibaca.
     */
    public function markAllAsRead(Request $request)
    {
        Notification::unread()->update(['read_at' => now()]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi telah ditandai dibaca.',
                'unread_count' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai telah dibaca.');
    }

    /**
     * Hapus satu notifikasi.
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}

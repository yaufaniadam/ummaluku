<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
    public function getNotificationsData()
    {

        $unreadNotifications = Auth::user()->unreadNotifications;

        // 2. Format data menjadi HTML untuk dropdown
        $dropdownHtml = '';
        foreach ($unreadNotifications as $notification) {
            $icon = '<i class="fas fa-envelope mr-2"></i>';
            $time = '<span class="float-right text-muted text-sm">' . $notification->created_at->diffForHumans() . '</span>';

            // Buat form untuk menandai sudah dibaca
            $formAction = route('notifications.markAsRead', $notification->id);
            $dropdownHtml .= '
                <form action="' . $formAction . '" method="POST" class="dropdown-item-form">
                    ' . csrf_field() . '
                    <button type="submit" class="dropdown-item-button">
                        ' . $icon . $notification->data['message'] . $time . '
                    </button>
                </form>
            ';
            $dropdownHtml .= '<div class="dropdown-divider"></div>';
        }

        // Jika tidak ada notifikasi
        if ($unreadNotifications->count() === 0) {
            $dropdownHtml = '<span class="dropdown-item dropdown-header">Tidak ada notifikasi baru</span>';
        } else {
            $dropdownHtml .= '<a href="'. route('notifications.markAllAsRead' ) . '" class="dropdown-item dropdown-footer">Tandai semua sudah dibaca</a>';
        }

        // 3. Kembalikan data dalam format JSON yang dimengerti AdminLTE
        return [
            'label'       => $unreadNotifications->count(),
            'label_color' => 'danger',
            'icon_color'  => 'dark',
            'dropdown'    => $dropdownHtml,
        ];
    }

    public function showAll()
    {
        // Ambil semua notifikasi (terbaca & belum terbaca)
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Redirect ke URL asli dari notifikasi
        return redirect($notification->data['url'] ?? route('dashboard'));
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }
}

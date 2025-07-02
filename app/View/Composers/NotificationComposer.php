<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $unreadNotifications = [];
        $notificationsCount = 0;

        // Hanya ambil notifikasi jika user sudah login
        if (Auth::check()) {
            $user = Auth::user();
            $unreadNotifications = $user->unreadNotifications->take(5);
            $notificationsCount = $user->unreadNotifications->count();
        }

        // Kirim data ke view dengan nama variabel yang kita tentukan
        $view->with('unreadNotifications', $unreadNotifications);
        $view->with('notificationsCount', $notificationsCount);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NavbarNotification extends Controller
{
    public $notifications;

    public function __construct()
    {
        // Get the unread notifications for the authenticated user
        $this->notifications = Auth::user()->unreadNotifications;
    }

    public function render()
    {
        return view('components.partials.navbar', compact('notifications'));
    }
}

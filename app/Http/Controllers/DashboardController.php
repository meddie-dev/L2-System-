<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
   
    public function superAdminDashboard()
    {
        $users = User::all();
        return view('components.dashboard.superAdmin', compact('users'));
    }

    public function adminDashboard()
    {
        return view('components.dashboard.admin');
    }

    public function staffDashboard()
    {
        return view('components.dashboard.staff');
    }
}

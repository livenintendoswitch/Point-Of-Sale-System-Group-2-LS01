<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControllerDashboard extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role == 'admin') {
            return view('admin.dashboard');
        } else {
            return view('kasir.dashboard');
        }
    }
}

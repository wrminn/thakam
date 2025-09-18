<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    function backend()
    {
        
        $user = Auth::user();
        $user->user_name;
        $user->user_email;
        $user->user_permission;
        $user->user_position;
        
        return view('backend.dashboard');
    }
}

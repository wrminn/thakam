<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $video = Slide::active()
            ->where('slide_menu', 69)
           ->where('slide_display', "A")
            ->first();

        $SlideMenu70 = Slide::active()
            ->where('slide_menu', 70)
            ->where('slide_display', "A")
             ->orderBy('slide_id', 'desc')
            ->limit(3)
            ->get();

        return view('home', compact('video','SlideMenu70'));
    }
}

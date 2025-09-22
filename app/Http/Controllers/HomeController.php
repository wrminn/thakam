<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Slide;
use App\Models\Texteditor;

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

        $activity = DB::table('texteditor')
            ->leftJoin('texteditor_detail', 'texteditor.texteditor_id', '=', 'texteditor_detail.texteditor_id')
            ->where('texteditor.texteditor_menu', 51)
            ->where('texteditor.texteditor_display', "A")
            ->orderBy('texteditor.texteditor_date_show', 'desc')
            ->orderBy('texteditor.texteditor_id', 'desc')
            ->limit(6)
            ->get();

        return view('home', compact('video', 'SlideMenu70', 'activity'));
    }
}

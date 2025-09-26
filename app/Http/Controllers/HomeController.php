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
            ->orderBy('slide_id', 'desc')
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

        $SlideMenu8 = DB::table('texteditor')
            ->where('texteditor_menu', 8)
            ->where('texteditor_display', "A")
            ->orderBy('texteditor_date_show', 'desc')
            ->orderBy('texteditor_id', 'desc')
            ->limit(6)
            ->get();

        $egp = DB::table('texteditor')
            ->where('texteditor_menu', 8)
            ->where('texteditor_display', "A")
            ->orderBy('texteditor_date_show', 'desc')
            ->orderBy('texteditor_id', 'desc')
            ->limit(6)
            ->get();

        $listMenu48 = DB::table('texteditor')
            ->where('texteditor_menu', 48)
            ->where('texteditor_display', "A")
            ->orderBy('texteditor_date_show', 'desc')
            ->orderBy('texteditor_id', 'desc')
            ->limit(6)
            ->get();

        $listMenu49 = DB::table('texteditor')
            ->where('texteditor_menu', 49)
            ->where('texteditor_display', "A")
            ->orderBy('texteditor_date_show', 'desc')
            ->orderBy('texteditor_id', 'desc')
            ->limit(6)
            ->get();

        $listMenu50 = DB::table('texteditor')
            ->where('texteditor_menu', 50)
            ->where('texteditor_display', "A")
            ->orderBy('texteditor_date_show', 'desc')
            ->orderBy('texteditor_id', 'desc')
            ->limit(6)
            ->get();

        $Vote = $list = DB::table('vote')
            ->where('vote_display', "A")
            ->orderBy('vote_id', 'desc')
            ->select([
                'vote_id as id',
                'vote_option as topic',
                'vote_count as count',
            ])
            ->get();


        return view('home', compact('video', 'SlideMenu70', 'activity', 'SlideMenu8', 'egp', 'listMenu48', 'listMenu49', 'listMenu50', 'Vote'));
    }

    public function save(Request $request)
    {
        $vote = $request->input('vote');

        // บันทึกลง DB

        DB::table('vote')
            ->where('vote_id', $vote)
            ->increment('vote_count');

        return response()->json(['status' => 'success']);
    }
}

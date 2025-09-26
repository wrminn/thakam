<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        $listMenu52 = DB::table('texteditor')
            ->leftJoin('texteditor_detail', 'texteditor.texteditor_id', '=', 'texteditor_detail.texteditor_id')
            ->where('texteditor.texteditor_menu', 52)
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

        $elibrary = DB::table('elibrary')
            ->where('elibrary_menu', 78)
            ->where('elibrary_display', "A")
            ->orderBy('elibrary_id', 'desc')
            ->limit(3)
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

        $now = Carbon::now();

        $stats = [
            '2min' => DB::table('visits')
                ->where('visits_visited_at', '>=', $now->copy()->subMinutes(2))
                ->count(),

            'today' => DB::table('visits')
                ->whereDate('visits_visited_at', $now->toDateString())
                ->count(),

            'weekly' => DB::table('visits')
                ->whereBetween('visits_visited_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])
                ->count(),

            'monthly' => DB::table('visits')
                ->whereYear('visits_visited_at', $now->year)
                ->whereMonth('visits_visited_at', $now->month)
                ->count(),

            'yearly' => DB::table('visits')
                ->whereYear('visits_visited_at', $now->year)
                ->count(),

            'total' => DB::table('visits')->count(),
        ];

        return view('home', compact('video', 'SlideMenu70', 'activity', 'listMenu52', 'SlideMenu8', 'egp', 'listMenu48', 'listMenu49', 'listMenu50', 'Vote', 'stats','elibrary'));
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

    public function stats()
    {
        $now = Carbon::now();
        $now = Carbon::now('Asia/Bangkok');

        return response()->json([
            'yearly' => DB::table('visits')
                ->whereYear('visits_visited_at', $now->year)
                ->count(),

            'monthly' => DB::table('visits')
                ->whereYear('visits_visited_at', $now->year)
                ->whereMonth('visits_visited_at', $now->month)
                ->count(),

            'weekly' => DB::table('visits')
                ->whereBetween('visits_visited_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])
                ->count(),

            'today' => DB::table('visits')
                ->whereDate('visits_visited_at', $now->toDateString())
                ->count(),

            'online' => DB::table('visits')
                ->where('visits_visited_at', '>=', $now->subMinutes(5))
                ->count(),
        ]);
    }
}

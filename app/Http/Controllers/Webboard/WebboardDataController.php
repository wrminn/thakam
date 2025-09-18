<?php

namespace App\Http\Controllers\Webboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

use App\Models\Webboard;

class WebboardDataController extends Controller
{

    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    public function SelectDate()
    {
        $timestamp = time();
        $thai_months = [
            1 => "มกราคม",
            2 => "กุมภาพันธ์",
            3 => "มีนาคม",
            4 => "เมษายน",
            5 => "พฤษภาคม",
            6 => "มิถุนายน",
            7 => "กรกฎาคม",
            8 => "สิงหาคม",
            9 => "กันยายน",
            10 => "ตุลาคม",
            11 => "พฤศจิกายน",
            12 => "ธันวาคม"
        ];
        $day = date('j', $timestamp);
        $month = date('n', $timestamp);
        $year = date('Y', $timestamp) + 543;

        $thai_date = $day . " " . $thai_months[$month] . " " . $year;
        return $thai_date;
    }

    public function webboard($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('threads')
            ->where('threads_status', 'O')
            ->get();

        foreach ($list as $item) {


            if (Schema::hasTable('posts')) {
                $count = DB::table('posts')
                    ->where('threads_id', $item->threads_id)
                    ->where('posts_status', 'O')
                    ->count();

                $item->form_count = $count; // เก็บค่า count ไว้ใน list
            } else {
                $item->form_count = 0;
            }
        }

        return view('data.webboard.index', compact('title', 'menuId', 'list'));
    }

    public function Thread($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        return view('data.webboard.newthread', compact('title', 'menuId'));
    }

    public function ThreadInsert(Request $request, $menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
        ]);

        $Date = $this->SelectDate();

        $id = DB::table('threads')->insertGetId([
            'threads_title' => $validated['title'],
            'threads_content' => $validated['content'],
            'threads_name' => $validated['name'],
            'threads_email' => $validated['email'],
            'threads_ip' => $request->ip(),
            'threads_date_insert' => now(),
            'threads_date_show' => $Date
        ]);

        return redirect('/webboard/menu/' . $menuId)->with('success', 'ตั้งกระทู้สำเร็จ');
    }

    public function getThreaddetail($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('threads')
            ->where('threads_id', $id)
            ->first();

        $detail = DB::table('posts')
            ->where('threads_id', $id)
            ->where('posts_status', "O")
            ->get();

        $cookieKey = "viewed_event_" . $id;
        $alreadyViewed = request()->cookie($cookieKey);

        if (!$alreadyViewed) {
            DB::table('threads')->where('threads_id', $id)
                ->update([
                    'threads_view' => $list->threads_view + 1,
                ]);

            cookie()->queue(cookie($cookieKey, true, 60 * 24)); // 60*24 = 1 วัน
        }



        return view('data.webboard.threaddetail', compact('title', 'menuId', 'list', 'detail', 'id'));
    }

    public function PostsInsert(Request $request, $menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $validated = $request->validate([
            'content' => 'required|string',
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
        ]);

        $Date = $this->SelectDate();

        $id = DB::table('posts')->insertGetId([
            'threads_id' => $id,
            'posts_content' => $validated['content'],
            'posts_name' => $validated['name'],
            'posts_email' => $validated['email'],
            'posts_ip' => $request->ip(),
            'posts_date_insert' => now(),
            'posts_date_show' => $Date
        ]);

        return redirect('/threaddetail/menu/' . $menuId . '/id/' . $id)->with('success', 'ตั้งกระทู้สำเร็จ');
    }
}

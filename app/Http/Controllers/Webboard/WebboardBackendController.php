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

class WebboardBackendController extends Controller
{

    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    function listthread($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('threads')
            ->whereIn('threads_status', ['P', 'O'])
            ->paginate(20);

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.webboard.list', compact('title', 'list', 'menuId', 'startIndex'));
    }

    public function threaddetail($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('threads')
            ->where('threads_id', $id)
            ->first();

        $detail = DB::table('posts')
            ->where('threads_id', $id)
            ->get();
        return view('backend.webboard.detail', compact('title', 'menuId', 'list', 'detail', 'id'));
    }

    function openthreda($menuId, $id)
    {
        DB::table('threads')->where('threads_id', $id)
            ->update([
                'threads_status' => 'O',
            ]);
        return redirect('backend/webboard/menu/' . $menuId)->with('success', 'เปิดการมองเห็นกระทู้สำเร็จ');
    }

    function hidethread($menuId, $id)
    {
        DB::table('threads')->where('threads_id', $id)
            ->update([
                'threads_status' => 'P',
            ]);
        return redirect('backend/webboard/menu/' . $menuId)->with('success', 'ปิดการมองเห็นกระทู้สำเร็จ');
    }

    function deletethread($menuId, $id)
    {
        DB::table('threads')->where('threads_id', $id)
            ->update([
                'threads_status' => 'D',
            ]);
        return redirect('backend/webboard/menu/' . $menuId)->with('success', 'ลบกระทู้สำเร็จ');
    }

    function openpost($menuId, $id)
    {
        DB::table('posts')->where('posts_id', $id)
            ->update([
                'posts_status' => 'O',
            ]);
        return redirect('backend/threaddetail/menu/' . $menuId . '/id/' . $id)->with('success', 'เปิดการมองเห็นสำเร็จ');
    }

    function hidepost($menuId, $id)
    {
        DB::table('posts')->where('posts_id', $id)
            ->update([
                'posts_status' => 'P',
            ]);
        return redirect('backend/threaddetail/menu/' . $menuId . '/id/' . $id)->with('success', 'ปิดการมองเห็นสำเร็จ');
    }

    function deletepost($menuId, $id)
    {
        DB::table('posts')->where('posts_id', $id)
            ->update([
                'posts_status' => 'D',
            ]);
        return redirect('backend/threaddetail/menu/' . $menuId . '/id/' . $id)->with('success', 'ลบสำเร็จ');
    }
}

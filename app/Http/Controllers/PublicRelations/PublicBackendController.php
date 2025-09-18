<?php

namespace App\Http\Controllers\PublicRelations;

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

class PublicBackendController extends Controller
{

    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    function listsatisfaction($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('satisfaction')->paginate(20);

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.satisfaction.list', compact('title', 'list', 'menuId', 'startIndex'));
    }
    public function satisfactiondetail($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('satisfaction')
            ->where('satisfaction_id', $id)
            ->first();

       
        return view('backend.satisfaction.detail', compact('title', 'menuId', 'list', 'id'));
    }

    function popup($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('popup')
            ->where('popup_display', "A")
            ->orderBy('popup_id', 'desc')
            ->first();

        return view('backend.satisfaction.popup', compact('title', 'list', 'menuId'));
    }

    function popupInsert(Request $request, $menuId, $category = "")
    {

        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('popup')
            ->where('popup_display', "A")
            ->orderBy('popup_id', 'desc')
            ->first();

        if (!empty($list)) {

            DB::table('popup')->where('popup_id', $list->popup_id)
                ->update([
                    'popup_display' => "D",
                ]);
        }

        if ($request->hasFile('file')) {

            $id_file = $list ? $list->popup_id + 1 : 1;


            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id_file}_popup_{$timestamp}.{$ext}";
            $path = $file->storeAs($folder, $filename, 'public');

            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) {
                chmod($fullPath, 0644);
            }

            $publicStoragePath = public_path('storage/' . $path);
            if (!file_exists(dirname($publicStoragePath))) {
                mkdir(dirname($publicStoragePath), 0775, true);
            }
            copy($fullPath, $publicStoragePath);
            chmod($publicStoragePath, 0644);

            $popup = [
                'popup_path' => $path,
            ];
            DB::table('popup')->insert($popup);
        }

        return redirect('backend/popup/menu/' . $menuId);
    }

    public function fetchEvents($menu)
    {
        $list = DB::table('events')
            ->where('events_display', "A")
            ->get();

        $events = $list->map(function ($item) {
            return [
                'id' => $item->events_id,
                'title' => $item->events_name,
                'start' => $item->events_start,
                'end' => $item->events_end,
                'description' => $item->events_description,
                'color' => $item->events_color ?? '#3788d8'
            ];
        });
        return response()->json($events);
    }

    function eventcalendar($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;



        return view('backend.satisfaction.eventcalendar', compact('title', 'menuId'));
    }

    public function eventcalendarInsert(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end'   => 'nullable|date|after_or_equal:start',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20',
        ]);
        $events = [
            'events_name' => $validated['title'],
            'events_start' => $validated['start'],
            'events_end' => $validated['end'] ?? null,
            'events_description' => $validated['description'] ?? null,
            'events_color' => $validated['color'] ?? '#3788d8',
        ];

        DB::table('events')->insertGetId($events);

        return redirect()->back()->with('success', 'เพิ่มกิจกรรมเรียบร้อย');
    }

    public function eventcalendarId($menu, $id)
    {
        // $event = Event::where('menu', $menu)->findOrFail($id);
        $event = DB::table('events')
            ->where('events_id', $id)
            ->first();

        return view('backend.eventcalendar_edit', compact('event', 'menu'));
    }

    public function eventcalendarUpdate(Request $request, $menu, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:20'
        ]);


        $event = DB::table('events')->where('events_id', $id)->first();

        if (!$event) {
            abort(404);
        }

        // อัปเดต
        DB::table('events')->where('events_id', $id)->update([
            'events_name' => $validated['title'],
            'events_start' => $validated['start'],
            'events_end' => $validated['end'] ?? $validated['start'],
            'events_description' => $validated['description'] ?? null,
            'events_color' => $validated['color'] ?? '#3788d8',
        ]);

        return redirect()->back()->with('success', 'แก้ไขกิจกรรมเรียบร้อย');
    }

    public function eventcalendarDelete($menu, $id)
    {
        DB::table('events')->where('events_id', $id)->update([
            'events_display' => 'D'
        ]);

        return redirect()->back()->with('success', 'ลบกิจกรรมเรียบร้อย');
    }

    function selectvote($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;


        $list = DB::table('vote')
            // ->where('vote_display', "A")
            ->paginate(20);
        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;
        return view('backend.webboard.vote', compact('title', 'list', 'menuId', 'startIndex'));
    }

    function selectvoterone($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('vote')
            ->where('vote_id', $id)
            ->first();

        return view('backend.webboard.editvote', compact('title', 'list', 'menuId', 'id'));
    }

    function editvote(Request $request, $menuId, $id, $category = "")
    {

        DB::table('vote')
            ->where('vote_id', $id)
            ->update([
                'vote_option' => $request->title,
            ]);

        return redirect('backend/vote/menu/' . $menuId);
    }

    function openvote($menuId, $id)
    {
        DB::table('vote')->where('vote_id', $id)
            ->update([
                'vote_display' => 'A',
            ]);
        // return redirect('backend/webboard/menu/' . $menuId);
        return redirect('backend/vote/menu/' . $menuId)->with('success', 'เปิดการมองเห็นสำเร็จ');
    }

    function hidevote($menuId, $id)
    {
        DB::table('vote')->where('vote_id', $id)
            ->update([
                'vote_display' => 'D',
            ]);
        return redirect('backend/vote/menu/' . $menuId)->with('success', 'ปิดการมองเห็นสำเร็จ');
    }
}

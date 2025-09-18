<?php

namespace App\Http\Controllers\Slide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Slide;

class SlideBackendController extends Controller
{
    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    function selectslide($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Slide::active()
            ->where('slide_menu', $menuId)
            ->paginate(20);
        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.slide.slide', compact('title', 'list', 'menuId', 'startIndex'));
    }

    function addslide($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        return view('backend.slide.addslide', compact('title', 'menuId'));
    }

    function insertslide(Request $request, $menuId, $category = "")
    {

        $id = DB::table('slide')->insertGetId([
            'slide_title' => $request->topic,
            'slide_link' => $request->link,
            'slide_menu' => $menuId,
            'slide_date_insert' => now(),
        ]);


        if ($request->hasFile('topic_picture')) {
            $file = $request->file('topic_picture');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_slide_{$timestamp}.{$ext}";
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

            DB::table('slide')->where('slide_id', $id)
                ->update([
                    'slide_path' => $path
                ]);
        }

        return redirect('backend/slide/menu/' . $menuId);
    }

    function selectslideone($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('slide')
            ->where('slide_id', $id)
            ->where('slide_display', "A")
            ->first();

        return view('backend.slide.editslide', compact('title', 'list', 'menuId', 'id'));
    }

    function editslide(Request $request, $menuId, $id, $category = "")
    {

        DB::table('slide')
            ->where('slide_id', $id)
            ->update([
                'slide_title' => $request->title,
                'slide_link' => $request->link,
                'slide_date_update' => now()
            ]);

        if ($request->hasFile('topic_picture')) {
            $file = $request->file('topic_picture');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_slide_{$timestamp}.{$ext}";
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

            DB::table('slide')->where('slide_id', $id)
                ->update([
                    'slide_path' => $path
                ]);
        }
        return redirect('backend/slide/menu/' . $menuId);
    }

    function deleteslide($menuId, $id)
    {

        DB::table('slide')->where('slide_id', $id)
            ->update([
                'slide_display' => 'D',
                'slide_date_update' => now()
            ]);
        return redirect('backend/slide/menu/' . $menuId);
    }

    function insertslidevideo(Request $request, $menuId, $category = "")
    {

        $id = DB::table('slide')->insertGetId([
            'slide_title' => $request->topic,
            'slide_menu' => $menuId,
            'slide_date_insert' => now(),
        ]);


        if ($request->input('inputType') === 'V' && $request->hasFile('video')) {
            $file = $request->file('video');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}";
            $filename = "{$id}_video_{$timestamp}.{$ext}";
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

            DB::table('slide')->where('slide_id', $id)
                ->update([
                    'slide_path' => $path,
                    'slide_link' => null, // reset link ถ้าเลือก video
                    'slide_type' => 'V'
                ]);
        } elseif ($request->input('inputType') === 'L' && $request->filled('link')) {
            DB::table('slide')->where('slide_id', $id)
                ->update([
                    'slide_link' => $request->input('link'),
                    'slide_path' => null, // reset video ถ้าเลือก link
                    'slide_type' => 'L'
                ]);
        }

        return redirect('backend/slide/menu/' . $menuId);
    }

    function editslidevideo(Request $request, $menuId, $id, $category = "")
    {

        DB::table('slide')
            ->where('slide_id', $id)
            ->update([
                'slide_title' => $request->title,
                'slide_date_update' => now()
            ]);

        $list = DB::table('slide')
            ->where('slide_id', $id)
            ->first();

        if ($list->slide_type == "L") {
            DB::table('slide')->where('slide_id', $id)
                ->update([
                    'slide_link' => $request->input('link'),
                ]);
        } elseif ($list->slide_type == "V") {
            $file = $request->file('video');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}";
            $filename = "{$id}_video_{$timestamp}.{$ext}";
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

            DB::table('slide')->where('slide_id', $id)
                ->update([
                    'slide_path' => $path,
                ]);
        }



        return redirect('backend/slide/menu/' . $menuId);
    }
}

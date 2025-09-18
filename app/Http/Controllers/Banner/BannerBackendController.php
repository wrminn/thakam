<?php

namespace App\Http\Controllers\Banner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Banner;

class BannerBackendController extends Controller
{

    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    function SelectBanner($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Banner::active()
            ->where('Banner_menu', $menuId)
            ->paginate(20);
        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.banner.banner', compact('title', 'list', 'menuId', 'startIndex'));
    }

    function AddBanner($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        return view('backend.banner.addbanner', compact('title', 'menuId'));
    }

    function InsertBanner(Request $request, $menuId, $category = "")
    {

        $id = DB::table('banner')->insertGetId([
            'banner_title' => $request->topic,
            'banner_link' => $request->link,
            'banner_menu' => $menuId,
            'banner_date_insert' => now(),
        ]);

        if ($request->hasFile('topic_picture')) {

            $file = $request->file('topic_picture');
            $ext = $file->getClientOriginalExtension();

            // สร้างชื่อกลาง
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_banner_{$timestamp}.{$ext}";

            // บันทึกไฟล์เข้า storage/app/public/content/{menuId}
            $path = $file->storeAs($folder, $filename, 'public');

            // ตั้ง permission ให้ไฟล์ใหม่
            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) {
                chmod($fullPath, 0644);
            }

            // สำหรับ host ที่ symlink ไม่ทำงาน → copy ไป public/storage
            $publicStoragePath = public_path('storage/' . $path);
            if (!file_exists(dirname($publicStoragePath))) {
                mkdir(dirname($publicStoragePath), 0775, true);
            }
            copy($fullPath, $publicStoragePath);
            chmod($publicStoragePath, 0644);

            // บันทึก path ลง database
            DB::table('banner')->where('banner_id', $id)
                ->update([
                    'banner_topic_picture' => $path
                ]);
        }

        return redirect('backend/banner/menu/' . $menuId);
    }

    function SelectBannerOne($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('banner')
            ->where('banner_id', $id)
            ->where('banner_display', "A")
            ->first();

        return view('backend.banner.editbanner', compact('title', 'list', 'menuId', 'id'));
    }

    function EditBanner(Request $request, $menuId, $id, $category = "")
    {

        DB::table('banner')
            ->where('banner_id', $id)
            ->update([
                'banner_title' => $request->title,
                'banner_link' => $request->link,
                'banner_date_update' => now()
            ]);

 
        if ($request->hasFile('topic_picture')) {

            $file = $request->file('topic_picture');
            $ext = $file->getClientOriginalExtension();

            // สร้างชื่อกลาง
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_banner_{$timestamp}.{$ext}";

            // บันทึกไฟล์เข้า storage/app/public/content/{menuId}
            $path = $file->storeAs($folder, $filename, 'public');

            // ตั้ง permission ให้ไฟล์ใหม่
            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) {
                chmod($fullPath, 0644);
            }

            // สำหรับ host ที่ symlink ไม่ทำงาน → copy ไป public/storage
            $publicStoragePath = public_path('storage/' . $path);
            if (!file_exists(dirname($publicStoragePath))) {
                mkdir(dirname($publicStoragePath), 0775, true);
            }
            copy($fullPath, $publicStoragePath);
            chmod($publicStoragePath, 0644);

            // บันทึก path ลง database
            DB::table('banner')->where('banner_id', $id)
                ->update([
                    'banner_topic_picture' => $path
                ]);
        }

        return redirect('backend/banner/menu/' . $menuId);
    }

    function DeleteBanner($menuId, $id)
    {

        DB::table('banner')->where('banner_id', $id)
            ->update([
                'banner_display' => 'D',
                'banner_date_update' => now()
            ]);
        return redirect('backend/banner/menu/' . $menuId);
    }
}

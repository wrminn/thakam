<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Personnel;

class PersonnelBackendController extends Controller
{

    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    function SelectDataPersonnel($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Personnel::active()
            ->where('personnel_menu', $menuId)
            // ->orderBy('personnel_seq', 'asc')
            ->orderByRaw('CAST(personnel_seq AS UNSIGNED) ASC')
            ->paginate(50);
        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.personnel.personnel', compact('title', 'list', 'menuId', 'startIndex'));
    }

    function add($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        return view('backend.personnel.addpersonnel', compact('title', 'menuId'));
    }

    function insertpersonnel(Request $request, $menuId, $category = "")
    {

        $latestRecord = Personnel::where('personnel_menu', $menuId)
            ->where('personnel_display', '!=', 'D')
            ->latest('personnel_seq')
            ->first();

        $seq = ($latestRecord['personnel_seq'] ?? 0) + 1;

        $id = DB::table('personnel')->insertGetId([
            'personnel_name' => $request->name,
            'personnel_position' => $request->position,
            'personnel_tel' => $request->tel,
            'personnel_menu' => $menuId,
            'personnel_date_insert' => now(),
            'personnel_seq' => $seq,
        ]);

        if ($request->hasFile('personnel_img')) {

            $file = $request->file('personnel_img');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_personnel_{$timestamp}.{$ext}";
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

            DB::table('personnel')->where('personnel_id', $id)
                ->update([
                    'personnel_path' => $path
                ]);
        }

        return redirect('backend/personnel/menu/' . $menuId);
    }

    function selectpersonnelid(Request $request, $menuId, $id, $category = "")
    {

        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('personnel')
            ->where('personnel_id', $id)
            ->where('personnel_display', "A")
            ->first();

        return view('backend.personnel.editpersonnel', compact('title', 'list', 'menuId', 'id'));
    }

    function editpersonnel(Request $request, $menuId, $id, $category = "")
    {

        DB::table('personnel')
            ->where('personnel_id', $id)
            ->update([
                'personnel_name' => $request->name,
                'personnel_position' => $request->position,
                'personnel_tel' => $request->tel,
                'personnel_date_update' => now()
            ]);

        if ($request->hasFile('personnel_img')) {

            $file = $request->file('personnel_img');
            $ext = $file->getClientOriginalExtension();

            // สร้างชื่อกลาง
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_personnel_{$timestamp}.{$ext}";

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
            DB::table('personnel')->where('personnel_id', $id)
                ->update([
                    'personnel_path' => $path
                ]);
        }

        return redirect('backend/personnel/menu/' . $menuId);
    }

    function deletepersonnel($menuId, $id)
    {

        DB::table('personnel')->where('personnel_id', $id)
            ->update([
                'personnel_display' => 'D',
                'personnel_date_update' => now()
            ]);
        return redirect('backend/personnel/menu/' . $menuId);
    }

    function selectdataseq($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Personnel::active()
            ->where('personnel_menu', $menuId)
            ->orderBy('personnel_seq', 'asc')
            ->paginate(50);
        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.personnel.personnelseq', compact('title', 'list', 'menuId', 'startIndex'));
    }

    function updateseqpersonnel(Request $request, $menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $data = $request->input('users');

        foreach ($data as $item) {

            Personnel::where('personnel_menu', $menuId)
                ->where('personnel_id', $item['id'])
                ->update(
                    [
                        'personnel_seq' => $item['seq'],
                        'personnel_date_update' => now()
                    ]
                );
        }

        return response()->json(['status' => 'success']);
        // return view('backend.personnel.addpersonnel', compact('title', 'menuId'));
    }
}

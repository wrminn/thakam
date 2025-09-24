<?php

namespace App\Http\Controllers\Directory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use App\Services\MyService;

use App\Models\Texteditor;
use App\Models\Category;

class DirectoryBackendController extends Controller
{

    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }
    
    private function uploadFiles($files, $menuId, $id, $type = 'list', $single = false)
    {
        $uploadedPaths = [];

        // ถ้าเป็นไฟล์เดี่ยว ให้ wrap เป็น array
        $files = $single ? [$files] : $files;

        foreach ($files as $key => $file) {
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');
            $seq = $key + 1;

            $folder = "content/{$menuId}";
            $filename = "{$id}_{$type}_{$seq}_{$timestamp}.{$ext}";
            $path = $file->storeAs($folder, $filename, 'public');

            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) chmod($fullPath, 0644);

            $publicStoragePath = public_path('storage/' . $path);
            if (!file_exists(dirname($publicStoragePath))) {
                mkdir(dirname($publicStoragePath), 0775, true);
            }
            copy($fullPath, $publicStoragePath);
            chmod($publicStoragePath, 0644);

            $uploadedPaths[] = [
                'seq'  => $seq,
                'name' => $file->getClientOriginalName(),
                'file' => $path
            ];
        }

        return $uploadedPaths;
    }

    function SelectDirectory($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);

        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Texteditor::active()
            ->where('texteditor_menu', $menuId)
            ->orderBy('texteditor_date_show', 'desc')
            ->orderBy('texteditor_id', 'desc')
            ->paginate(20);

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.directory.list', compact('title', 'menuId', 'list', 'startIndex'));
    }

    function FormAdd($menuId, $cateID = null)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        return view('backend.directory.add', compact('title', 'menuId', 'cateID'));
    }

    function Insert(Request $request, $menuId, $cateID = "")
    {

        $id = DB::table('texteditor')->insertGetId([
            'texteditor_title' => $request->topic,
            'texteditor_date_show' => $request->date,
            'texteditor_category_id' => $cateID ? $cateID : 0,
            'texteditor_menu' => $menuId,
            'texteditor_date_insert' => now(),
            'texteditor_user_id_insert' => session()->get('user_id'),
        ]);

        if (!empty($request->detail)) {
            $data_texteditor_detail = [
                'texteditor_detail' => $request->detail,
                'texteditor_id' => $id,
                'texteditor_detail_seq' => 1,
            ];
            DB::table('texteditor_detail')->insert($data_texteditor_detail);
        }

        if ($request->hasFile('topic_picture')) {
            $uploaded = $this->uploadFiles($request->file('topic_picture'), $menuId, $id, 'topic', true);

            DB::table('texteditor')->where('texteditor_id', $id)
                ->update(['texteditor_topic_picture' => $uploaded[0]['file']]);
        }

        $allUploads = [];

        if ($request->hasFile('files')) {
            $allUploads = array_merge($allUploads, $this->uploadFiles($request->file('files'), $menuId, $id, 'list'));
        }

        if ($request->hasFile('images')) {
            $allUploads = array_merge($allUploads, $this->uploadFiles($request->file('images'), $menuId, $id, 'list'));
        }

        $insertData = [];
        foreach ($allUploads as $file) {
            $insertData[] = [
                'texteditor_id' => $id,
                'texteditor_upload_seq' => $file['seq'],
                'texteditor_upload_name' => $file['name'],
                'texteditor_upload_file' => $file['file']
            ];
        }

        if (!empty($insertData)) {
            DB::table('texteditor_upload')->insert($insertData);
        }

        if (empty($cateID)) {
            return redirect('backend/directory/menu/' . $menuId);
        } else {
            return redirect('backend/directory/menu/' . $menuId . '/cate/' . $cateID);
        }
    }

    function FormEdit($menuId, $id, $cateID = null)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('texteditor')
            ->leftJoin('texteditor_detail', 'texteditor.texteditor_id', '=', 'texteditor_detail.texteditor_id')
            ->where('texteditor.texteditor_id', $id)
            ->first();

        if (!empty($list)) {
            $file = DB::table('texteditor_upload')
                ->where('texteditor_id', $id)
                ->where('texteditor_display', "A")
                ->get()->toArray();
        }
        return view('backend.directory.edit', compact('title', 'menuId', 'list', 'file', 'id', 'cateID'));
    }

    function Edit(Request $request, $menuId, $id, $cateID = "")
    {

        DB::table('texteditor')
            ->where('texteditor_id', $id)
            ->update([
                'texteditor_title' => $request->topic,
                'texteditor_date_show' => $request->date,
                'texteditor_date_update' => now(),
                'texteditor_user_id_update' => session()->get('users_id'),
            ]);

        $list_texteditor = DB::table('texteditor_detail')
            ->where('texteditor_id', $id)
            ->first();

        if (!empty($list_texteditor->texteditor_id)) {
            DB::table('texteditor_detail')->where('texteditor_id', $id)
                ->update([
                    'texteditor_detail' => $request->detail
                ]);
        } else {
            if (!empty($request->detail)) {
                $data_texteditor_detail = [
                    'texteditor_detail' => $request->detail,
                    'texteditor_id' => $id,
                    'texteditor_detail_seq' => '1',
                ];
                DB::table('texteditor_detail')->insert($data_texteditor_detail);
            }
        }


        if ($request->hasFile('topic_picture')) {
            $uploaded = $this->uploadFiles($request->file('topic_picture'), $menuId, $id, 'topic', true);

            DB::table('texteditor')->where('texteditor_id', $id)
                ->update(['texteditor_topic_picture' => $uploaded[0]['file']]);
        }

        $allUploads = [];

        if ($request->hasFile('files')) {
            $allUploads = array_merge($allUploads, $this->uploadFiles($request->file('files'), $menuId, $id, 'list'));
        }

        if ($request->hasFile('images')) {
            $allUploads = array_merge($allUploads, $this->uploadFiles($request->file('images'), $menuId, $id, 'list'));
        }

        $insertData = [];
        foreach ($allUploads as $file) {
            $insertData[] = [
                'texteditor_id' => $id,
                'texteditor_upload_seq' => $file['seq'],
                'texteditor_upload_name' => $file['name'],
                'texteditor_upload_file' => $file['file']
            ];
        }

        if (!empty($insertData)) {
            DB::table('texteditor_upload')->insert($insertData);
        }

        if (empty($cateID)) {
            return redirect('backend/directory/menu/' . $menuId);
        } else {
            return redirect('backend/directory/menu/' . $menuId . '/cate/' . $cateID);
        }
    }

    function Delete($menuId, $id)
    {
        DB::table('texteditor')->where('texteditor_id', $id)
            ->update([
                'texteditor_display' => 'D',
                'texteditor_date_update' => now()
            ]);
        return redirect('backend/directory/menu/' . $menuId);
    }

    function DeleteOneFile($menuId, $id, $idFile, $cateID = "")
    {
        DB::table('texteditor_upload')->where('texteditor_upload_id', $idFile)
            ->update([
                'texteditor_display' => 'D',
            ]);

        if (empty($cateID)) {
            return redirect('backend/updateDirectory/menu/' . $menuId . '/id/' . $id);
        } else {
            return redirect('backend/updateDirectory/menu/' . $menuId . '/id/' . $id . '/cate/' . $cateID);
        }
    }

    public function toggleFileVisibility(Request $request)
    {
        $idfile = $request->idfile;


        $file = DB::table('texteditor_upload')->where('texteditor_upload_id', $idfile)->first();

        if (!$file) {
            return response()->json(['success' => false, 'message' => 'ไม่พบไฟล์']);
        }

        $newStatus = $file->texteditor_show === 'Y' ? 'N' : 'Y';

        DB::table('texteditor_upload')
            ->where('texteditor_upload_id', $idfile)
            ->update(['texteditor_show' => $newStatus]);

        return response()->json([
            'success' => true,
            'status'  => $newStatus,
            'message' => $newStatus === 'Y' ? 'ไฟล์ถูกเปิดการมองเห็น' : 'ไฟล์ถูกปิดการมองเห็น'
        ]);
    }

    //category
    function SelectCategory($menuId, $cateID = null)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $cate = Category::active()
            ->where('categories_menu', $menuId)
            ->paginate(20);

        $list = Texteditor::active()
            ->where('texteditor_menu', $menuId)
            ->where('texteditor_category_id', $cateID)
            ->orderBy('texteditor_id', 'desc')
            ->paginate(20);

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;
        return view('backend.directory.listcategory', compact('title', 'list', 'cate', 'menuId', 'cateID', 'startIndex'));
    }

    function FormAddCategory($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        return view('backend.directory.addcategory', compact('title', 'menuId'));
    }

    function InsertCategory(Request $request, $menuId)
    {

        $id = DB::table('categories')->insertGetId([
            'categories_name' => $request->name_category,
            'categories_menu' => $menuId,
            'categories_date_insert' => now(),
        ]);

        return redirect('backend/categorylist/menu/' . $menuId);
    }

    function SelectCategorylist($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Category::active()
            ->where('categories_menu', $menuId)
            ->paginate(20);
        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;
        $cateID = 0;

        return view('backend.directory.categorylist', compact('title', 'list', 'menuId', 'cateID', 'startIndex'));
    }

    function FormEditCategory($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        $list = DB::table('categories')
            ->where('categories_id', $id)
            ->first();

        return view('backend.directory.editcategory', compact('title', 'menuId', 'list', 'id'));
    }

    function EditCategory(Request $request, $menuId, $id)
    {
        DB::table('categories')
            ->where('categories_id', $id)
            ->update([
                'categories_name' => $request->categories_name,
                'categories_date_update' => now()
            ]);
        return redirect('backend/categorylist/menu/' . $menuId);
    }

    function DeleteCategoryid($menuId, $id)
    {
        DB::table('categories')->where('categories_id', $id)
            ->update([
                'categories_display' => 'D',
                'categories_date_update' => now()
            ]);
        return redirect('backend/categorylist/menu/' . $menuId);
    }
}

<?php

namespace App\Http\Controllers\Articles;

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

class ArticlesBackendController extends Controller
{

    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    /// เมนูหน้าเดียว

    function SelectArticles($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $file = null;

        $list = DB::table('texteditor')
            ->leftJoin('texteditor_detail', 'texteditor.texteditor_id', '=', 'texteditor_detail.texteditor_id')
            ->where('texteditor.texteditor_menu', $menuId)
            ->where('texteditor_detail.texteditor_display', "A")
            ->select(
                'texteditor.*',
                'texteditor_detail.texteditor_detail_id',
                'texteditor_detail.texteditor_detail_seq',
                'texteditor_detail.texteditor_detail'
            )
            ->first();

        if (!empty($list)) {

            $file = DB::table('texteditor_upload')
                ->where('texteditor_id', $list->texteditor_id)
                ->where('texteditor_display', "A")
                ->get()->toArray();
        }

        return view('backend.articles.texteditor', compact('title', 'menuId', 'list', 'file'));
    }

    function InsertArticles(Request $request, $menuId, $category = "")
    {

        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('texteditor')
            ->where('texteditor_menu', $menuId)
            ->orderBy('texteditor_id', 'desc')
            ->first();


        if (!empty($list)) {
            $filename = null;

            DB::table('texteditor')->where('texteditor_id', $list->texteditor_id)
                ->update([
                    'texteditor_date_update' => now()
                ]);

            $list_texteditor = DB::table('texteditor_detail')
                ->where('texteditor_id', $list->texteditor_id)
                ->where('texteditor_display', "A")
                ->first();

            if (trim(strip_tags($request->detail)) === '') {

                DB::table('texteditor_detail')->where('texteditor_id', $list_texteditor->texteditor_id)
                    ->update([
                        'texteditor_display' => "D"
                    ]);
            } else {

                if (!empty($list_texteditor->texteditor_id)) {

                    DB::table('texteditor_detail')->where('texteditor_id', $list_texteditor->texteditor_id)
                        ->update([
                            'texteditor_detail' => $request->detail
                        ]);
                } else {
                    $data_texteditor_detail = [
                        'texteditor_detail' => $request->detail,
                        'texteditor_id' => $list->texteditor_id,
                        'texteditor_detail_seq' => '1',
                    ];
                    DB::table('texteditor_detail')->insert($data_texteditor_detail);
                }
            }
        } else {

            $id = DB::table('texteditor')->insertGetId([
                'texteditor_title' => $title,
                'texteditor_category_id' =>  $category ? $category : 0,
                'texteditor_menu' => $menuId,
                'texteditor_date_insert' => now(),
            ]);

            $data_texteditor_detail = [
                'texteditor_detail' => $request->detail,
                'texteditor_id' => $id,
                'texteditor_detail_seq' => '1',
            ];
            DB::table('texteditor_detail')->insert($data_texteditor_detail);
        }

        if ($request->hasFile('file')) {

            $list_select = DB::table('texteditor')
                ->where('texteditor_menu', $menuId)
                ->where('texteditor_display', 'A')
                ->first();

            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$list_select->texteditor_id}_Articles_{$timestamp}.{$ext}";
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

            $data_texteditor_upload = [
                'texteditor_id' => $list_select->texteditor_id,
                'texteditor_upload_seq' => "1",
                'texteditor_upload_name' => $file->getClientOriginalName(),
                'texteditor_upload_file' => $path,
            ];
            DB::table('texteditor_upload')->insert($data_texteditor_upload);
        }

        return redirect('backend/articles/menu/' . $menuId);
    }

    function DeleteAarticlesfile($menuId, $id)
    {
        DB::table('texteditor_upload')->where('texteditor_upload_id', $id)
            ->update([
                'texteditor_display' => 'D',
            ]);
        return redirect('backend/articles/menu/' . $menuId);
    }

    function selectslide($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('elibrary')
            ->where('elibrary_menu', $menuId)
            ->orderBy('elibrary_id', 'desc')
            ->paginate(20);
        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.elibrary.elibrary', compact('title', 'list', 'menuId', 'startIndex'));
    }

    function addslide($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        return view('backend.elibrary.addelibrary', compact('title', 'menuId'));
    }

    function insertslide(Request $request, $menuId, $category = "")
    {

        $id = DB::table('elibrary')->insertGetId([
            'elibrary_title' => $request->topic,
            'elibrary_menu' => $menuId,
            'elibrary_date_insert' => now(),
        ]);


        if ($request->hasFile('topic_picture')) {
            $file = $request->file('topic_picture');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_elibrary_{$timestamp}.{$ext}";
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

            DB::table('elibrary')->where('elibrary_id', $id)
                ->update([
                    'elibrary_path_page' => $path,
                ]);
        }

        if ($request->hasFile('file_pdf')) {
            $file_pdf = $request->file('file_pdf');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_elibrary_pdf_{$timestamp}.{$ext}";
            $path = $file_pdf->storeAs($folder, $filename, 'public');

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

            DB::table('elibrary')->where('elibrary_id', $id)
                ->update([
                    'elibrary_path' => $path,
                    'elibrary_name_file' => $file_pdf->getClientOriginalName()
                ]);
        }

        return redirect('backend/elibrary/menu/' . $menuId);
    }

    function selectslideone($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('elibrary')
            ->where('elibrary_id', $id)
            ->where('elibrary_display', "A")
            ->first();

        return view('backend.elibrary.editelibrary', compact('title', 'list', 'menuId', 'id'));
    }

    function editslide(Request $request, $menuId, $id, $category = "")
    {

        DB::table('elibrary')
            ->where('elibrary_id', $id)
            ->update([
                'elibrary_title' => $request->title,
                'elibrary_date_update' => now()
            ]);

         if ($request->hasFile('topic_picture')) {
            $file = $request->file('topic_picture');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_elibrary_{$timestamp}.{$ext}";
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

            DB::table('elibrary')->where('elibrary_id', $id)
                ->update([
                    'elibrary_path_page' => $path,
                ]);
        }

        if ($request->hasFile('file_pdf')) {
            $file_pdf = $request->file('file_pdf');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_elibrary_pdf_{$timestamp}.{$ext}";
            $path = $file_pdf->storeAs($folder, $filename, 'public');

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

            DB::table('elibrary')->where('elibrary_id', $id)
                ->update([
                    'elibrary_path' => $path,
                    'elibrary_name_file' => $file_pdf->getClientOriginalName()
                ]);
        }

        return redirect('backend/elibrary/menu/' . $menuId);
    }

    function deleteslide($menuId, $id)
    {

        DB::table('elibrary')->where('elibrary_id', $id)
            ->update([
                'elibrary_display' => 'D',
                'elibrary_date_update' => now()
            ]);
        return redirect('backend/elibrary/menu/' . $menuId);
    }
}

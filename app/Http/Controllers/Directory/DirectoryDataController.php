<?php

namespace App\Http\Controllers\Directory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\MyService;

use App\Models\Texteditor;
use App\Models\Category;

class DirectoryDataController extends Controller
{
    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    function SelectDirectoryFront($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => $title, 'url' => route('directory.data', ['menu' => $menuId])] // หน้า current
        ];

        $list = Texteditor::active()
            ->where('texteditor_menu', $menuId)
            ->orderBy('texteditor_date_show', 'desc')
            ->orderBy('texteditor_id', 'desc')
            ->paginate(20);

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('data.directory.list', compact('title', 'menuId', 'list', 'startIndex', 'breadcrumbs'));
    }

    function SelectDirectoryFrontID($menuId, $Id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $file = null;

        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => $title, 'url' => route('directory.data', ['menu' => $menuId])], // หน้า current
        ];

        $list = DB::table('texteditor')
            ->leftJoin('texteditor_detail', 'texteditor.texteditor_id', '=', 'texteditor_detail.texteditor_id')
            ->where('texteditor.texteditor_id', $Id)
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

        return view('data.directory.detail', compact('title', 'menuId', 'list', 'file', 'breadcrumbs'));
    }

    function SelectCategoriesFront($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => $title, 'url' => route('directory.data', ['menu' => $menuId])], // หน้า current
        ];
        $list = Category::active()
            ->where('categories_menu', $menuId)
            ->paginate(20);
        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;
        $cateID = 0;

        return view('data.categories.categories', compact('title', 'list', 'menuId', 'cateID', 'startIndex', 'breadcrumbs'));
    }

    function SelectDirectoryCateFront($menuId, $cateID)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

         $name_cat = Category::active()
            ->where('categories_id', $cateID)
            ->first();

        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => $title, 'url' => route('categories.list', ['menu' => $menuId])], // หน้า current
            ['name' => $name_cat->categories_name, 'url' => route('categories.data', ['menu' => $menuId,'cate' => $cateID])], // หน้า current
        ];

        $list = Texteditor::active()
            ->where('texteditor_menu', $menuId)
            ->where('texteditor_category_id', $cateID)
            ->orderBy('texteditor_date_show', 'desc')
            ->orderBy('texteditor_id', 'desc')
            ->paginate(20);

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('data.categories.list', compact('title', 'menuId', 'cateID', 'list', 'startIndex', 'breadcrumbs'));
    }

    function SelectDirectoryCateFrontID($menuId, $Id, $cateID)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $file = null;

        $name_cat = Category::active()
            ->where('categories_id', $cateID)
            ->first();

        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => $title, 'url' => route('categories.list', ['menu' => $menuId])], // หน้า current
            ['name' => $name_cat->categories_name, 'url' => route('categories.data', ['menu' => $menuId,'cate' => $cateID])], // หน้า current
        ];

        $list = DB::table('texteditor')
            ->leftJoin('texteditor_detail', 'texteditor.texteditor_id', '=', 'texteditor_detail.texteditor_id')
            ->where('texteditor.texteditor_id', $Id)
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

        return view('data.categories.detail', compact('title', 'menuId', 'list', 'file', 'breadcrumbs'));
    }
}

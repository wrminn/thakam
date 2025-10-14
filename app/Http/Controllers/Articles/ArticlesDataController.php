<?php

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MyService;
use App\Models\Texteditor;
use Illuminate\Support\Facades\DB;


class ArticlesDataController extends Controller
{
    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    function SelectArticlesFront($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $file = null;


        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => $title, 'url' => route('articles.data', ['menu' => $menuId])] // หน้า current
        ];


        // $list = DB::table('texteditor')
        //     ->leftJoin('texteditor_detail', 'texteditor.texteditor_id', '=', 'texteditor_detail.texteditor_id')
        //     ->where('texteditor.texteditor_menu', $menuId)
        //     ->select(
        //         'texteditor.*',
        //         'texteditor_detail.texteditor_detail_id',
        //         'texteditor_detail.texteditor_detail_seq',
        //         'texteditor_detail.texteditor_detail'
        //     )
        //     ->first();

        $list = DB::table('texteditor')
            ->leftJoin('texteditor_detail', function ($join) {
                $join->on('texteditor.texteditor_id', '=', 'texteditor_detail.texteditor_id')
                    ->where('texteditor_detail.texteditor_display', '=', 'A');
            })
            ->where('texteditor.texteditor_menu', $menuId)
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

        return view('data.articles.texteditor', compact('title', 'menuId', 'list', 'file', 'breadcrumbs'));
    }

    function SelectElibraryFront($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => $title, 'url' => route('elibrary.data', ['menu' => $menuId])] // หน้า current
        ];

        $list = DB::table('elibrary')
            ->where('elibrary_menu', $menuId)
            ->where('elibrary_display', 'A')
            ->orderBy('elibrary_id', 'desc')
            ->paginate(20);

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('data.elibrary.list', compact('title', 'menuId', 'list', 'startIndex', 'breadcrumbs'));
    }

    function SelectElibraryFrontID($menuId, $Id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $file = null;

        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => $title, 'url' => route('elibrary.data', ['menu' => $menuId])], // หน้า current
        ];

        $list = DB::table('elibrary')
            ->where('elibrary_id', $Id)
            ->where('elibrary_display', 'A')
            ->first();
            
    
        return view('data.elibrary.detail', compact('title', 'menuId', 'list', 'breadcrumbs'));
    }
}

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


        $list = DB::table('texteditor')
            ->leftJoin('texteditor_detail', 'texteditor.texteditor_id', '=', 'texteditor_detail.texteditor_id')
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

    
}

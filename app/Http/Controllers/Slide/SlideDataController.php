<?php

namespace App\Http\Controllers\Slide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MyService;

use App\Models\Slide;

class SlideDataController extends Controller
{
    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    function SelectSlideFront($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => $title, 'url' => route('slide.list', ['menu' => $menuId])] // หน้า current
        ];

        $list = Slide::active()
            ->where('slide_menu', $menuId)
            ->orderBy('slide_id', 'desc')
            ->paginate(20);
        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('data.slide.slide', compact('title', 'list', 'menuId', 'startIndex', 'breadcrumbs'));
    }
    function SelectSlideDetailFront($menuId, $Id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $file = null;

        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => $title, 'url' => route('slide.list', ['menu' => $menuId])], // หน้า current
        ];

        $list = Slide::active()
            ->where('slide_id', $Id)
            ->where('slide_display', "A")
            ->first();

        return view('data.slide.detail', compact('title', 'menuId', 'list', 'breadcrumbs'));
    }
}

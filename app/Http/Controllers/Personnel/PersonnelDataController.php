<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MyService;
use Illuminate\Support\Facades\DB;

use App\Models\Personnel;


class PersonnelDataController extends Controller
{
    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }
    function SelectPersonnelFront($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => $title, 'url' => route('directory.data', ['menu' => $menuId])], // หน้า current
        ];
        $list = Personnel::active()
            ->where('personnel_menu', $menuId)
            ->orderByRaw('CAST(personnel_seq AS UNSIGNED) ASC')
            ->paginate(50);

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        $File = DB::table('personnelgroup')
            ->where('personnelgroup_menu', $menuId)
            ->where('personnelgroup_display', 'A')
            ->paginate(10);

        return view('data.personnel.personnel', compact('title', 'list', 'menuId', 'startIndex', 'breadcrumbs','File'));
    }
}

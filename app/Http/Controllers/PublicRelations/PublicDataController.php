<?php

namespace App\Http\Controllers\PublicRelations;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Services\MyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PublicDataController extends Controller
{
    public function satisfaction($menuId)
    {
        //$titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        return view('data.public.satisfaction', compact('title', 'menuId'));
    }

    public function satisfactionInsert(Request $request, $menuId)
    {
        //$titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $request->validate([
            'customer_name'   => 'required|string|max:255',
            'customer_phone'  => 'required|digits_between:9,10',
            'customer_address' => 'required|string|max:500',
            'customer_department' => 'required|string|max:255',
            'service_topic'   => 'required|string',
            'q1'              => 'required|integer|min:1|max:5',
            'q2'              => 'required|integer|min:1|max:5',
            'q3'              => 'required|integer|min:1|max:5',
            'suggestions'   => 'required|string|max:255',
        ]);

        $data_texteditor_detail = [
            'satisfaction_customer_name'   => $request->customer_name,
            'satisfaction_customer_phone'  => $request->customer_phone,
            'satisfaction_customer_address' => $request->customer_address,
            'satisfaction_customer_department' => $request->customer_department,
            'satisfaction_service_topic'   => $request->service_topic,
            'satisfaction_service_other'   => $request->service_other,
            'satisfaction_q1'              => $request->q1,
            'satisfaction_q2'              => $request->q2,
            'satisfaction_q3'              => $request->q3,
            'satisfaction_suggestions'   => $request->suggestions,
            'satisfaction_ip'   => $request->ip(),
        ];

        DB::table('satisfaction')->insert($data_texteditor_detail);


        return redirect('/satisfaction/menu/' . $menuId)->with('success', 'ส่งแบบสอบถามสำเร็จ');
    }

    public function calendar($menuId)
    {
        //$titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        return view('data.public.calendar', compact('title', 'menuId'));
    }
    public function getEvents()
    {
        $events = DB::table('events')->where('events_display', 'A')->get()->map(function ($event) {
            return [
                'id'    => $event->events_id,
                'title' => $event->events_name,
                'start' => $event->events_start,
                'end'   => $event->events_end ?? $event->events_start,
                'color' => $event->events_color ?? '#3788d8',
            ];
        });

        return response()->json($events);
    }
}

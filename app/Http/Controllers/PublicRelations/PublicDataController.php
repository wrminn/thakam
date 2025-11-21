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
    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }
    public function satisfaction($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        return view('data.public.satisfaction', compact('title', 'menuId'));
    }

    public function satisfactionInsert(Request $request, $menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
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
        $titles = $this->myService->getDataByKey($menuId);
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

    public function showSitemap()
    {
        $menus = [
            "ข้อมูลพื้นฐาน" => [
                ["url" => "/articles/menu/1", "name" => "ประวัติความเป็นมา"],
                ["url" => "/articles/menu/2", "name" => "วิสัยทัศน์"],
                ["url" => "/articles/menu/3", "name" => "ข้อมูลสภาพทั่วไป"],
                ["url" => "/articles/menu/4", "name" => "บริการขั้นพื้นฐาน"],
                ["url" => "/personnel/menu/5", "name" => "ผู้นำชุมชน"],
                ["url" => "/articles/menu/6", "name" => "รายละเอียดชุมชน"],
                ["url" => "/directory/menu/7", "name" => "ผลิตภัณฑ์ชุมชน"],
                ["url" => "/directory/menu/8", "name" => "สถานที่สำคัญ"],
            ],
            "อำนาจหน้าที่" => [
                ["url" => "/articles/menu/29", "name" => "เทศบาลตำบล"],
                ["url" => "/articles/menu/30", "name" => "สำนักปลัด"],
                ["url" => "/articles/menu/31", "name" => "กองยุทธศาสตร์และงบประมาณ"],
                ["url" => "/articles/menu/32", "name" => "กองคลัง"],
                ["url" => "/articles/menu/33", "name" => "กองช่าง"],
                ["url" => "/articles/menu/34", "name" => "กองสาธารณสุขและสิ่งแวดล้อม"],
                ["url" => "/articles/menu/35", "name" => "กองการศึกษา"],
                ["url" => "/articles/menu/36", "name" => "กองสวัสดิการสังคม"],
                ["url" => "/articles/menu/37", "name" => "หน่วยตรวจสอบภายใน"],
            ],
            "บุคลากร" => [
                ["url" => "/articles/menu/9", "name" => "แผนผังโครงสร้างองค์กร"],
                ["url" => "/personnel/menu/10", "name" => "คณะผู้บริหาร"],
                ["url" => "/personnel/menu/11", "name" => "สมาชิกสภา"],
                ["url" => "/personnel/menu/13", "name" => "สำนักปลัด"],
                ["url" => "/personnel/menu/14", "name" => "กองยุทธศาสตร์และงบประมาณ"],
                ["url" => "/personnel/menu/15", "name" => "กองคลัง"],
                ["url" => "/personnel/menu/16", "name" => "กองช่าง"],
                ["url" => "/personnel/menu/17", "name" => "กองสาธารณสุขและสิ่งแวดล้อม"],
                ["url" => "/personnel/menu/18", "name" => "กองการศึกษา"],
                ["url" => "/personnel/menu/19", "name" => "กองสวัสดิการสังคม"],
                ["url" => "/personnel/menu/20", "name" => "หน่วยตรวจสอบภายใน"],
                ["url" => "/categories/menu/26", "name" => "การบริหารและพัฒนาทรัพยากรบุคคล"],
            ],
            "แผนงานและงบประมาณ" => [
                // จะเติม dynamic ต่อไป
            ],
            "ผลการดำเนินงาน" => [
                ["url" => "/categories/menu/21", "name" => "ผลงานองค์กร"],
                ["url" => "/categories/menu/22", "name" => "รายงานทางการเงิน"],
                ["url" => "/categories/menu/23", "name" => "รายงานผลการดำเนินงาน"],
                ["url" => "/categories/menu/24", "name" => "รายงานการจัดซื้อจัดจ้างหรือการจัดหาพัสดุ"],
                ["url" => "/categories/menu/25", "name" => "ข้อมูลเชิงสถิติ"],
                ["url" => "/categories/menu/27", "name" => "มาตรการส่งเสริมความโปร่งใสและป้องกันการทุจริต"],
                ["url" => "/categories/menu/28", "name" => "ประมวลจริยธรรมและการขับเคลื่อนจริยธรรม"],
            ],
            "กฎหมายและระเบียบ" => [
                ["url" => "/directory/menu/39", "name" => "เทศบัญญัติและคำสั่ง"],
                ["url" => "/directory/menu/40", "name" => "กฎหมายอื่นๆที่เกี่ยวข้อง"],
                ["url" => "/directory/menu/76", "name" => "แผนพัฒนาเศรษฐกิจและสังคมแห่งชาติ"],
            ],
            "บริการประชาชน" => [
                ["url" => "/satisfaction/menu/43", "name" => "แบบสอบถามความพึงพอใจ"],
                ["url" => "/directory/menu/44", "name" => "รายงานผลสำรวจความพึงพอใจ"],
                ["url" => "/directory/menu/46", "name" => "ดาวน์โหลดแบบฟอร์ม"],
                ["url" => "/directory/menu/81", "name" => "คู่มือหรือแนวทางการปฏิบัติสำหรับเจ้าหน้าที่"],
                ["url" => "/directory/menu/82", "name" => "คู่มือสำหรับประชาชน"],
            ],
            "นโยบายเว็บไซต์" => [
                ["url" => "/articles/menu/83", "name" => "การปฏิเสธความรับผิด"],
                ["url" => "/articles/menu/84", "name" => "นโยบายการคุ้มครองข้อมูลส่วนบุคคล"],
                ["url" => "/articles/menu/85", "name" => "นโยบายเว็บไซต์"],
                ["url" => "/articles/menu/86", "name" => "นโยบายการรักษาความมั่นคงปลอดภัยเว็บไซต์"],
            ],
        ];

        // ตัวอย่างเติม dynamic (จาก categories ที่มี menu_id = 38)
        $recentMenu = DB::table('categories')->where('categories_menu', 38)->get();
        foreach ($recentMenu as $item) {
            $menus['แผนงานและงบประมาณ'][] = [
                'url' => "/directory/menu/38/cate/{$item->categories_id}",
                'name' => $item->categories_name
            ];
        }

      

        return view('data.public.sitemap', compact('menus'));
    }

   
}

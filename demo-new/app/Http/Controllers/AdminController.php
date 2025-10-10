<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    // public function __construct()
    // {
    //     // ให้ middleware auth ตรวจสอบทุกเมธอดของ controller นี้
    //     $this->middleware('auth');
    // }

    function index() {}

    private function getTitles()
    {
        return [
            1 => "ประวัติความเป็นมา",
            2 => "ข้อมูลสภาพทั่วไป",
            3 => "ข้อมูลและรายละเอียดชุมชน",
            4 => "ผู้นำชุมชน",
            5 => "ผลิตภัณฑ์ชุมชน",
            6 => "สถานที่สำคัญ",
            7 => "แกลอรี่ภาพถ่ายภูมิทัศน์",
            8 => "บริการขั้นพื้นฐาน",
            9 => "ยุทธศาสตร์การพัฒนา",
            10 => "โครงสร้างองค์กร",
            11 => "คณะผู้บริหาร",
            12 => "สมาชิกสภา",
            13 => "ผู้บริหารส่วนราชการ",
            14 => "สำนักปลัดเทศบาล",
            15 => "กองคลัง",
            16 => "กองช่าง",
            17 => "กองการศึกษา",
            18 => "กองสาธารณสุขและสิ่งแวดล้อม",
            19 => "กองสวัสดิการสังคม",
            20 => "กองส่งเสริมการเกษตร",
            21 => "กองยุทธศาสตร์และงบประมาณ",
            22 => "กองการประปา",
            23 => "หน่วยตรวจสอบภายใน",
            24 => "รายงานแสดงฐานะการเงิน",
            25 => "งบแสดงฐานะทางการเงิน",
            26 => "รายงานแสดงรายรับ-รายจ่าย",
            27 => "แผนการใช้จ่ายเงินงบประมาณประจำปี",
            28 => "รายงานการตรวจสอบการเงิน สำนักงานการตรวจเงินแผ่นดิน",
            29 => "รายงานผลการดำเนินงาน ประจำไตรมาส",
            30 => "รายงานผลการดำเนินงาน ประจำปีงบประมาณ",
            31 => "รายงานผลการจัดซื้อจัดจ้างหรือการจัดหาพัสดุ",
            32 => "รายงานผลการจัดซื้อจัดจ้างหรือการจัดหาพัสดุรายเดือน",
            33 => "รายงานผลการจัดซื้อจัดจ้างหรือการจัดหาพัสดุประจำปี",
            34 => "ความก้าวหน้าในการจัดซื้อจัดจ้างหรือการจัดหาพัสดุ",
            35 => "ข้อมูลเชิงสถิติการให้บริการ",
            36 => "ข้อมูลเชิงสถิติเรื่องร้องเรียนการทุจริตและประพฤติมิชอบ",
            37 => "ประกาศและนโยบายการบริหารทรัพยากรบุคคล",
            38 => "หลักเกณฑ์การบริหารและพัฒนาทรัพยากรบุคคล",
            39 => "การดำเนินการตามนโยบายการบริหารทรัพยากรบุคคล",
            40 => "รายงานผลการบริหารและพัฒนาทรัพยากรบุคคลประจำปี",
            41 => "แผนการบริหารและพัฒนาทรัพยากรบุคคล",
            42 => "มาตรฐานการกำหนดตำแหน่ง",
            43 => "มาตรการป้องกันการรับสินบน",
            44 => "มาตรการเผยแพร่ข้อมูลต่อสาธารณะ",
            45 => "มาตรการตรวจสอบการใช้ดุลพินิจ",
            46 => "มาตรการส่งเสริมความโปรงใส่ในการจัดซื้อจัดจ้าง",
            47 => "มาตรการจัดการเรื่องร้องเรียนการทุจริตและประพฤติมิชอบ",
            48 => "มาตรการให้ผู้มีส่วนได้เสียมีส่วนร่วมในการป้องกันทุจริตและประพฤติมิชอบ",
            49 => "มาตรการป้องกันการขัดกันระหว่างผลประโยชน์ส่วนตนกับผลประโยชน์ส่วนรวม",
            50 => "มาตรการส่งเสริมคุณธรรมและความโปร่งในภายในหน่วยงาน",
            51 => "รายงานผลการดำเนินการเพื่อส่งเสริมคุณธรรมและความโปร่งใสภายในหน่วยงาน",
            52 => "แนวปฏิบัติการจัดการเรื่องร้องเรียนการทุจริตและประพฤติมิชอบ",
            53 => "อำนาจหน้าที่ เทศบาล",
            54 => "สำนักปลัดเทศบาล",
            55 => "กองคลัง",
            56 => "กองช่าง",
            57 => "กองการศึกษา",
            58 => "กองสาธารณสุขและสิ่งแวดล้อม",
            59 => "กองสวัสดิการสังคม",
            60 => "กองส่งเสริมการเกษตร",
            61 => "กองยุทธศาสตร์และงบประมาณ",
            62 => "กองการประปา",
            63 => "หน่วยตรวจสอบภายใน",
            64 => "แผนพัฒนาท้องถิ่น",
            65 => "แผนการดำเนินงานประจำปี",
            66 => "แผนแม่บทระบบเทคโนโลยีสารสนเทศ",
            67 => "แผนการจัดซื้อจัดจ้างหรือการจัดหาพัสดุ",
            68 => "แผนอัตรากำลัง",
            69 => "แผนยุทธศาสตร์และการพัฒนา",
            70 => "แผนปฏิบัติการป้องกันการทุจริตประจำปี",
            71 => "เทศบัญญัติงบประมาณรายจ่าย",
            72 => "การประเมินความเสี่ยงการทุจริตและประพฤติมิชอบประจำปี",
            73 => "รายงานผลการดำเนินการป้องกันการทุจริตและประพฤติมิชอบประจำปี",
            74 => "รายงานติดตามและประเมินผลแผนพัฒนา",
            75 => "ข้อบัญญัติ และคำสั่ง อบต./เทศบัญญัติ และคำสั่งเทศบาล",
            76 => "กฎหมายที่เกี่ยวข้อง",
            77 => "กฎหมาย/ระเบียบ",
            78 => "กฎหมายเกี่ยวกับภาษี",
            79 => "ระเบียบเกี่ยวกับการจัดทำแผนพัฒนา",
            80 => "กฎหมายที่เกี่ยวกับการปฏิบัติงาน",
            81 => "กฎหมายเกี่ยวกับการจัดซื้อจัดจ้าง",
            82 => "กฎหมายเกี่ยวกับการจัดตั้ง/ขอบเขตอำนาจหน้าที่ของเทศบาล",
            83 => "พระราชบัญญัติ และพระราชกฤษฎีกา",
            84 => "กฎหมาย ระเบียบ และประกาศกระทรวง",
            85 => "รับเรื่องราวร้องทุกข์",
            86 => "รับแจ้งร้องเรียนทุจริตประพฤติมิชอบ",
            87 => "แบบสอบถามความพึงพอใจ",
            88 => "รายงานผลการสำรวจความพึงพอใจการให้บริการ",
            89 => "คู่มือสำหรับประชาชน",
            90 => "คู่มือการป้องกันการทุจริต",
            91 => "คู่มือหรือมาตรฐานการปฏิบัติงาน",
            92 => "E-Service",
            93 => "ดาวน์โหลดแบบฟอร์ม",
            94 => "เบี้ยยังชีพผู้สูงอายุ",
            95 => "เบี้ยยังชีพคนพิการ",
            96 => "คำถามที่พบบ่อย",
            97 => "ระบบจองห้องประชุมและเครื่องเสียงห้องประชุม",
            98 => "E-Library",
            99 => "เบี้ยยังชีพผู้สูงอายุ",
            100 => "เบี้ยยังชีพคนพิการ",
            101 => "ภาพสไลด์นายก",
            102 => "ภาพสไลด์ปลัด",
            103 => "สายด่วนนายก",
            104 => "สายด่วนปลัด",
            105 => "สารจากนายก",
            106 => "เจตจำนงสุจริตของผู้บริหาร",
            107 => "รับเรื่องราวร้องทุกข์",
            108 => "รับแจ้งร้องเรียนทุจริตประพฤติมิชอบ",
            109 => "การประเมินคุณธรรม และความโปร่งใส (ITA)",
            110 => "ประกาศจัดซื้อจัดจ้าง",
            111 => "ผลประกาศจัดซื้อจัดจ้าง",
            112 => "ประกาศราคากลาง",
            113 => "รายงานผลจัดซื้อจัดจ้าง",
            114 => "วิดีทัศน์",
            115 => "ป้ายประกาศ",
            116 => "E-SERVICE",
            117 => "กิจกรรม",
            118 => "ข่าวประชาสัมพันธ์",
            119 => "แนะนำสถานที่ท่องเที่ยว",
            120 => "โรงแรม ปั้มน้ำมัน ร้านอาหาร"

        ];
    }

    // public  function showLoginForm()
    // {
    //     return view('admin.login');
    // }

    // public function login(Request $request)
    // {

    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);

    //     $loginData = [
    //         'user_email' => $credentials['email'],
    //         'password'   => $credentials['password'],
    //     ];

    //     if (Auth::attempt($loginData)) {
    //         $request->session()->regenerate();
    //         return redirect()->intended('/backend');
    //     }

    //     return back()->withErrors([
    //         'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
    //     ])->onlyInput('email');
    // }

    // // ออกจากระบบ
    // public function logout(Request $request)
    // {
    //     Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return redirect('/login');
    // }

    function backend()
    {
        return view('admin.backend');
    }

    // รายการ
    function list($menuId)
    {
        $titles = $this->getTitles();
        $title = $titles[$menuId] ?? 'ข้อมูลเมนู' . $menuId;

        $list = null;
        $list = DB::table('texteditor')
            ->where('texteditor_menu', $menuId)
            ->where('texteditor_display', "A")
            ->get()->toArray();

        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $list[$key]->id_new = $key + 1;
            }
        }

        return view('admin.list', compact('title', 'list', 'menuId'));
    }

    function add($menuId)
    {
        $titles = $this->getTitles();
        $title = $titles[$menuId] ?? 'ข้อมูลเมนู' . $menuId;
        return view('admin.add', compact('title', 'menuId'));
    }

    function insert(Request $request, $menuId, $category = "")
    {

        $id = DB::table('texteditor')->insertGetId([
            'texteditor_title' => $request->topic,
            'texteditor_date_show' => $request->date,
            'texteditor_category_id' => $category ? $category : 0,
            'texteditor_menu' => $menuId,
        ]);

        if (!empty($request->detail)) {
            $data_texteditor_detail = [
                'texteditor_detail' => $request->detail,
                'texteditor_id' => $id,
            ];
            DB::table('texteditor_detail')->insert($data_texteditor_detail);
        }

        if ($request->hasFile('topic_picture')) {

            $file = $request->file('topic_picture');
            $ext = $file->getClientOriginalExtension();

            // สร้างชื่อกลาง
            $timestamp = now()->format('Ymd_His');

            $folder = "/content/{$menuId}";
            $filename = "{$id}_topic_{$timestamp}.{$ext}";
            $path = $file->storeAs($folder, $filename, 'public');

            DB::table('texteditor')->where('texteditor_id', $id)
                ->update([
                    'texteditor_topic_picture' => $path
                ]);
        }

        if ($request->hasFile('files')) {

            foreach ($request->file('files') as $key => $files) {
                $file = $request->file('files');
                $ext = $files->getClientOriginalExtension();
                // สร้างชื่อกลาง
                $timestamp = now()->format('Ymd_His');
                $seq = $key + 1;
                $folder = "/content/{$menuId}";
                $filename = "";
                $filename = "{$id}_{$seq}_{$timestamp}.{$ext}";
                $path = $files->storeAs($folder, $filename, 'public');

                $data_texteditor_upload = [
                    'texteditor_id' => $id,
                    'texteditor_upload_seq' => $seq,
                    'texteditor_upload_name' => $files->getClientOriginalName(),
                    'texteditor_upload_file' => $path,
                ];

                DB::table('texteditor_upload')->insert($data_texteditor_upload);
            }
        }

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $key => $files) {
                $file = $request->file('images');
                $ext = $files->getClientOriginalExtension();
                // สร้างชื่อกลาง
                $timestamp = now()->format('Ymd_His');
                $seq = $key + 1;
                $folder = "/content/{$menuId}";
                $filename = "";
                $filename = "{$id}_{$seq}_{$timestamp}.{$ext}";
                $path = $files->storeAs($folder, $filename, 'public');

                $data_texteditor_upload = [
                    'texteditor_id' => $id,
                    'texteditor_upload_seq' => $seq,
                    'texteditor_upload_name' => $files->getClientOriginalName(),
                    'texteditor_upload_file' => $path,
                ];

                DB::table('texteditor_upload')->insert($data_texteditor_upload);
            }
        }

        return redirect('backend/list/menu/' . $menuId);
    }

    function edit($menuId, $id)
    {
        $titles = $this->getTitles();
        $title = $titles[$menuId] ?? 'ข้อมูลเมนู' . $menuId;
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


        return view('admin.edit', compact('title', 'list', 'file', 'menuId', 'id'));
    }


    function update(Request $request, $menuId, $id, $category = "")
    {

        DB::table('texteditor')
            ->where('texteditor_id', $id)
            ->update([
                'texteditor_title' => $request->topic,
                'texteditor_date_show' => $request->date,
                'texteditor_date_update' => now()
            ]);
        DB::table('texteditor_detail')
            ->where('texteditor_id', $id)
            ->update([
                'texteditor_detail' => $request->detail
            ]);

        if ($request->hasFile('topic_picture')) {

            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();

            // สร้างชื่อกลาง
            $timestamp = now()->format('Ymd_His');

            $folder = "/content/{$menuId}";
            $filename = "{$id}_topic_{$timestamp}.{$ext}";
            $path = $file->storeAs($folder, $filename, 'public');

            DB::table('texteditor')->where('texteditor_id', $id)
                ->update([
                    'texteditor_topic_picture' => $path
                ]);
        }

        if ($request->hasFile('files')) {

            foreach ($request->file('files') as $key => $files) {
                $file = $request->file('files');
                $ext = $files->getClientOriginalExtension();
                // สร้างชื่อกลาง
                $timestamp = now()->format('Ymd_His');
                $seq = $key + 1;
                $folder = "/content/{$menuId}";
                $filename = "";
                $filename = "{$id}_{$seq}_{$timestamp}.{$ext}";
                $path = $files->storeAs($folder, $filename, 'public');

                $data_texteditor_upload = [
                    'texteditor_id' => $id,
                    'texteditor_upload_seq' => $seq,
                    'texteditor_upload_name' => $files->getClientOriginalName(),
                    'texteditor_upload_file' => $path,
                ];

                DB::table('texteditor_upload')->insert($data_texteditor_upload);
            }
        }

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $key => $files) {
                $file = $request->file('images');
                $ext = $files->getClientOriginalExtension();
                // สร้างชื่อกลาง
                $timestamp = now()->format('Ymd_His');
                $seq = $key + 1;
                $folder = "/content/{$menuId}";
                $filename = "";
                $filename = "{$id}_{$seq}_{$timestamp}.{$ext}";
                $path = $files->storeAs($folder, $filename, 'public');

                $data_texteditor_upload = [
                    'texteditor_id' => $id,
                    'texteditor_upload_seq' => $seq,
                    'texteditor_upload_name' => $files->getClientOriginalName(),
                    'texteditor_upload_file' => $path,
                ];

                DB::table('texteditor_upload')->insert($data_texteditor_upload);
            }
        }

        return redirect('backend/list/menu/' . $menuId);
    }

    function delete($id, $menuId)
    {
        DB::table('texteditor')->where('texteditor_id', $id)
            ->update([
                'texteditor_display' => 'A',
                'texteditor_date_update' => now()
            ]);
        return redirect('backend/list/menu/' . $menuId);
    }

    function deletelistfile($menuId, $id, $idFile)
    {
        DB::table('texteditor_upload')->where('texteditor_upload_id', $idFile)
            ->update([
                'texteditor_display' => 'D',
            ]);
        return redirect('backend/edit/menu/' . $menuId . '/id/' . $id);
    }

    // เมนูหน้าเดียว

    function listtexteditor($menuId)
    {
        $titles = $this->getTitles();
        $title = $titles[$menuId] ?? 'ข้อมูลเมนู' . $menuId;


        $list = DB::table('texteditor')
            ->where('texteditor_menu', $menuId)
            ->first();

        if (!empty($list)) {
            $list = DB::table('texteditor_detail')
                ->where('texteditor_id', $list->texteditor_id)
                ->first();

            $file = DB::table('texteditor_upload')
                ->where('texteditor_id', $list->texteditor_id)
                ->where('texteditor_display', "A")
                ->get()->toArray();
        }

        // echo "<pre>";
        // print_r($file);
        // exit();
        return view('admin.listtexteditor', compact('title', 'list', 'file', 'menuId'));
    }

    function inserttexteditor(Request $request, $menuId, $category = "")
    {

        $titles = $this->getTitles();
        $title = $titles[$menuId] ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('texteditor')
            ->where('texteditor_menu', $menuId)
            ->first();

        if (!empty($list)) {
            $filename = null;
            $data_texteditor = [
                'texteditor_detail' => $request->detail,
            ];
            DB::table('texteditor')->where('texteditor_id', $list->texteditor_id)
                ->update([
                    'texteditor_date_update' => now()
                ]);
            DB::table('texteditor_detail')->where('texteditor_id', $list->texteditor_id)
                ->update([
                    'texteditor_detail' => $request->detail
                ]);
        } else {
            $data_texteditor = [
                'texteditor_title' => $title,
                'texteditor_category_id' =>  $category ? $category : 0,
                'texteditor_menu' => $menuId,
            ];
            DB::table('texteditor')->insert($data_texteditor);

            $list_select = DB::table('texteditor')
                ->where('texteditor_menu', $menuId)
                ->first();

            $data_texteditor_detail = [
                'texteditor_detail' => $request->detail,
                'texteditor_id' => $list_select->texteditor_id,
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

            // สร้างชื่อกลาง
            $timestamp = now()->format('Ymd_His');

            $folder = "/content/{$menuId}";
            $filename = "{$list_select->texteditor_id}_default_{$timestamp}.{$ext}";
            $path = $file->storeAs($folder, $filename, 'public');

            $data_texteditor_upload = [
                'texteditor_id' => $list_select->texteditor_id,
                'texteditor_upload_seq' => "1",
                'texteditor_upload_name' => $file->getClientOriginalName(),
                'texteditor_upload_file' => $path,
            ];
            DB::table('texteditor_upload')->insert($data_texteditor_upload);
        }

        return redirect('backend/listtexteditor/menu/' . $menuId);
    }
    function deletetexteditorfile($menuId, $id)
    {
        DB::table('texteditor_upload')->where('texteditor_upload_id', $id)
            ->update([
                'texteditor_display' => 'D',
            ]);
        return redirect('backend/listtexteditor/menu/' . $menuId);
    }
    //banner
}

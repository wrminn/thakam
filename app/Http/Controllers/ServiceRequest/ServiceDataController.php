<?php

namespace App\Http\Controllers\ServiceRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

use App\Models\Complaint;
use App\Models\Corruption;
use App\Models\Contact;
use App\Models\GennericForm;

class ServiceDataController extends Controller
{
    function indexComplaint($menuId)
    {

        //$titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        return view('data.complaint.index', compact('title', 'menuId'));
    }

    function insertComplaint(Request $request, $menuId)
    {

        $ip = request()->ip();

        $id = DB::table('complaints')->insertGetId([
            'complaints_name' => $request->name,
            'complaints_email' => $request->email,
            'complaints_age' => $request->age,
            'complaints_tel' => $request->tel,
            'complaints_address' => $request->address,
            'complaints_gender' => $request->gender,
            'complaints_topic' => $request->topic,
            'complaints_detail' => $request->detail,
            'complaints_date_insert' => now(),
            'complaints_menu' => $menuId,
            'complaints_ip' => $ip,
        ]);

        if ($request->hasFile('img')) {

            $file = $request->file('img');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_file_{$timestamp}.{$ext}";
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

            DB::table('complaints')->where('complaints_id', $id)
                ->update([
                    'complaints_file' => $path
                ]);
        }

        return redirect('complaint/menu/' . $menuId)->with('success', 'ส่งแบบฟอร์มร้องเรียนสำเร็จ');
    }

    function indexCorruption($menuId)
    {

        // $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        return view('data.corruption.index', compact('title', 'menuId'));
    }

    function insertCorruption(Request $request, $menuId)
    {

        $ip = request()->ip();

        $id = DB::table('corruptions')->insertGetId([
            'corruptions_name' => $request->name,
            'corruptions_email' => $request->email,
            'corruptions_age' => $request->age,
            'corruptions_tel' => $request->tel,
            'corruptions_address' => $request->address,
            'corruptions_gender' => $request->gender,
            'corruptions_topic' => $request->topic,
            'corruptions_detail' => $request->detail,
            'corruptions_date_insert' => now(),
            'corruptions_menu' => $menuId,
            'corruptions_ip' => $ip,
        ]);

        if ($request->hasFile('img')) {

            $file = $request->file('img');
            $ext = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');

            $folder = "content/{$menuId}"; // path ใน disk 'public'
            $filename = "{$id}_file_{$timestamp}.{$ext}";
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

            DB::table('corruptions')->where('corruptions_id', $id)
                ->update([
                    'corruptions_file' => $path
                ]);
        }
        return redirect('corruption/menu/' . $menuId)->with('success', 'ส่งแบบฟอร์มร้องเรียนสำเร็จ');
    }

    function indexContact($menuId)
    {
        // $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        return view('data.contact.index', compact('title', 'menuId'));
    }

    function insertContact(Request $request, $menuId)
    {
        $ip = request()->ip();
        $id = DB::table('contacts')->insertGetId([
            'contacts_topic' => $request->topic,
            'contacts_name' => $request->name,
            'contacts_tel' => $request->tel,
            'contacts_address' => $request->address,
            'contacts_email' => $request->email,
            'contacts_detail' => $request->detail,
            'contacts_date_insert' => now(),
            'contacts_menu' => $menuId,
            'contacts_ip' => $ip,
        ]);

        
        return redirect('contact/menu/' . $menuId)->with('success', 'ส่งแบบฟอร์มสำเร็จ');
    }

    //eservice

    public function SelectDate()
    {
        $timestamp = time();
        $thai_months = [
            1 => "มกราคม",
            2 => "กุมภาพันธ์",
            3 => "มีนาคม",
            4 => "เมษายน",
            5 => "พฤษภาคม",
            6 => "มิถุนายน",
            7 => "กรกฎาคม",
            8 => "สิงหาคม",
            9 => "กันยายน",
            10 => "ตุลาคม",
            11 => "พฤศจิกายน",
            12 => "ธันวาคม"
        ];
        $day = date('j', $timestamp);
        $month = date('n', $timestamp);
        $year = date('Y', $timestamp) + 543;

        $thai_date = $day . " " . $thai_months[$month] . " " . $year;
        return $thai_date;
    }
    function listform($menuId)
    {
        // $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = GennericForm::active()->paginate(20);

        return view('data.formeservice.index', compact('title', 'menuId', 'list'));
    }

    function listformpdf($menuId, $id)
    {
        // $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('table_form_1')
            ->where('form_id', '1')
            ->first();

        return view('data.formeservice.pdf.table_1', compact('title', 'menuId', 'list'));
    }

    function showform($menuId, $id)
    {
        // $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;
        $Date = $this->SelectDate();
        $form_page = 'data.formeservice.table_' . $id;

        return view($form_page, compact('title', 'menuId', 'id', 'Date'));
    }

    function saveform(Request $request, $menuId, $id)
    {


        $data = [];

        foreach ($request->all() as $key => $value) {
            if (preg_match('/^field_(\d+)$/', $key, $matches)) {
                $num = (int) $matches[1];
                if ($num >= 1 && $num <= 50) {
                    $data[$key] = $value;
                }
            }
        }
        $table_name = "table_form_" . $id;
        $id_inserted = DB::table($table_name)->insertGetId($data);

        if ($request->hasFile('files')) {

            foreach ($request->file('files') as $key => $file) {

                $ext = $file->getClientOriginalExtension();
                $timestamp = now()->format('Ymd_His');
                $seq = $key + 1;

                $folder = "content/{$menuId}";
                $filename = "{$id}_formeservice_{$seq}_{$timestamp}.{$ext}";
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
                    'form_id' => $id_inserted,
                    'form_path' => $path,
                ];
                $table_name = "table_form_file_" . $id;
                DB::table($table_name)->insert($data_texteditor_upload);
            }
        }
        return redirect('/formeservice/menu/' . $menuId . '/id/' . $id)->with('success', 'ส่งแบบฟอร์มสำเร็จ');
    }


    // public function GeneralRequestsAdminExportPDFtest($id)
    // {
    //     $list = DB::table('table_form_1')
    //         ->where('form_id', $id)
    //         ->first();

    //     $pdf = Pdf::loadView('data.formeservice.pdf.table_1_test', compact('list'))->setPaper('A4', 'portrait');

    //     return $pdf->stream('แบบคำขอร้องทั่วไป' . $id . '.pdf');
    // }

    // public function GeneralRequestsAdminExportPDFtest2pdf($id)
    // {
    //     $list = DB::table('table_form_1')
    //         ->where('form_id', $id)
    //         ->first();

    //     // สร้าง HTML2PDF object
    //     $html2pdf = new Html2Pdf('P', 'A4', 'en'); // P = Portrait, A4, ภาษาไทย
    //     $html2pdf->setDefaultFont('freeserif');   // แนะนำ font ภาษาไทย

    //     // Render view เป็น HTML แล้วส่งให้ Html2Pdf
    //     $htmlContent = view('data.formeservice.pdf.table_1_test', compact('list'))->render();
    //     $html2pdf->writeHTML($htmlContent);

    //     // แสดงใน Browser (stream)
    //     return $html2pdf->output('แบบคำขอร้องทั่วไป_' . $id . '.pdf', 'I');
    // }

    /*
    public function GeneralRequestsAdminExportPDF($form, $id)
    {
        $name_form = DB::table('gennericforms')
            ->where('gennericforms_id', $form)
            ->first();

        $table_name = $name_form->gennericforms_name_table;
        $file = 'table_' . $form;
        $list = DB::table($table_name)
            ->where('form_id', $id)
            ->first();

        if (!$list) {
            abort(404, 'ไม่พบข้อมูล');
        }


        $parts = explode(' ', $list->field_date);
        $day = $parts[0] ?? '';
        $month = $parts[1] ?? '';
        $year = $parts[2] ?? '';

        $html = view('data.formeservice.pdf.' . $file, compact('list', 'day', 'month', 'year'))->render();

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
            'fontdata' => $fontData + [
                'thsarabun' => [
                    'R' => 'THSarabunNew.ttf',
                    'B' => 'THSarabunNew-Bold.ttf',
                    'I' => 'THSarabunNew-Italic.ttf',
                    'BI' => 'THSarabunNew-BoldItalic.ttf'
                ]
            ],
            'default_font' => 'thsarabun',
            'format' => 'A4',
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_left' => 25,
            'margin_right' => 25,
        ]);

        // return view('data.formeservice.pdf.table_1', compact('list', 'day', 'month', 'year'));
        $mpdf->WriteHTML($html);
        return response($mpdf->Output('', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $name_form->gennericforms_name . '-' . $id . '.pdf"');
    }
            */
}

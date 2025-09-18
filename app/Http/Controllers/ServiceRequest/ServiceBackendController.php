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

class ServiceBackendController extends Controller
{

    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    // ร้องเรียนร้องทุกข์
    function SelectComplaint($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Complaint::active()
            ->where('complaints_menu', $menuId)
            ->paginate(20);

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.complaints.list', compact('title', 'list', 'menuId', 'startIndex'));
    }

    function ComplaintDetail($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Complaint::active()
            ->where('complaints_id', $id)
            ->first();

        return view('backend.complaints.detail', compact('title', 'list', 'menuId', 'id'));
    }

    function ComplaintUpdate(Request $request, $menuId, $id)
    {
        DB::table('complaints')->where('complaints_id', $id)
            ->update([
                'complaints_status' => $request->status,
                'complaints_note' => $request->note,
                'complaints_date_update' => now()
            ]);
        return redirect('backend/complaint/menu/' . $menuId);
    }

    // ร้องเรียนทุจริต
    function SelectCorruption($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Corruption::active()
            ->where('corruptions_menu', $menuId)
            ->paginate(20);

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.corruptions.list', compact('title', 'list', 'menuId', 'startIndex'));
    }

    function CorruptionDetail($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Corruption::active()
            ->where('corruptions_id', $id)
            ->first();

        return view('backend.corruptions.detail', compact('title', 'list', 'menuId', 'id'));
    }

    function CorruptionUpdate(Request $request, $menuId, $id)
    {
        DB::table('corruptions')->where('corruptions_id', $id)
            ->update([
                'corruptions_status' => $request->status,
                'corruptions_note' => $request->note,
                'corruptions_date_update' => now()
            ]);
        return redirect('backend/corruption/menu/' . $menuId);
    }

    //Contact
    function SelectContact($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Contact::active()
            ->where('contacts_menu', $menuId)
            ->paginate(20);

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.contacts.list', compact('title', 'list', 'menuId', 'startIndex'));
    }

    function ContactDetail($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = Contact::active()
            ->where('contacts_id', $id)
            ->first();

        return view('backend.contacts.detail', compact('title', 'list', 'menuId', 'id'));
    }

    function ContactUpdate(Request $request, $menuId, $id)
    {

        DB::table('contacts')->where('contacts_id', $id)
            ->update([
                'contacts_status' => $request->status,
                'contacts_note' => $request->note,
                'contacts_date_update' => now()
            ]);
        return redirect('backend/contact/menu/' . $menuId);
    }

    function listeservice($menuId)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $list = DB::table('gennericforms')->paginate(20);


        foreach ($list as $item) {
            $tableName = $item->gennericforms_name_table;

            if (Schema::hasTable($tableName)) {
                $count = DB::table($tableName)
                    ->where('form_status', 'N')
                    ->count();

                $item->form_count = $count; // เก็บค่า count ไว้ใน list
            } else {
                $item->form_count = 0;
            }
        }

        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.eservice.list', compact('title', 'list', 'menuId', 'startIndex'));
    }

    function listeserviceOne($menuId, $id)
    {
        $titles = $this->myService->getDataByKey($menuId);
        $title = $titles ?? 'ข้อมูลเมนู' . $menuId;

        $form =  DB::table('gennericforms')->get();


        $tableName = 'table_form_' . $id;
        $list = DB::table($tableName)->paginate(20);
        
        $tableUpload = 'table_form_file_' . $id;
        $list->getCollection()->transform(function ($item) use ($tableUpload) {
            $item->file = DB::table($tableUpload)
                ->where('form_id', $item->form_id)
                ->pluck('form_path')
                ->toArray();
            return $item;
        });


        $startIndex = ($list->currentPage() - 1) * $list->perPage() + 1;

        return view('backend.eservice.listformone', compact('title', 'list', 'menuId', 'id', 'startIndex', 'form'));
    }

    public function reply(Request $request)
    {

        $request->validate([
            'form_id' => 'required|integer',
            'reply_message' => 'required|string',
        ]);
        $tableName = 'table_form_' . $request->form_id;

        DB::table($tableName)->where('form_id', $request->form_id)
            ->update([
                'form_note' => $request->reply_message,
                'form_status' => 'R'
            ]);

        return redirect()->back()->with('success', 'ตอบกลับเรียบร้อยแล้ว');
    }

}

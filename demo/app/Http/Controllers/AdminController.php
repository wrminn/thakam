<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    function index() {}

    function list($menuId)
    {
        // $list_data = DB::table('texteditor')
        //     ->where('texteditor_menu', $menuId)
        //     ->paginate(1);

        // $list = $list_data->getCollection()->values()->map(function ($item, $key) use ($list_data) {
        //     $item->id_new = $list_data->firstItem() + $key;
        //     return $item;
        // });

        // $list_data->setCollection($list);


        // return view('admin.list', compact('list', 'list_data', 'menuId'));

        $list_data = DB::table('texteditor')
            ->where('texteditor_menu', $menuId)
            ->get();

        $list = $list_data->values()->map(function ($item, $key) {
            $item->id_new = $key + 1;
            return $item;
        });

        return view('admin.list', compact('list', 'menuId'));
    }


    function add($menuId)
    {
        return view('admin.add', compact('menuId'));
    }

    function edit($menuId, $id)
    {
        $list = DB::table('texteditor')
            ->where('texteditor_id', $id)
            ->first();

        // echo "<pre>";
        // print_r($list);
        // exit();
        return view('admin.edit', compact('list','menuId', 'id'));
    }

    function insert(Request $request, $menuId, $category = "")
    {

        /*
        $request->validate(
            [
                'data' => 'required|max:200',
                'topic' => 'required',
            ],
            [
                'title.required' => 'กรุณากรอกข้อมูล'
            ]
        );
        */

        $data = [
            'texteditor_title' => $request->topic,
            'texteditor_date_show' => $request->date,
            // 'texteditor_title' => $request->detail,
            'texteditor_category_id' =>  $category ? $category : 0,
            'texteditor_menu' => $menuId,
        ];
        DB::table('texteditor')->insert($data);
        return redirect('backend/list/menu/' . $menuId);
    }

    function update(Request $request, $menuId, $category = "")
    {

        $data = [
            'texteditor_title' => $request->topic,
            'texteditor_date_show' => $request->date,
            // 'texteditor_title' => $request->detail,
            'texteditor_category_id' =>  $category ? $category : 0,
            'texteditor_menu' => $menuId,
        ];
        DB::table('texteditor')->insert($data);
        return redirect('backend/list/menu/' . $menuId);
    }


    function delete($id, $menuId)
    {
        // DB::table('texteditor')->where('texteditor_id',$id)->delete();
        return redirect('backend/list/menu/' . $menuId);
        
    }
}

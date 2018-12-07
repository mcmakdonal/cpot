<?php

namespace App\Http\Controllers;

use App\Table\Images;
use Illuminate\Http\Request;
use Validator;

class ImagesController extends Controller
{
    public function ads(Request $request)
    {
        $data = Images::lists("ads");
        return view('images_mobile.ads', ['data' => $data]);
    }

    public function background(Request $request)
    {
        $data = Images::lists("background");
        return view('images_mobile.background', ['data' => $data]);
    }

    public function ads_image(Request $request)
    {
        $data = Images::lists("ads","A");
        foreach($data as $k => $v){
            unset($v->create_date);
            unset($v->create_by);
            unset($v->update_date);
            unset($v->update_by);
            unset($v->record_status);

            $data[$k]->path = url($v->path);
        }
        $obj = [
            'data_object' => $data
        ];
        return $obj;
    }

    public function background_image(Request $request)
    {
        $data = Images::lists("background","A");
        foreach($data as $k => $v){
            unset($v->create_date);
            unset($v->create_by);
            unset($v->update_date);
            unset($v->update_by);
            unset($v->record_status);

            $data[$k]->path = url($v->path);
        }
        $obj = [
            'data_object' => $data
        ];
        return $obj;
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            $error = [
                'status' => false,
                'message' => 'Please fill all data',
            ];
            return redirect()->back()->withErrors($error);
        }

        $file = "";
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $name = md5($file->getClientOriginalName() . " " . date('Y-m-d H:i:s')) . "." . $file->getClientOriginalExtension();
            $file->move(public_path() . '/images/', $name);
            $file = "/images/" . $name;
        }

        $type = ($request->type == "ads") ? $request->type : "background";
        $args = [
            'active' => 'I',
            'path' => $file,
            'type' => $type,
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => \Cookie::get('ad_id'),
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => \Cookie::get('ad_id'),
            'record_status' => 'A',
        ];

        $status = Images::insert($args);
        if ($status['status']) {
            return redirect("/$type")->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => 'error'));
        }
    }

    public function active(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            $error = [
                'status' => false,
                'message' => 'Please fill all data',
            ];
            return redirect()->back()->withErrors($error);
        }

        $id = $request->id;
        $result = Images::active($id, "A");
        return response()->json($result);
    }

    public function unactive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            $error = [
                'status' => false,
                'message' => 'Please fill all data',
            ];
            return redirect()->back()->withErrors($error);
        }

        $id = $request->id;
        $type = ($request->type == "ads") ? $request->type : "background";
        $result = Images::active($id, "I", $type);
        return response()->json($result);
    }

    public function destroy($id)
    {
        // return response()->json([
        //     'status' => false,
        // ]);
        $result = Images::delete($id);
        return response()->json($result);
    }
}

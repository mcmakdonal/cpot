<?php

namespace App\Http\Controllers;

use App\Table\Privacy;
use Illuminate\Http\Request;
use Validator;

class PrivacyController extends Controller
{
    public function __construct()
    {
        $this->middleware('islogin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Privacy::lists();
        return view('privacy.index', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'p_choice' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $args = [
            'p_choice' => $request->p_choice,
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => \Cookie::get('ad_id'),
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => \Cookie::get('ad_id'),
            'record_status' => 'A',
        ];

        $status = Privacy::insert($args);
        if ($status['status']) {
            return redirect("/privacy")->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => 'error'));
        }
    }

    public function destroy($id)
    {
        $result = Privacy::delete($id,\Cookie::get('ad_id'));
        return response()->json($result);
    }

}

<?php

namespace App\Http\Controllers;

use App\Table\Addr;
use App\Table\Material;
use App\Table\Entrepreneur;
use Validator;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('islogin:5');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Material::lists();
        $del = (\Cookie::get('ad_permission') == "S") ? true : false;
        return view('material.index', ['data' => $data, 'del' => $del]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $store = Entrepreneur::lists();
        $province = Addr::province_lists();
        return view('material.create', ['province' => $province,'store' => []]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'm_name' => 'required',
            'm_price' => 'required|numeric',
            // 's_id' => 'required',
            'm_unit' => 'required',
            'm_phone' => 'nullable',

            'sm_name' => 'required',
            'm_facebook' => 'nullable',
            'm_line' => 'nullable',
            'm_instagram' => 'nullable',
            'm_lat' => 'nullable',
            'm_long' => 'nullable',
            
            'province_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'sub_district_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $args = [
            'm_name' => ($request->m_name) ? $request->m_name : "",
            'm_price' => ($request->m_price) ? $request->m_price : "",
            // 's_id' => ($request->s_id) ? $request->s_id : "",
            'm_unit' => ($request->m_unit) ? $request->m_unit : "",
            'm_phone' => ($request->m_phone) ? $request->m_phone : "",

            'sm_name' => ($request->sm_name) ? $request->sm_name : "",
            'm_facebook' => ($request->m_facebook) ? $request->m_facebook : "",
            'm_line' => ($request->m_line) ? $request->m_line : "",
            'm_instagram' => ($request->m_instagram) ? $request->m_instagram : "",
            'm_lat' => ($request->m_lat) ? $request->m_lat : "",
            'm_long' => ($request->m_long) ? $request->m_long : "",

            'province_id' => $request->province_id,
            'district_id' => ($request->district_id) ? $request->district_id : "",
            'sub_district_id' => ($request->sub_district_id) ? $request->sub_district_id : "",
            'update_date' => date('Y-m-d H:i:s')
        ];

        $status = Material::insert($args);
        if ($status['status']) {
            $id = $status['id'];
            return redirect("/material/$id/edit")->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => $status['message']));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $store = Entrepreneur::lists();
        $province = Addr::province_lists();
        $data = Material::get_detail($id);
        return view('material.edit', ['data' => $data, 'province' => $province,'store' => []]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'm_name' => 'required',
            'm_price' => 'required|numeric',
            // 's_id' => 'required',
            'm_unit' => 'required',
            'm_phone' => 'nullable',

            'sm_name' => 'required',
            'm_facebook' => 'nullable',
            'm_line' => 'nullable',
            'm_instagram' => 'nullable',
            'm_lat' => 'nullable',
            'm_long' => 'nullable',

            'province_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'sub_district_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $args = [
            'm_name' => ($request->m_name) ? $request->m_name : "",
            'm_price' => ($request->m_price) ? $request->m_price : "",
            // 's_id' => ($request->s_id) ? $request->s_id : "",
            'm_unit' => ($request->m_unit) ? $request->m_unit : "",
            'm_phone' => ($request->m_phone) ? $request->m_phone : "",

            'sm_name' => ($request->sm_name) ? $request->sm_name : "",
            'm_facebook' => ($request->m_facebook) ? $request->m_facebook : "",
            'm_line' => ($request->m_line) ? $request->m_line : "",
            'm_instagram' => ($request->m_instagram) ? $request->m_instagram : "",
            'm_lat' => ($request->m_lat) ? $request->m_lat : "",
            'm_long' => ($request->m_long) ? $request->m_long : "",
            
            'province_id' => $request->province_id,
            'district_id' => ($request->district_id) ? $request->district_id : "",
            'sub_district_id' => ($request->sub_district_id) ? $request->sub_district_id : "",
            'update_date' => date('Y-m-d H:i:s')
        ];

        $status = Material::update($args,$id);
        if ($status['status']) {
            return redirect("/material/$id/edit")->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => $status['message']));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // wait
        if (\Cookie::get('ad_id') == $id) {
            return response()->json([
                'status' => false,
            ]);
        }
        $result = Material::delete($id);
        return response()->json($result);
    }
}

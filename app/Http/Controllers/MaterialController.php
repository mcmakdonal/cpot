<?php

namespace App\Http\Controllers;

use App\Table\Addr;
use App\Table\Material;
use App\Table\Entrepreneur;
use Validator;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Material::lists();
        return view('material.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $store = Entrepreneur::lists();
        $province = Addr::province_lists();
        $data = Material::get_detail($id);
        return view('material.edit', ['data' => $data, 'province' => $province,'store' => $store]);
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
            'm_price' => 'required',
            's_id' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'sub_district_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $args = [
            'm_name' => ($request->m_name) ? $request->m_name : "",
            'm_price' => ($request->m_price) ? $request->m_price : "",
            's_id' => ($request->s_id) ? $request->s_id : "",
            'province_id' => $request->province_id,
            'district_id' => ($request->district_id) ? $request->district_id : "",
            'sub_district_id' => ($request->sub_district_id) ? $request->sub_district_id : "",
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
        //
    }
}

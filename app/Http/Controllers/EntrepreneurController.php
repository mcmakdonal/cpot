<?php

namespace App\Http\Controllers;

use App\Table\Addr;
use App\Table\Entrepreneur;
use Validator;
use Illuminate\Http\Request;

class EntrepreneurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Entrepreneur::lists();
        return view('entrepreneur.index', ['data' => $data]);
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
        $province = Addr::province_lists();
        $data = Entrepreneur::get_detail($id);
        return view('entrepreneur.edit', ['data' => $data, 'province' => $province]);
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
            's_name' => 'required',
            's_onwer' => 'required',
            's_phone' => 'required',
            'province_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $args = [
            'fb_id' => ($request->fb_id) ? $request->fb_id : "",
            's_name' => ($request->s_name) ? $request->s_name : "",
            's_onwer' => ($request->s_onwer) ? $request->s_onwer : "",
            's_phone' => ($request->s_phone) ? $request->s_phone : "",
            's_addr' => ($request->s_addr) ? $request->s_addr : "",
            's_ig' => ($request->s_ig) ? $request->s_ig : "",
            's_line' => ($request->s_line) ? $request->s_line : "",
            'province_id' => $request->province_id,
            'update_date' => date('Y-m-d H:i:s')
        ];

        $status = Entrepreneur::update($args,$id);
        if ($status['status']) {
            return redirect("/entrepreneur/$id/edit")->with('status', 'บันทึกสำเร็จ');
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

<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('tbl_blog')->get();
        foreach ($data as $k => $v) {
            $data[$k]->bg_image = url('/blog/' . $v->bg_image);
        }
        $obj = ['data_object' => $data];
        return $obj;
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
        // dd($request->data);
        $validator = Validator::make($request->all(), [
            'data' => 'required',
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $file = "";
        $data = json_decode($request->data, true);
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $name = md5($file->getClientOriginalName() . " " . date('Y-m-d H:i:s')) . "." . $file->getClientOriginalExtension();
            $file->move(public_path() . '/blog/', $name);
            $file = $name;
        }

        $args = array(
            'bg_title' => $data['bg_title'],
            'bg_description' => $data['bg_description'],
            'bg_tag' => $data['bg_tag'],
            'bg_embed' => $data['bg_embed'],
            'bg_image' => $file,
        );

        DB::beginTransaction();
        $status = DB::table('tbl_blog')->insertGetId($args);
        if ($status) {
            DB::commit();
            return [
                'status' => true,
                'message' => 'Success',
                'id' => $status,
            ];
        } else {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Fail',
            ];
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
        $data = DB::table('tbl_blog')->where('bg_id', $id)->get();
        foreach ($data as $k => $v) {
            $data[$k]->bg_image = url('/blog/' . $v->bg_image);
            $sub_tag = explode(",", $v->bg_tag);
            $matchThese = "where ";
            foreach ($sub_tag as $k_t => $tag) {
                $matchThese .= " pd_name like '%$tag%' or pd_tag like '%$tag%' ";
                if(($k_t + 1) != count($sub_tag)){
                    $matchThese .= " or ";
                }
            }
            $matchThese .= " limit 4";
            $blog = DB::select("select * from tbl_product $matchThese");
            foreach ($blog as $kk => $vv) {
                $blog[$kk]->pd_image = url('/blog/' . $vv->pd_image);
            }
            $data[$k]->product_relate = $blog;
        }
        $obj = ['data_object' => $data];
        return $obj;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        // dd($request);
        $validator = Validator::make($request->all(), [
            'data' => 'required',
            'file' => 'nullable'
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $file = "";
        $data = json_decode($request->data, true);
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $name = md5($file->getClientOriginalName() . " " . date('Y-m-d H:i:s')) . "." . $file->getClientOriginalExtension();
            $file->move(public_path() . '/blog/', $name);
            $file = $name;
        }

        $args = array(
            'bg_title' => $data['bg_title'],
            'bg_description' => $data['bg_description'],
            'bg_tag' => $data['bg_tag'],
            'bg_embed' => $data['bg_embed'],
        );
        if ($file != "") {
            $args['bg_image'] = $file;
        }

        DB::beginTransaction();
        $status = DB::table('tbl_blog')->where('bg_id', $id)->update($args);
        if ($status) {
            DB::commit();
            return [
                'status' => true,
                'message' => 'Success',
            ];
        } else {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Fail',
            ];
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
        if ($id == "" && $id == null) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }
        DB::beginTransaction();
        $status = DB::table('tbl_blog')->where('bg_id', '=', $id)->delete();
        if ($status) {
            DB::commit();
            return [
                'status' => true,
                'message' => 'Success',
            ];
        } else {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }
}

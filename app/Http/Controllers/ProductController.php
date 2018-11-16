<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('tbl_product')->get();
        foreach ($data as $k => $v) {
            $data[$k]->pd_image = url('/files/' . $v->pd_image);
            $data[$k]->category = DB::table('tbl_cat_product')->select('cat_id')->where('pd_id', $v->pd_id)->get();
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
            $file->move(public_path() . '/files/', $name);
            $file = $name;
        }

        $cat = $data['cat_id'];
        $category = [];
        foreach ($cat as $k => $v) {
            $category[]['cat_id'] = $v;
        }

        $args = array(
            'pd_name' => $data['pd_name'],
            'pd_price' => $data['pd_price'],
            'pd_sprice' => $data['pd_sprice'],
            'pd_description' => $data['pd_description'],
            'pd_rating' => $data['pd_rating'],
            'pd_tag' => $data['pd_tag'],
            'pd_ref' => $data['pd_ref'],
            'pd_image' => $file,
        );

        DB::beginTransaction();
        $status = DB::table('tbl_product')->insertGetId($args);
        if ($status) {
            foreach ($category as $k => $v) {
                $category[$k]['pd_id'] = $status;
            }
            $result = DB::table('tbl_cat_product')->insert($category);
            if ($result) {
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

        } else {
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
        $data = DB::table('tbl_product')->where('pd_id', $id)->get();
        foreach ($data as $k => $v) {
            $data[$k]->pd_image = url('/files/' . $v->pd_image);
            $sub_tag = explode(",", $v->pd_tag);
            $matchThese = "where ";
            foreach ($sub_tag as $k_t => $tag) {
                $matchThese .= " bg_title like '%$tag%' or bg_tag like '%$tag%' ";
                if (($k_t + 1) != count($sub_tag)) {
                    $matchThese .= " or ";
                }
            }
            $matchThese .= " limit 4";
            $blog = DB::select("select * from tbl_blog $matchThese");
            foreach ($blog as $kk => $vv) {
                $blog[$kk]->bg_image = url('/blog/' . $vv->bg_image);
            }
            $data[$k]->blog_relate = $blog;
            $data[$k]->category = DB::table('tbl_cat_product')->select('cat_id')->where('pd_id', $id)->get();
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
            'file' => 'nullable',
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
            $file->move(public_path() . '/files/', $name);
            $file = $name;
        }

        $cat = $data['cat_id'];
        $category = [];
        foreach ($cat as $k => $v) {
            $category[]['cat_id'] = $v;
        }


        $args = array(
            'pd_name' => $data['pd_name'],
            'pd_price' => $data['pd_price'],
            'pd_sprice' => $data['pd_sprice'],
            'pd_description' => $data['pd_description'],
            'pd_rating' => $data['pd_rating'],
            'pd_tag' => $data['pd_tag'],
            'pd_ref' => $data['pd_ref'],
        );
        if ($file != "") {
            $args['pd_image'] = $file;
        }

        DB::beginTransaction();
        $status = DB::table('tbl_product')->where('pd_id', $id)->update($args);
        if ($status) {
            foreach ($category as $k => $v) {
                $category[$k]['pd_id'] = $id;
            }
            DB::table('tbl_cat_product')->where('pd_id', $id)->delete();
            $result = DB::table('tbl_cat_product')->insert($category);
            if ($result) {
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

        } else {
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
        $status = DB::table('tbl_product')->where('pd_id', '=', $id)->delete();
        if ($status) {
            return [
                'status' => true,
                'message' => 'Success',
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }
}

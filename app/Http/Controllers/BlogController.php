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
        $select = ['tbl_blog.bg_id', 'tbl_blog.bg_title', 'tbl_blog.bg_description', 'tbl_blog.bg_image', 'tbl_blog.bg_tag', 'tbl_blog.bg_embed', 'tbl_blog.bg_ref', 'tbl_blog.bmc_id', 'tbl_blog.bsc_id', 'tbl_blog.bc_id', 'bmc_name', 'bsc_name', 'bc_name'];
        $data = DB::table('tbl_blog')
            ->select($select)
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->join('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->leftJoin('tbl_blog_category', 'tbl_blog_category.bc_id', '=', 'tbl_blog.bc_id')
            ->groupBy($select)
            ->orderBy('bg_id', 'desc')
            ->get();
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
            'bg_ref' => (array_key_exists("bg_ref", $data)) ? $data['bg_ref'] : "",
            'bmc_id' => $data['bmc_id'],
            'bsc_id' => $data['bsc_id'],
            'bc_id' => $data['bc_id'],
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
        $select = ['tbl_blog.bg_id', 'tbl_blog.bg_title', 'tbl_blog.bg_description', 'tbl_blog.bg_image', 'tbl_blog.bg_tag', 'tbl_blog.bg_embed', 'tbl_blog.bg_ref', 'tbl_blog.bmc_id', 'tbl_blog.bsc_id', 'tbl_blog.bc_id', 'tbl_blog.bg_tag', 'bmc_name', 'bsc_name', 'bc_name'];
        $data = DB::table('tbl_blog')
            ->select($select)
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->join('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->leftJoin('tbl_blog_category', 'tbl_blog_category.bc_id', '=', 'tbl_blog.bc_id')
            ->groupBy($select)
            ->where('bg_id', $id)->get();
        foreach ($data as $k => $v) {
            $data[$k]->bg_image = url('/blog/' . $v->bg_image);
            $sub_tag = explode(",", $v->bg_tag);
            $matchThese = "where ";
            foreach ($sub_tag as $k_t => $tag) {
                $matchThese .= " pd_name like '%$tag%' or pd_tag like '%$tag%' ";
                if (($k_t + 1) != count($sub_tag)) {
                    $matchThese .= " or ";
                }
            }
            $matchThese .= " limit 4";
            $product = DB::select("select pd_id,pd_name,pd_price,pd_sprice,pd_description,pd_image,pd_tag from tbl_product $matchThese");
            foreach ($product as $kk => $vv) {
                $product[$kk]->pd_image = url('/files/' . $vv->pd_image);
            }
            $data[$k]->product_relate = $product;
        }
        foreach ($data as $k => $v) {
            $sub_tag = explode(",", $v->bg_tag);
            $matchThese = "where ";
            foreach ($sub_tag as $k_t => $tag) {
                $matchThese .= " bg_title like '%$tag%' or bg_tag like '%$tag%' ";
                if (($k_t + 1) != count($sub_tag)) {
                    $matchThese .= " or ";
                }
            }
            $matchThese .= " limit 4";
            $blog = DB::select("select bg_id,bg_title,bg_image,bg_tag from tbl_blog $matchThese");
            foreach ($blog as $kk => $vv) {
                $blog[$kk]->bg_image = url('/blog/' . $vv->bg_image);
            }
            $data[$k]->blog_relate = $blog;
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
            $file->move(public_path() . '/blog/', $name);
            $file = $name;
        }

        $args = array(
            'bg_title' => $data['bg_title'],
            'bg_description' => $data['bg_description'],
            'bg_tag' => $data['bg_tag'],
            'bg_embed' => $data['bg_embed'],
            'bg_ref' => (array_key_exists("bg_ref", $data)) ? $data['bg_ref'] : "",
            'bmc_id' => $data['bmc_id'],
            'bsc_id' => $data['bsc_id'],
            'bc_id' => $data['bc_id'],
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

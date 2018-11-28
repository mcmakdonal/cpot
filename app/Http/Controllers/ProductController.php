<?php

namespace App\Http\Controllers;

use App\Table\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Product::list();
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

        $cat = explode(",", $data['cat_id']);
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

        return Product::insert($args);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Product::detail($id);
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

        $cat = explode(",", $data['cat_id']);
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

        return Product::update($args, $id);
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

        return Product::delete($id);
    }
}

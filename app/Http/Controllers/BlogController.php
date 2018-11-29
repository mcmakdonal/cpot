<?php

namespace App\Http\Controllers;

use App\Table\Blog;
use Illuminate\Http\Request;
use Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $obj = ['data_object' => Blog::lists($request->search, $request->bmc_id, $request->bsc_id)];
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
            'bg_image' => $file,
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => 1,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => 1,
            'record_status' => 'A',
        );

        return Blog::insert($args);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Blog::detail($id);
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
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => 1,
            'record_status' => 'A',
        );
        if ($file != "") {
            $args['bg_image'] = $file;
        }

        return Blog::update($args, $id);
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
        return Blog::delete($id);
    }
}

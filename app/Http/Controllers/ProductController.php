<?php

namespace App\Http\Controllers;

use App\Http\JwtService;
use App\Table\Favorite;
use App\Table\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('Jwt', ['except' => [
            'index', 'show',
        ]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $mcat_id = $request->mcat_id;
        $scat_id = $request->scat_id;
        $page = ($request->page == 0 || $request == "") ? 1 : $request->page;
        $data = Product::lists($search, $mcat_id, $scat_id, [], $page);
        return $data;
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

        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];

        $file = "";
        $data = json_decode($request->data, true);
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $name = md5($file->getClientOriginalName() . " " . date('Y-m-d H:i:s')) . "." . $file->getClientOriginalExtension();
            $file->move(public_path() . '/files/', $name);
            $file = $name;
        }

        $args = [];
        $accept = [
            'pd_name', 'pd_price', 'pd_sprice', 'pd_description', 'pd_rating', 'pd_tag', 'pd_ref', 'mcat_id', 'scat_id',
            'pd_store', 'pd_province', 'pd_history', 'pd_featured', 'pd_detail', 'pd_benefits',
        ];
        foreach ($data as $key => $value) {
            if (in_array($key, $accept)) {
                $args[$key] = $value;
            }
        }

        $args = array_merge($args, [
            'pd_image' => $file,
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => $u_id,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $u_id,
            'record_status' => 'A',
        ]);

        return Product::insert($args);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $favorite = false;
        if ($request->header('Authorization') != "") {
            $result = JwtService::de_auth($request);
            if (gettype($result) != "array") {
                die();
            }
            $u_id = $result['u_id'];
            $favorite = Favorite::is_like("P", $id, $u_id);
        }
        $data = Product::detail($id);
        $obj = [
            'data_object' => $data,
            'favorite' => $favorite,
        ];
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

        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];

        $file = "";
        $data = json_decode($request->data, true);
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $name = md5($file->getClientOriginalName() . " " . date('Y-m-d H:i:s')) . "." . $file->getClientOriginalExtension();
            $file->move(public_path() . '/files/', $name);
            $file = $name;
        }

        $args = [];
        $accept = [
            'pd_name', 'pd_price', 'pd_sprice', 'pd_description', 'pd_rating', 'pd_tag', 'pd_ref', 'mcat_id', 'scat_id',
            'pd_store', 'pd_province', 'pd_history', 'pd_featured', 'pd_detail', 'pd_benefits',
        ];
        foreach ($data as $key => $value) {
            if (in_array($key, $accept)) {
                $args[$key] = $value;
            }
        }

        if ($file != "") {
            $args['pd_image'] = $file;
        }

        $args = array_merge($args, [
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $u_id,
        ]);

        return Product::update($args, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];
        if ($id == "" && $id == null) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        return Product::delete($id, $u_id);
    }
}

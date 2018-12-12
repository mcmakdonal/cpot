<?php

namespace App\Http\Controllers;

use App\Http\JwtService;
use App\Table\Blog;
use App\Table\Favorite;
use Illuminate\Http\Request;
use Validator;

class BlogController extends Controller
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
        $bmc_id = $request->bmc_id;
        $bsc_id = $request->bsc_id;
        $page = ($request->page == 0 || $request == "") ? 1 : $request->page;
        $data = Blog::lists($search, $bmc_id, $bsc_id, [], $page);
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
            $file->move(public_path() . '/blog/', $name);
            $file = $name;
        }

        $args = [];
        $accept = [
            'bg_title', 'bg_description', 'bg_tag', 'bg_embed', 'bg_ref', 'bmc_id', 'bsc_id',
            'bg_store', 'bg_featured', 'bg_process', 'bg_detail', 'bg_benefits',
        ];
        foreach ($data as $key => $value) {
            if (in_array($key, $accept)) {
                $args[$key] = $value;
            }
        }

        $args = array_merge($args, [
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => $u_id,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $u_id,
            'record_status' => 'A',
        ]);

        return Blog::insert($args);
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
        $data = Blog::detail($id);
        $obj = ['data_object' => $data, 'favorite' => $favorite];
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

        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];

        $args = [];
        $accept = [
            'bg_title', 'bg_description', 'bg_tag', 'bg_embed', 'bg_ref', 'bmc_id', 'bsc_id',
            'bg_store', 'bg_featured', 'bg_process', 'bg_detail', 'bg_benefits',
        ];
        foreach ($data as $key => $value) {
            if (in_array($key, $accept)) {
                $args[$key] = $value;
            }
        }

        if ($file != "") {
            $args['bg_image'] = $file;
        }

        $args = array_merge($args, [
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $u_id,
        ]);

        return Blog::update($args, $id);
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
        return Blog::delete($id, $u_id);
    }
}

<?php

namespace App\Http\Controllers;

use App\Table\Permission;
use Illuminate\Http\Request;
use Validator;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('islogin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\Helper::instance()->check_role(9)) {
            abort(404);
        }
        $data = Permission::lists();
        return view('permission.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\Helper::instance()->check_role(9)) {
            abort(404);
        }
        $role = Permission::$role;
        return view('permission.create', ['role' => $role]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!\Helper::instance()->check_role(9)) {
            abort(404);
        }
        $validator = Validator::make($request->all(), [
            'per_name' => 'required',
            'per_role' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $per_role = [];
        if (count($request->per_role) > 0) {
            foreach ($request->per_role as $value) {
                array_push($per_role, $value);
            }
        }

        $args = [
            'per_name' => $request->per_name,
            'per_role' => json_encode($per_role),
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => \Cookie::get('ad_id'),
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => \Cookie::get('ad_id'),
            'record_status' => 'A',
        ];

        $status = Permission::insert($args);
        if ($status['status']) {
            $id = $status['id'];
            return redirect("/permission/$id/edit")->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => 'error'));
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
        if (!\Helper::instance()->check_role(9)) {
            abort(404);
        }
        $data = Permission::get($id);
        $role = Permission::$role;
        return view('permission.edit', ['data' => $data, 'role' => $role]);
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
        if (!\Helper::instance()->check_role(9)) {
            abort(404);
        }
        $validator = Validator::make($request->all(), [
            'per_name' => 'required',
            'per_role' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $per_role = [];
        if ($request->per_role != null) {
            if (count($request->per_role) > 0) {
                foreach ($request->per_role as $value) {
                    array_push($per_role, $value);
                }
            }
        }

        $args = [
            'per_name' => $request->per_name,
            'per_role' => json_encode($per_role),
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => \Cookie::get('ad_id'),
        ];

        $status = Permission::update($args, $id);
        if ($status['status']) {
            return redirect("/permission/$id/edit")->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => 'error'));
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
        if (!\Helper::instance()->check_role(9)) {
            abort(404);
        }
        if(Permission::check_in_use($id)){
            return response()->json([
                'status' => false,
                'message' => 'ถูกใช้งานอยู่ ไม่สามารถลบได้'
            ]);
        }
        $result = Permission::delete($id);
        return response()->json($result);
    }
}

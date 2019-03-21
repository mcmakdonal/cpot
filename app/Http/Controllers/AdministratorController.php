<?php

namespace App\Http\Controllers;

use App\Table\Admin;
use App\Table\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AdministratorController extends Controller
{
    public function __construct()
    {
        $this->middleware('islogin', ['except' => [
            'check_login', 'forget_password', 'show', 'update_profile', 'edit_password', 'update_password',
        ]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\Helper::instance()->check_role(8)) {
            abort(404);
        }
        $data = Admin::lists();
        return view('administrator.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\Helper::instance()->check_role(8)) {
            abort(404);
        }
        // $role = Admin::$role;
        $per = Permission::lists();
        return view('administrator.create', ['per' => $per]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!\Helper::instance()->check_role(8)) {
            abort(404);
        }
        $validator = Validator::make($request->all(), [
            'ad_firstname' => 'required',
            'ad_lastname' => 'required',
            'ad_password' => 'required',
            'ad_username' => 'required',
            'ad_email' => 'required|unique:tbl_administrator,ad_email',
            'ad_phone' => 'required|numeric',
            'ad_ogz' => 'required',
            'per_id' => 'required',
            // 'ad_permission' => 'required',
            // 'ad_role' => 'nullable',
            'conf_password' => 'required',
        ], [
            'ad_email.unique' => 'อีเมลนี้ถูกใช้ไปแล้ว กรุณาใช้อีเมลใหม่',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->ad_password != $request->conf_password) {
            return redirect()->back()->withErrors(array('error' => 'Password not macth !'));
        }

        // $ad_role = [];
        // if (count($request->ad_role) > 0) {
        //     foreach ($request->ad_role as $key => $value) {
        //         array_push($ad_role, $value);
        //     }
        // }

        $args = [
            'ad_firstname' => $request->ad_firstname,
            'ad_lastname' => $request->ad_lastname,
            'ad_username' => $request->ad_username,
            'ad_password' => Hash::make($request->ad_password),
            'ad_email' => $request->ad_email,
            'ad_phone' => $request->ad_phone,
            'ad_ogz' => $request->ad_ogz,
            'per_id' => $request->per_id,
            // 'ad_permission' => $request->ad_permission,
            // 'ad_role' => json_encode($ad_role),
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => \Cookie::get('ad_id'),
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => \Cookie::get('ad_id'),
            'record_status' => 'A',
        ];

        $status = Admin::insert($args);
        if ($status['status']) {
            $id = $status['id'];
            return redirect("/administrator/$id/edit")->with('status', 'บันทึกสำเร็จ');
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
    public function show($id = "")
    {
        if (!(\Cookie::get('ad_id') !== null)) {
            abort(404);
        }
        $id = \Cookie::get('ad_id');
        $data = Admin::get_admin($id);
        return view('administrator.profile', ['data' => $data]);
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ad_firstname' => 'required',
            'ad_lastname' => 'required',
            'ad_phone' => 'required|numeric',
            'ad_ogz' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $id = \Cookie::get('ad_id');
        $args = [
            'ad_firstname' => $request->ad_firstname,
            'ad_lastname' => $request->ad_lastname,
            'ad_phone' => $request->ad_phone,
            'ad_ogz' => $request->ad_ogz,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => \Cookie::get('ad_id'),
        ];

        $status = Admin::update($args, $id);
        if ($status['status']) {
            return redirect("/administrator/profile")->cookie('ad_firstname', $request->ad_firstname . " " . $request->ad_lastname, 14660)->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => 'error'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!\Helper::instance()->check_role(8)) {
            abort(404);
        }
        $data = Admin::get_admin($id);
        // $role = Admin::$role;
        $per = Permission::lists();
        return view('administrator.edit', ['data' => $data, 'per' => $per]);
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
        if (!\Helper::instance()->check_role(8)) {
            abort(404);
        }
        $validator = Validator::make($request->all(), [
            'ad_firstname' => 'required',
            'ad_lastname' => 'required',
            'ad_phone' => 'required|numeric',
            'ad_ogz' => 'required',
            // 'ad_permission' => 'required',
            // 'ad_role' => 'nullable',
            'per_id' => 'required',
            'ad_password' => 'nullable',
            'conf_password' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->ad_password != $request->conf_password) {
            return redirect()->back()->withErrors(array('error' => 'Password not macth !'));
        }

        // $ad_role = [];
        // if ($request->ad_role != null) {
        //     if (count($request->ad_role) > 0) {
        //         foreach ($request->ad_role as $key => $value) {
        //             array_push($ad_role, $value);
        //         }
        //     }
        // }

        $args = [
            'ad_firstname' => $request->ad_firstname,
            'ad_lastname' => $request->ad_lastname,
            'ad_phone' => $request->ad_phone,
            'ad_ogz' => $request->ad_ogz,
            // 'ad_permission' => $request->ad_permission,
            // 'ad_role' => json_encode($ad_role),
            'per_id' => $request->per_id,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => \Cookie::get('ad_id'),
        ];

        if ($request->ad_password != "") {
            $args['ad_password'] = Hash::make($request->ad_password);
        }

        $status = Admin::update($args, $id);
        if ($status['status']) {
            return redirect("/administrator/$id/edit")->with('status', 'บันทึกสำเร็จ');
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
        if (!\Helper::instance()->check_role(8)) {
            abort(404);
        }
        if ($id === '1' || \Cookie::get('ad_id') == $id) {
            return response()->json([
                'status' => false,
            ]);
        }
        $result = Admin::delete($id);
        return response()->json($result);
    }

    public function check_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:250',
            'password' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors(array('error' => 'error'));
        }

        $user = Admin::get_admin("", $request->username);
        if (count($user) === 0) {
            return redirect()->back()->withErrors([
                'status' => false,
                'message' => 'Not found Username',
            ]);
        }

        if (Hash::check($request->password, $user[0]->ad_password)) {
            return redirect("/administrator/profile")
                ->cookie('ad_id', $user[0]->ad_id, 14660)
                // ->cookie('ad_permission', $user[0]->ad_permission, 14660)
                // ->cookie('ad_role', $user[0]->ad_role, 14660)
                ->cookie('ad_firstname', $user[0]->ad_firstname . " " . $user[0]->ad_lastname, 14660)
                ->cookie('per_id', $user[0]->per_id, 14660);
        } else {
            return redirect()->back()->withErrors(array('error' => 'Username or Password Incorrect'));
        }
    }

    public function forget_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json(array('error' => 'error'));
        }

        $user = Admin::forget_password($request->email);
        return response()->json($user);
    }

    public function edit_password()
    {
        if (!(\Cookie::get('ad_id') !== null)) {
            abort(404);
        }
        $id = \Cookie::get('ad_id');
        $data = Admin::get_admin($id);
        return view('administrator.cpassword', ['data' => $data]);
    }

    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ad_password' => 'required|string|max:250',
            'conf_password' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->ad_password != $request->conf_password) {
            return redirect()->back()->withErrors(array('error' => 'Password not macth !'));
        }

        $args = [
            'ad_password' => Hash::make($request->ad_password),
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => \Cookie::get('ad_id'),
        ];

        $status = Admin::update($args, \Cookie::get('ad_id'));
        if ($status['status']) {
            return redirect("/change-password")->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => 'error'));
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Table\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Admin::list();
        return view('administrator.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrator.create');
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
            'ad_firstname' => 'required',
            'ad_lastname' => 'required',
            'ad_password' => 'required',
            'ad_username' => 'required',
            'conf_password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->ad_password != $request->conf_password) {
            return redirect()->back()->withErrors(array('error' => 'Password not macth !'));
        }

        $args = [
            'ad_firstname' => $request->ad_firstname,
            'ad_lastname' => $request->ad_lastname,
            'ad_username' => $request->ad_username,
            'ad_password' => Hash::make($request->ad_password),
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => 1,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => 1,
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
        $data = Admin::get_admin($id);
        return view('administrator.edit', ['data' => $data]);
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
            'ad_firstname' => 'required',
            'ad_lastname' => 'required',
            'ad_password' => 'nullable',
            'conf_password' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->ad_password != $request->conf_password) {
            return redirect()->back()->withErrors(array('error' => 'Password not macth !'));
        }

        $args = [
            'ad_firstname' => $request->ad_firstname,
            'ad_lastname' => $request->ad_lastname,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => 1,
            'record_status' => 'A',
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
        if ($id === '1') {
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
        // dd($user);

        if (Hash::check($request->password, $user[0]->ad_password)) {
            return redirect("/administrator")->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => 'Username or Password Incorrect'));
        }
    }
}

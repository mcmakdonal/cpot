<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;
use App\Table\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'u_identity' => 'required',
            'u_owner' => 'required',
            'u_email' => 'required',
            'u_password' => 'required',
            'u_phone' => 'required',
            'u_store' => 'required',
            'u_addr' => 'required',
            'u_province' => 'required',
            'u_district' => 'required',
            'u_subdistrcit' => 'required',
            'u_zipcode' => 'required',
            'u_community' => 'nullable',
            'u_lat' => 'nullable',
            'u_long' => 'nullable',
            'u_desc' => 'nullable',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $check = User::check_email_exists($request->u_email);
        if ($check['status']) {
            $obj = ['data_object' => $check];
            return $obj;
        }

        $args = array(
            'u_identity' => $request->u_identity,
            'u_owner' => $request->u_owner,
            'u_email' => $request->u_email,
            'u_password' => Hash::make($request->u_password),
            'u_phone' => $request->u_phone,
            'u_store' => $request->u_store,
            'u_addr' => $request->u_addr,
            'u_province' => $request->u_province,
            'u_district' => $request->u_district,
            'u_subdistrcit' => $request->u_subdistrcit,
            'u_zipcode' => $request->u_zipcode,
            'u_community' => $request->u_community,
            'u_lat' => $request->u_lat,
            'u_long' => $request->u_long,
            'u_desc' => $request->u_desc
        );

        return User::insert($args);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function check_email(Request $request){
        $email = $request->email;
        $obj = [
            'data_object' => User::check_email_exists($email)
        ];
        return $obj;
    }

    public function check_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:250',
            'password' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $user = User::get_user($request->email);
        if (count($user) === 0) {
            return [
                'status' => false,
                'message' => 'Username or Password Incorrect',
            ];
        }

        if (Hash::check($request->password, $user[0]->u_password)) {
            unset($user[0]->u_password);
            return [
                'status' => true,
                'message' => 'Success',
                'data_object' => $user
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Username or Password Incorrect',
            ];
        }
    }
}

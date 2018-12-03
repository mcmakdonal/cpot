<?php

namespace App\Http\Controllers;

use App\Http\JwtService;
use App\Table\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('Jwt', ['except' => [
            'store', 'check_email', 'check_login',
        ]]);
    }
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

        $args = [];
        $ignore = [];
        $all_request = $request->all();
        foreach ($request->all() as $key => $value) {
            if (!in_array($key, $ignore)) {
                if ($key == "u_password") {
                    $value = Hash::make($value);
                    $args[$key] = $value;
                } else {
                    $args[$key] = $value;
                }
            }
        }

        $args = array_merge($args, [
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => 1,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => 1,
            'record_status' => 'A',
        ]);

        return User::insert($args);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];
        $user = User::get_user("", $u_id);
        $obj = ['data_object' => $user];
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
    public function update(Request $request, $id = "")
    {
        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];
        $validator = Validator::make($request->all(), [
            'u_owner' => 'nullable',
            'u_password' => 'nullable',
            'u_phone' => 'nullable',
            'u_store' => 'nullable',
            'u_addr' => 'nullable',
            'u_province' => 'nullable',
            'u_district' => 'nullable',
            'u_subdistrcit' => 'nullable',
            'u_zipcode' => 'nullable',
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

        $args = [];
        $ignore = ['u_identity', 'u_email'];
        $all_request = $request->all();
        foreach ($request->all() as $key => $value) {
            if (!in_array($key, $ignore)) {
                if ($key == "u_password") {
                    $value = Hash::make($value);
                    $args[$key] = $value;
                } else {
                    $args[$key] = $value;
                }
            }
        }

        $args = array_merge($args, ['update_date' => date('Y-m-d H:i:s'), 'update_by' => $u_id]);

        return User::update($args, $u_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];
        if ($u_id == "" && $u_id == null) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }
        return User::delete($u_id);
    }

    public function check_email(Request $request)
    {
        $email = $request->email;
        $obj = [
            'data_object' => User::check_email_exists($email),
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
                'data_object' => $user,
                'token' => JwtService::auth(['u_id' => $user[0]->u_id]),
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Username or Password Incorrect',
            ];
        }
    }
}

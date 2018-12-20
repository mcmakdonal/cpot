<?php

namespace App\Http\Controllers;

use App\Http\JwtService;
use App\Table\Send_mail;
use App\Table\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('Jwt', ['except' => [
            'store', 'check_email', 'check_login', 'register_facebook', 'forget_password',
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
        // $validator = Validator::make($request->all(), [
        //     'u_fullname' => 'required',
        //     'u_profile' => 'required',
        //     'u_email' => 'required',
        //     'u_phone' => 'required',
        //     'u_password' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return [
        //         'status' => false,
        //         'message' => 'Please fill all data',
        //     ];
        // }

        $check = User::check_email_exists($request->u_email);
        if ($check['status']) {
            $obj = ['data_object' => $check];
            return $obj;
        }

        $args = [];
        $accept = ['u_fullname', 'u_profile', 'u_email', 'u_phone', 'u_password'];
        foreach ($request->all() as $key => $value) {
            if (in_array($key, $accept)) {
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

        $user = User::insert($args);
        if ($user['status']) {
            $email = $request->u_email;
            $mess = " การลงทะเบียนสมาชิกของคุณสำเร็จแล้ว!  MCulture Mobile ขอขอบคุณท่านที่ลงทะเบียนเพื่อใช้งานแอพพลิเคชั่น โดยการใช้อีเมล์ $email ของท่านในการติดต่อและส่งข้อมูลต่างๆระหว่างท่านกับกระทรวง ตามเงื่อนไขและข้อตกลงการใช้งานแอพพลิเคชั่น ขอแสดงความนับถือ";
            Send_mail::nofti_register($request->u_fullname, $request->u_email, $mess);
        }
        return $user;
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
        foreach ($user as $k => $v) {
            if (!strpos($v->u_profile, 'http')) {
                $v->u_profile = url($v->u_profile);
            }
        }
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
            $file->move(public_path() . '/profile/', $name);
            $file = "/profile/" . $name;
        }

        $args = [];
        $accept = ['u_fullname', 'u_phone', 'u_password'];
        foreach ($data as $key => $value) {
            if (in_array($key, $accept)) {
                if ($key == "u_password") {
                    $value = Hash::make($value);
                    $args[$key] = $value;
                } else {
                    $args[$key] = $value;
                }
            }
        }

        if ($file != "") {
            $args['u_profile'] = $file;
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
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

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

    public function forget_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
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
                'message' => 'Email Not Found !',
            ];
        } elseif ($user[0]->fb_id != "") {
            return [
                'status' => false,
                'message' => 'This User Login With Facebook Account !',
            ];
        } else {
            $u_id = $user[0]->u_id;
            $to_name = $user[0]->u_fullname;
            $to_email = $user[0]->u_email;
            $password = User::generate_random_letters(8);
            $args = [
                'u_password' => Hash::make($password),
                'update_date' => date('Y-m-d H:i:s'),
                'update_by' => $u_id,
            ];
            $result = User::update($args, $u_id);
            if ($result['status']) {
                $message = "This is a new password for login : $password";
                return Send_mail::send_mail($to_name, $to_email, $message);
            } else {
                return [
                    'status' => false,
                    'message' => 'Update Password not success Please contact Administrator !',
                ];
            }
        }
    }

    public function register_facebook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fb_id' => 'required',
            'u_fullname' => 'required',
            'u_profile' => 'required',
            'u_email' => 'required',
            'u_phone' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $check = User::check_email_exists($request->u_email);
        if ($check['status']) {
            $obj = [
                'data_object' => $check,
                'token' => JwtService::auth(['u_id' => $check['user_data'][0]->u_id]),
            ];
            return $obj;
        }

        $args = [];
        $accept = ['fb_id', 'u_fullname', 'u_profile', 'u_email', 'u_phone'];
        foreach ($request->all() as $key => $value) {
            if (in_array($key, $accept)) {
                $args[$key] = $value;
            }
        }

        $args = array_merge($args, [
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => 1,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => 1,
            'record_status' => 'A',
        ]);

        $user = User::insert($args);
        if ($user['status']) {
            $email = $request->u_email;
            $mess = " การลงทะเบียนสมาชิกของคุณสำเร็จแล้ว!  MCulture Mobile ขอขอบคุณท่านที่ลงทะเบียนเพื่อใช้งานแอพพลิเคชั่น โดยการใช้อีเมล์ $email ของท่านในการติดต่อและส่งข้อมูลต่างๆระหว่างท่านกับกระทรวง ตามเงื่อนไขและข้อตกลงการใช้งานแอพพลิเคชั่น ขอแสดงความนับถือ";
            Send_mail::nofti_register($request->u_fullname, $request->u_email, $mess);
        }
        return [
            'data_object' => $user,
            'token' => JwtService::auth(['u_id' => $user['id']]),
        ];
    }
}

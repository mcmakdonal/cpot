<?php

namespace App\Table;

use App\Table\Send_mail;
use App\Table\User;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class Admin extends ServiceProvider
{
    public static $role = [
        [
            'id' => 1,
            'name' => 'การจัดการ Youtube และ สินค้า',
        ], [
            'id' => 2,
            'name' => 'การจัดการแบบประเมิน',
        ], [
            'id' => 3,
            'name' => 'การจัดการแบล็คกราว',
        ], [
            'id' => 4,
            'name' => 'การจัดการผู้ประกอบการ',
        ], [
            'id' => 5,
            'name' => 'การจัดการทรัพยากร',
        ], [
            'id' => 6,
            'name' => 'รายงาน',
        ], [
            'id' => 7,
            'name' => 'คู่มือการใช้งาน',
        ],
    ];

    public static function check_username_exists($ad_username, $ad_id)
    {
        $matchThese[] = ['ad_username', '=', $ad_username];
        $matchThese[] = ['ad_id', '!=', $ad_id];
        $matchThese[] = ['record_status', '=', 'A'];
        $data = DB::table('tbl_administrator')
            ->select('ad_id')
            ->where($matchThese)
            ->get();
        $exists = [
            'status' => true,
            'message' => 'Username already exists',
        ];

        $notexists = [
            'status' => false,
            'message' => 'Username Can use',
        ];
        return (count($data) > 0) ? $exists : $notexists;
    }

    public static function lists()
    {
        $matchThese[] = ['record_status', '=', 'A'];
        $data = DB::table('tbl_administrator')
            ->select('*')
            ->where($matchThese)
            ->orderBy('ad_id', 'desc')
            ->get()->toArray();

        return $data;
    }

    public static function get_admin($id = "", $username = "")
    {
        $matchThese[] = ['record_status', '=', 'A'];
        if ($id == "") {
            $matchThese[] = ['ad_username', '=', $username];
        } else {
            $matchThese[] = ['ad_id', '=', $id];
        }
        $data = DB::table('tbl_administrator')
            ->select('*')
            ->where($matchThese)
            ->get()->toArray();

        return (count($data) > 0) ? $data : [];
    }

    public static function forget_password($email)
    {
        $matchThese[] = ['record_status', '=', 'A'];
        $matchThese[] = ['ad_email', '=', $email];
        $data = DB::table('tbl_administrator')
            ->select('ad_id', 'ad_firstname')
            ->where($matchThese)
            ->get()->toArray();

        $result = (count($data) > 0) ? true : false;
        if ($result) {
            $password = User::generate_random_letters(8);
            $args = [
                'ad_password' => Hash::make($password),
                'update_date' => date('Y-m-d H:i:s'),
            ];
            $update = Admin::update($args, $data[0]->ad_id);
            $body = "รหัสผ่านใหม่สำหรับเข้าสู่ระบบคือ : $password เมื่อเข้าระบบแล้ว กรุณาเปลี่ยนรหัสผ่านใหม่";
            return send_mail::send_mail($data[0]->ad_firstname, $email, $body);
        } else {
            return [
                'status' => false,
                'message' => 'ไม่พบ อีเมล นี้ในระบบ',
            ];
        }
    }

    public static function insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_administrator')->insertGetId($args);
        if ($status) {
            DB::commit();
            return [
                'status' => true,
                'message' => 'Success',
                'id' => $status,
            ];
        } else {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }

    public static function update($args, $id)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_administrator')->where('ad_id', $id)->update($args);
        if ($status) {
            DB::commit();
            return [
                'status' => true,
                'message' => 'Success',
            ];
        } else {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }

    public static function delete($id)
    {
        DB::beginTransaction();
        $args = [
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => 1,
            'record_status' => 'I',
        ];
        $status = DB::table('tbl_administrator')->where('ad_id', $id)->update($args);
        if ($status) {
            DB::commit();
            return [
                'status' => true,
                'message' => 'Success',
            ];
        } else {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }
}

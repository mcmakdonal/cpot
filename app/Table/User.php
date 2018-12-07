<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class User extends ServiceProvider
{
    public static function check_email_exists($email)
    {
        $matchThese = [];
        $matchThese[] = ['tbl_user.record_status', '=', 'A'];
        $matchThese[] = ['tbl_user.u_email', '=', $email];
        $data = DB::table('tbl_user')
            ->select('u_id', 'fb_id', 'u_fullname', 'u_profile', 'u_email', 'u_phone')
            ->where($matchThese)
            ->get();
        $exists = [
            'status' => true,
            'message' => 'Email Already Exists',
            'user_data' => $data
        ];

        $notexists = [
            'status' => false,
            'message' => 'Email Not Exists',
            'user_data' => [],
        ];
        return (count($data) > 0) ? $exists : $notexists;
    }

    public static function get_user($email, $id = "")
    {
        $matchThese = [];
        $matchThese[] = ['tbl_user.record_status', '=', 'A'];
        if ($id == "") {
            $matchThese[] = ['tbl_user.u_email', '=', $email];
        } else {
            $matchThese[] = ['tbl_user.u_id', '=', $id];
        }

        $data = DB::table('tbl_user')
            ->select('u_id', 'fb_id', 'u_fullname', 'u_profile', 'u_email', 'u_phone','u_password')
            ->where($matchThese)
            ->get()->toArray();

        return (count($data) > 0) ? $data : [];
    }

    public static function insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_user')->insertGetId($args);
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
        $status = DB::table('tbl_user')->where('u_id', $id)->update($args);
        if ($status) {
            DB::commit();
            return [
                'status' => true,
                'message' => 'Success',
            ];
        } else {
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
            'update_by' => $id,
            'record_status' => 'I',
        ];
        $status = DB::table('tbl_user')->where('u_id', $id)->update($args);
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


    ///////////////////////////////////////////////////

    public static function generate_random_letters($length) {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= chr(rand(ord('a'), ord('z')));
        }
        return $random;
    }
}

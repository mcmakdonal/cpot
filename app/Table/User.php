<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class User extends ServiceProvider
{
    public static function check_email_exists($email)
    {
        $matchThese[] = ['tbl_user.u_email', '=', $email];
        $data = DB::table('tbl_user')
            ->select('u_id')
            ->where($matchThese)
            ->get();
        $exists = [
            'status' => true,
            'message' => 'Email already exists',
        ];

        $notexists = [
            'status' => false,
            'message' => 'Email Can use',
        ];
        return (count($data) > 0) ? $exists : $notexists;
    }

    public static function get_user($email, $id = "")
    {
        if ($id == "") {
            $matchThese[] = ['tbl_user.u_email', '=', $email];
        } else {
            $matchThese[] = ['tbl_user.u_id', '=', $id];
        }

        $data = DB::table('tbl_user')
            ->select('*')
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
}

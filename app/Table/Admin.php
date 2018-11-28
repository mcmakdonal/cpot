<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Admin extends ServiceProvider
{
    public static function check_username_exists($email)
    {
        $matchThese[] = ['ad_username', '=', $email];
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

    public static function list() {
        $matchThese[] = ['record_status', '=', 'A'];
        $data = DB::table('tbl_administrator')
            ->select('*')
            ->where($matchThese)
            ->orderBy('ad_id', 'desc')
            ->get()->toArray();

        return $data;
    }

    public static function get_admin($id = "",$username = "")
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
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }

    public static function delete($id){
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

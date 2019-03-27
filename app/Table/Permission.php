<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Permission extends ServiceProvider
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
        ], [
            'id' => 8,
            'name' => 'จัดการผู้ดูแลระบบ',
        ], [
            'id' => 9,
            'name' => 'จัดการสิทธิ์การใช้งาน',
        ], [
            'id' => 10,
            'name' => 'สามารถแก้ไขข้อมูลส่วนตัวได้ทั้งหมด',
        ],
    ];

    public static function lists()
    {
        $matchThese[] = ['record_status', '=', 'A'];
        $data = DB::table('tbl_permission')
            ->select('*')
            ->where($matchThese)
            ->orderBy('per_id', 'desc')
            ->get()->toArray();

        return $data;
    }

    public static function get($id = "")
    {
        $matchThese[] = ['record_status', '=', 'A'];
        $matchThese[] = ['per_id', '=', $id];
        $data = DB::table('tbl_permission')
            ->select('*')
            ->where($matchThese)
            ->get()->toArray();

        return (count($data) > 0) ? $data : [];
    }

    public static function check_in_use($per_id)
    {
        $matchThese[] = ['record_status', '=', 'A'];
        $matchThese[] = ['per_id', '=', $per_id];
        $data = DB::table('tbl_administrator')
            ->select('*')
            ->where($matchThese)
            ->get()->toArray();

        return (count($data) > 0) ? true : false;
    }

    public static function insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_permission')->insertGetId($args);
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
        $status = DB::table('tbl_permission')->where('per_id', $id)->update($args);
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
        $status = DB::table('tbl_permission')->where('per_id', $id)->update($args);
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

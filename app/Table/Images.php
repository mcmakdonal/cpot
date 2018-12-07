<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Images extends ServiceProvider
{

    public static function lists($type = "ads", $active = "")
    {
        $matchThese = [];
        $matchThese[] = ['type', '=', $type];
        if ($active != "") {
            $matchThese[] = ['active', '=', $active];
        }
        $matchThese[] = ['record_status', '=', 'A'];
        $data = DB::table('tbl_images_mobile')
            ->select('*')
            ->where($matchThese)
            ->orderBy('id', 'desc')
            ->get()->toArray();

        return $data;
    }

    public static function insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_images_mobile')->insert($args);
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

    public static function active($id, $active, $type = "")
    {
        $count = self::lists($type, "A");
        if ($active == "I" && count($count) == 1) {
            return [
                'status' => false,
                'message' => 'Fail Because minimun active is 1',
            ];
        }

        DB::beginTransaction();
        $status = DB::table('tbl_images_mobile')->where('id', $id)->update(['active' => $active, 'update_date' => date('Y-m-d H:i:s')]);
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
        $status = DB::table('tbl_images_mobile')->where('id', $id)->update($args);
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

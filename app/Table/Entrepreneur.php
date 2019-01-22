<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class Entrepreneur extends ServiceProvider
{
    public static function lists()
    {
        // $matchThese[] = ['record_status', '=', 'A'];
        $data = DB::table('tbl_store')
            ->select('*')
        // ->where($matchThese)
            ->orderBy('s_id', 'desc')
            ->get()->toArray();

        return $data;
    }

    public static function get_detail($id = "")
    {
        // $matchThese[] = ['record_status', '=', 'A'];
        $matchThese[] = ['s_id', '=', $id];
        $data = DB::table('tbl_store')
            ->select('*')
            ->where($matchThese)
            ->get()->toArray();

        return (count($data) > 0) ? $data : [];
    }

    public static function insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_store')->insertGetId($args);
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
        $status = DB::table('tbl_store')->where('s_id', $id)->update($args);
        Log::info($status);
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
        $status = DB::table('tbl_store')->where('s_id', $id)->update($args);
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

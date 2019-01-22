<?php

namespace App\Table;

use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class Material extends ServiceProvider
{
    public static function lists()
    {
        $data = DB::table('tbl_material')
            ->select('tbl_material.*','tbl_store.s_name')
            ->join('tbl_store', 'tbl_store.s_id', '=', 'tbl_material.s_id')
            ->orderBy('m_id', 'desc')
            ->get()->toArray();

        return $data;
    }

    public static function get_detail($id = "")
    {
        $matchThese[] = ['tbl_material.m_id', '=', $id];
        $data = DB::table('tbl_material')
            ->select('tbl_material.*')
            ->join('tbl_store', 'tbl_store.s_id', '=', 'tbl_material.s_id')
            ->where($matchThese)
            ->get()->toArray();

        return (count($data) > 0) ? $data : [];
    }

    public static function insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_material')->insertGetId($args);
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
        $status = DB::table('tbl_material')->where('m_id', $id)->update($args);
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
        $status = DB::table('tbl_material')->where('m_id', $id)->update($args);
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

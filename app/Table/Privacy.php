<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Privacy extends ServiceProvider
{
    public static function lists()
    {
        $matchThese[] = ['record_status', '=', 'A'];
        $data = DB::table('tbl_privacy')
            ->select('p_id','p_choice')
            ->where($matchThese)
            ->get()->toArray();

        return $data;
    }

    public static function insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_privacy')->insert($args);
        if ($status) {
            DB::commit();
            return [
                'status' => true,
                'message' => 'Success'
            ];
        } else {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }

    public static function delete($id, $u_id)
    {
        DB::beginTransaction();
        $args = [
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $u_id,
            'record_status' => 'I',
        ];
        $status = DB::table('tbl_privacy')->where('p_id', $id)->update($args);
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

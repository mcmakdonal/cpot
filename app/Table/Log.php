<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Log extends ServiceProvider
{
    public static function lists($type = 'pd',$pros = 'share')
    {
        $matchThese[] = ['record_status', '=', 'A'];
        $data = DB::table('tbl_log_user')
            ->select('*')
            ->where($matchThese)
            ->get()->toArray();

        return $data;
    }

    public static function insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_log_user')->insert($args);
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

<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Log extends ServiceProvider
{
    public static function lists($type = 'pd', $pros = 'share')
    {
        $matchThese[] = ['record_status', '=', 'A'];
        $data = DB::table('tbl_log_user')
            ->select('*')
            ->where($matchThese)
            ->get()->toArray();

        return $data;
    }

    public static function share_lists()
    {
        $matchThese = [];
        $matchThese[] = ['tbl_product.record_status', '=', 'A'];
        $matchThese[] = ['tbl_log_user.type', '=', 'pd'];
        $matchThese[] = ['tbl_log_user.pros', '=', 'share'];
        $product = DB::table('tbl_log_user')
            ->select('l_id', 'type', 'pd_name as title', DB::raw('count(tbl_product.pd_id) counter'))
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_log_user.id')
            ->where($matchThese)
            ->groupBy('tbl_product.pd_id')
            ->get()->toArray();

        $matchThese = [];
        $matchThese[] = ['tbl_blog.record_status', '=', 'A'];
        $matchThese[] = ['tbl_log_user.type', '=', 'bg'];
        $matchThese[] = ['tbl_log_user.pros', '=', 'share'];
        $blog = DB::table('tbl_log_user')
            ->select('l_id', 'type', 'bg_title as title', DB::raw('count(tbl_blog.bg_id) counter'))
            ->join('tbl_blog', 'tbl_blog.bg_id', '=', 'tbl_log_user.id')
            ->where($matchThese)
            ->groupBy('tbl_blog.bg_id')
            ->get()->toArray();

        $matchThese = [];
        $matchThese[] = ['tbl_youtube.record_status', '=', 'A'];
        $matchThese[] = ['tbl_log_user.type', '=', 'yt'];
        $matchThese[] = ['tbl_log_user.pros', '=', 'share'];
        $youtube = DB::table('tbl_log_user')
            ->select('l_id', 'type', 'my_title as title', DB::raw('count(tbl_youtube.my_id) counter'))
            ->join('tbl_youtube', 'tbl_youtube.my_id', '=', 'tbl_log_user.id')
            ->where($matchThese)
            ->groupBy('tbl_youtube.my_id')
            ->get()->toArray();

        $data = array_merge($product,$blog,$youtube);

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

    public static function token_insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_device_token')->insert($args);
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

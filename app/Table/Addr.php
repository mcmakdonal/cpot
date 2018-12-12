<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Addr extends ServiceProvider
{
    public static function province_lists($id = "")
    {
        $matchThese = [];
        if ($id != "") {
            $matchThese[] = ['tbl_province.province_id', '=', "$id"];
        }

        $data = DB::table('tbl_province')
            ->select('*')
            ->where($matchThese)
            ->orderBy('tbl_province.province_id', 'ASC')
            ->get()->toArray();

        return $data;
    }

    public static function district_lists($id = "")
    {
        $matchThese = [];
        if ($id != "") {
            $matchThese[] = ['tbl_district.province_id', '=', "$id"];
        }

        $data = DB::table('tbl_district')
            ->select('*')
            ->where($matchThese)
            ->orderBy('tbl_district.district_id', 'ASC')
            ->get()->toArray();

        return $data;
    }

    public static function sub_district_lists($id = "")
    {
        $matchThese = [];
        if ($id != "") {
            $matchThese[] = ['tbl_sub_district.district_id', '=', "$id"];
        }

        $data = DB::table('tbl_sub_district')
            ->select('*')
            ->where($matchThese)
            ->orderBy('tbl_sub_district.district_id', 'ASC')
            ->get()->toArray();

        return $data;
    }

}

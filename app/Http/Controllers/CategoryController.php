<?php

namespace App\Http\Controllers;

use DB;

class CategoryController extends Controller
{
    function list() {
        $data = DB::table('tbl_main_category')->get();
        foreach ($data as $k => $v) {
            $data[$k]->sub_cat = DB::table('tbl_sub_category')->select('scat_id', 'scat_name')->where('mcat_id', $v->mcat_id)->get()->toArray();
        }
        $obj = ['data_object' => $data];
        return $obj;
    }
}

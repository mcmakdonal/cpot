<?php

namespace App\Http\Controllers;

use DB;

class CategoryController extends Controller
{
    function list() {
        $data = DB::table('tbl_category')->get();
        $obj = ['data_object' => $data];
        return $obj;


        // $data = DB::table('tbl_main_category')->get();
        // foreach ($data as $k => $v) {
        //     $data[$k]->sub_cat = DB::table('tbl_sub_category')->select('scat_id', 'scat_name')->where('mcat_id', $v->mcat_id)->get()->toArray();
        // }
        // $obj = ['data_object' => $data];
        // return $obj;
    }

    function main_list() {
        $data = DB::table('tbl_main_category')->get();
        $obj = ['data_object' => $data];
        return $obj;
    }

    function sub_list($mcat_id) {
        $data = DB::table('tbl_main_category')->where('mcat_id', $mcat_id)->get();
        foreach ($data as $k => $v) {
            $data[$k]->sub_cat = DB::table('tbl_sub_category')->select('scat_id', 'scat_name')->where('mcat_id', $v->mcat_id)->get()->toArray();
        }
        $obj = ['data_object' => $data];
        return $obj;
    }
}

<?php

namespace App\Http\Controllers;

use DB;

class BlogCategoryController extends Controller
{
    public function blog_main_list()
    {
        $data = DB::table('tbl_blog_main_category')->get();
        $obj = ['data_object' => $data];
        return $obj;
    }

    public function blog_sub_list($bmc_id)
    {
        $data = DB::table('tbl_blog_sub_category')->where('bmc_id',$bmc_id)->get();
        $obj = ['data_object' => $data];
        return $obj;
    }
}

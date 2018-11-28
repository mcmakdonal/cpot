<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Blog extends ServiceProvider
{

    function list() {
        $select = ['tbl_blog.bg_id', 'tbl_blog.bg_title', 'tbl_blog.bg_description', 'tbl_blog.bg_image', 'tbl_blog.bg_tag', 'tbl_blog.bg_embed', 'tbl_blog.bg_ref', 'tbl_blog.bmc_id', 'tbl_blog.bsc_id', 'tbl_blog.bc_id', 'bmc_name', 'bsc_name', 'bc_name'];
        $data = DB::table('tbl_blog')
            ->select($select)
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->join('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->leftJoin('tbl_blog_category', 'tbl_blog_category.bc_id', '=', 'tbl_blog.bc_id')
            ->groupBy($select)
            ->orderBy('bg_id', 'desc')
            ->get();
        foreach ($data as $k => $v) {
            $data[$k]->bg_image = url('/blog/' . $v->bg_image);
        }
        return $data;
    }

    public static function insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_blog')->insertGetId($args);
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
        $status = DB::table('tbl_blog')->where('bg_id', $id)->update($args);
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

    public static function detail($id)
    {
        $select = ['tbl_blog.bg_id', 'tbl_blog.bg_title', 'tbl_blog.bg_description', 'tbl_blog.bg_image', 'tbl_blog.bg_tag', 'tbl_blog.bg_embed', 'tbl_blog.bg_ref', 'tbl_blog.bmc_id', 'tbl_blog.bsc_id', 'tbl_blog.bc_id', 'tbl_blog.bg_tag', 'bmc_name', 'bsc_name', 'bc_name'];
        $data = DB::table('tbl_blog')
            ->select($select)
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->join('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->leftJoin('tbl_blog_category', 'tbl_blog_category.bc_id', '=', 'tbl_blog.bc_id')
            ->groupBy($select)
            ->where('bg_id', $id)->get();
        foreach ($data as $k => $v) {
            $data[$k]->bg_image = url('/blog/' . $v->bg_image);
            $sub_tag = explode(",", $v->bg_tag);
            $matchThese = "where ";
            foreach ($sub_tag as $k_t => $tag) {
                $matchThese .= " pd_name like '%$tag%' or pd_tag like '%$tag%' ";
                if (($k_t + 1) != count($sub_tag)) {
                    $matchThese .= " or ";
                }
            }
            $matchThese .= " limit 4";
            $product = DB::select("select pd_id,pd_name,pd_price,pd_sprice,pd_description,pd_image,pd_tag from tbl_product $matchThese");
            foreach ($product as $kk => $vv) {
                $product[$kk]->pd_image = url('/files/' . $vv->pd_image);
            }
            $data[$k]->product_relate = $product;
        }
        foreach ($data as $k => $v) {
            $sub_tag = explode(",", $v->bg_tag);
            $matchThese = "where ";
            foreach ($sub_tag as $k_t => $tag) {
                $matchThese .= " bg_title like '%$tag%' or bg_tag like '%$tag%' ";
                if (($k_t + 1) != count($sub_tag)) {
                    $matchThese .= " or ";
                }
            }
            $matchThese .= " limit 4";
            $blog = DB::select("select bg_id,bg_title,bg_image,bg_tag from tbl_blog $matchThese");
            foreach ($blog as $kk => $vv) {
                $blog[$kk]->bg_image = url('/blog/' . $vv->bg_image);
            }
            $data[$k]->blog_relate = $blog;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_blog')->where('bg_id', '=', $id)->delete();
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

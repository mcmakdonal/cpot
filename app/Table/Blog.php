<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Blog extends ServiceProvider
{

    public static function lists($search = "", $bmc_id = "", $bsc_id = "", $search_tag = ['title', 'tag'])
    {
        $matchThese = [];
        $matchThese[] = ['tbl_blog.record_status', '=', 'A'];
        if ($bmc_id != "") {
            $matchThese[] = ['tbl_blog.bmc_id', '=', $bmc_id];
        }
        if ($bsc_id != "") {
            $matchThese[] = ['tbl_blog.bsc_id', '=', $bsc_id];
        }
        if ($search != "") {
            if (in_array("tag", $search_tag)) {
                $matchThese[] = ['tbl_blog.bg_tag', 'like', "%$search%"];
            }
            if (in_array("title", $search_tag)) {
                $matchThese[] = ['tbl_blog.bg_title', 'like', "%$search%"];
            }
        }

        $select = ['tbl_blog.bg_id', 'tbl_blog.bg_title', 'tbl_blog.bg_description', 'tbl_blog.bg_image', 'tbl_blog.bg_tag', 'tbl_blog.bg_embed', 'tbl_blog.bg_ref', 'tbl_blog.bmc_id', 'tbl_blog.bsc_id', 'bmc_name', 'bsc_name'];
        $data = DB::table('tbl_blog')
            ->select($select)
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->leftJoin('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->where($matchThese)
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
        $matchThese = [];
        $matchThese[] = ['tbl_blog.bg_id', '=', $id];
        $matchThese[] = ['tbl_blog.record_status', '=', 'A'];

        $select = ['tbl_blog.bg_id', 'tbl_blog.bg_title', 'tbl_blog.bg_description', 'tbl_blog.bg_image', 'tbl_blog.bg_tag', 'tbl_blog.bg_embed', 'tbl_blog.bg_ref', 'tbl_blog.bg_tag', 'tbl_blog.bmc_id', 'tbl_blog.bsc_id', 'bmc_name', 'bsc_name'];
        $data = DB::table('tbl_blog')
            ->select($select)
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->leftJoin('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->groupBy($select)
            ->where($matchThese)->get();
        foreach ($data as $k => $v) {
            $data[$k]->bg_image = url('/blog/' . $v->bg_image);
            $sub_tag = explode(",", $v->bg_tag);
            $matchThese = "where (record_status = 'A') AND ";
            foreach ($sub_tag as $k_t => $tag) {
                $matchThese .= " (pd_name like '%$tag%' or pd_tag like '%$tag%') ";
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

            $matchThese = "where (record_status = 'A') AND (bg_id != '$id') AND ";
            foreach ($sub_tag as $k_t => $tag) {
                $matchThese .= " (bg_title like '%$tag%' or bg_tag like '%$tag%') ";
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

        return $data;
    }

    public static function delete($id)
    {
        DB::beginTransaction();
        $args = [
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => 1,
            'record_status' => 'I',
        ];
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
}

<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Blog extends ServiceProvider
{

    private static $blog_field = [
        'tbl_blog.bg_id',
        'tbl_blog.bg_title',
        'tbl_blog.bg_description',
        'tbl_blog.bg_image',
        'tbl_blog.bg_tag',
        'tbl_blog.bg_embed',
        'tbl_blog.bg_ref',
        'tbl_blog.bg_store',
        'tbl_blog.bg_featured',
        'tbl_blog.bg_process',
        'tbl_blog.bg_detail',
        'tbl_blog.bg_benefits',
        'tbl_blog.bmc_id',
        'tbl_blog.bsc_id',
        'bmc_name',
        'bsc_name',

        'tbl_province.province_name',
        'tbl_province.province_sector',
    ];

    public static function lists($search = "", $bmc_id = "", $bsc_id = "", $search_tag = ['title', 'tag'], $page = 1, $date = false)
    {
        $limit = 10;
        $matchThese = [];

        // For default Release //
        $Release = "tbl_blog.bg_id != ''";
        if ($date) {
            $Release = "WEEK(create_date) = WEEK(CURDATE())";
        }
        // For default Release //

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

        $count = DB::table('tbl_blog')
            ->select(self::$blog_field)
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_blog.pd_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->leftJoin('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->where($matchThese)
            ->whereRaw($Release)
            ->groupBy(self::$blog_field)
            ->orderBy('bg_id', 'desc')
            ->get()->toArray();

        $count_all = count($count);
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        $data = DB::table('tbl_blog')
            ->select(self::$blog_field)
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_blog.pd_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->leftJoin('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->where($matchThese)
            ->whereRaw($Release)
            ->groupBy(self::$blog_field)
            ->orderBy('bg_id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();

        foreach ($data as $k => $v) {
            $image = DB::table('tbl_blog_images')->select('path')->where('bg_id', '=', $v->bg_id)->get()->toArray();
            $img = [];
            foreach ($image as $kk => $vv) {
                array_push($img, url($vv->path));
            }
            $data[$k]->bg_image = $img;
        }

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalBlog' => $count_all];
    }

    public static function listsv2($search = "", $search_tag = ['title', 'tag'], $page = 1, $mcat_id = "", $price = "", $rating = "", $sector = "")
    {
        $limit = 10;
        $matchThese = [];

        $matchThese[] = ['tbl_blog.record_status', '=', 'A'];
        if ($search != "") {
            if (in_array("tag", $search_tag)) {
                $matchThese[] = ['tbl_blog.bg_tag', 'like', "%$search%"];
            }
            if (in_array("title", $search_tag)) {
                $matchThese[] = ['tbl_blog.bg_title', 'like', "%$search%"];
            }
        }

        $matchThese[] = ['tbl_product.record_status', '=', 'A'];
        if ($mcat_id != "") {
            $matchThese[] = ['tbl_main_category.mcat_id', '=', $mcat_id];
        }
        if ($price != "") {
            $range = explode(",", $price);
            $min = $range[0];
            $max = $range[1];

            $matchThese[] = ['tbl_product.pd_price', '>=', $min];
            $matchThese[] = ['tbl_product.pd_price', '<=', $max];
        }
        if ($rating != "") {
            $matchThese[] = ['tbl_product.pd_rating', '=', $rating];
        }
        if ($sector != "") {
            $matchThese[] = ['tbl_province.province_sector', '=', $sector];
        }

        $count = DB::table('tbl_blog')
            ->select(self::$blog_field)
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_blog.pd_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->leftJoin('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->where($matchThese)
            ->groupBy(self::$blog_field)
            ->orderBy('bg_id', 'desc')
            ->get()->toArray();

        $count_all = count($count);
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        $data = DB::table('tbl_blog')
            ->select(self::$blog_field)
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_blog.pd_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->leftJoin('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->where($matchThese)
            ->groupBy(self::$blog_field)
            ->orderBy('bg_id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();

        foreach ($data as $k => $v) {
            $image = DB::table('tbl_blog_images')->select('path')->where('bg_id', '=', $v->bg_id)->get()->toArray();
            $img = [];
            foreach ($image as $kk => $vv) {
                array_push($img, url($vv->path));
            }
            $data[$k]->bg_image = $img;
        }

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalBlog' => $count_all];
    }

    public static function list_only_have_pd($page = 1)
    {
        $limit = 10;
        $matchThese = [];
        $matchThese[] = ['tbl_blog.record_status', '=', 'A'];
        $matchThese[] = ['tbl_blog.pd_id', '!=', ''];

        $count = DB::table('tbl_blog')
            ->select(self::$blog_field)
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_blog.pd_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->leftJoin('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->where($matchThese)
            ->groupBy(self::$blog_field)
            ->orderBy('bg_id', 'desc')
            ->get()->toArray();

        $count_all = count($count);
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        $data = DB::table('tbl_blog')
            ->select(self::$blog_field)
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_blog.pd_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->leftJoin('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->where($matchThese)
            ->groupBy(self::$blog_field)
            ->orderBy('bg_id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();

        foreach ($data as $k => $v) {
            $image = DB::table('tbl_blog_images')->select('path')->where('bg_id', '=', $v->bg_id)->get()->toArray();
            $img = [];
            foreach ($image as $kk => $vv) {
                array_push($img, url($vv->path));
            }
            $data[$k]->bg_image = $img;
        }

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalBlog' => $count_all];
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

        $data = DB::table('tbl_blog')
            ->select(self::$blog_field)
            ->join('tbl_blog_main_category', 'tbl_blog_main_category.bmc_id', '=', 'tbl_blog.bmc_id')
            ->leftJoin('tbl_blog_sub_category', 'tbl_blog_sub_category.bsc_id', '=', 'tbl_blog.bsc_id')
            ->groupBy(self::$blog_field)
            ->where($matchThese)->get();
        foreach ($data as $k => $v) {
            $image = DB::table('tbl_blog_images')->select('path')->where('bg_id', '=', $v->bg_id)->get()->toArray();
            $img = [];
            foreach ($image as $kk => $vv) {
                array_push($img, url($vv->path));
            }
            $data[$k]->bg_image = $img;

            $sub_tag = explode(",", $v->bg_tag);
            $matchThese = "where (record_status = 'A') AND ";
            foreach ($sub_tag as $k_t => $tag) {
                $matchThese .= " (pd_name like '%$tag%' or pd_tag like '%$tag%') ";
                if (($k_t + 1) != count($sub_tag)) {
                    $matchThese .= " or ";
                }
            }
            $matchThese .= " limit 4";
            $product = DB::select("select pd_id,pd_name,pd_price,pd_sprice,pd_description,pd_tag from tbl_product $matchThese");
            foreach ($product as $kk => $vv) {
                $image = DB::table('tbl_product_images')->select('path')->where('pd_id', '=', $vv->pd_id)->get()->toArray();
                $img = [];
                foreach ($image as $pk => $pv) {
                    array_push($img, url($pv->path));
                }
                $product[$kk]->pd_image = $img;
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
            $blog = DB::select("select bg_id,bg_title,bg_tag from tbl_blog $matchThese");
            foreach ($blog as $kk => $vv) {
                $image = DB::table('tbl_blog_images')->select('path')->where('bg_id', '=', $vv->bg_id)->get()->toArray();
                $img = [];
                foreach ($image as $bk => $bv) {
                    array_push($img, url($bv->path));
                }
                $blog[$kk]->bg_image = $img;
            }
            $data[$k]->blog_relate = $blog;
        }

        return $data;
    }

    public static function delete($id, $u_id)
    {
        DB::beginTransaction();
        $args = [
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $u_id,
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

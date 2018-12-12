<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Favorite extends ServiceProvider
{

    public static function count_all($u_id)
    {
        $matchThese = [];
        $matchThese[] = ['tbl_favorite.u_id', '=', $u_id];
        $matchThese[] = ['tbl_favorite.type', '=', 'pd'];
        $matchThese[] = ['tbl_product.record_status', '=', 'A'];

        $tbl_product = DB::table('tbl_favorite')
            ->select('tbl_favorite.u_id')
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_favorite.id')
            ->where($matchThese)
            ->count();

        $matchThese = [];
        $matchThese[] = ['tbl_favorite.u_id', '=', $u_id];
        $matchThese[] = ['tbl_favorite.type', '=', 'bg'];
        $matchThese[] = ['tbl_blog.record_status', '=', 'A'];

        $tbl_blog = DB::table('tbl_favorite')
            ->select('tbl_favorite.u_id')
            ->join('tbl_blog', 'tbl_blog.bg_id', '=', 'tbl_favorite.id')
            ->where($matchThese)
            ->count();

        $matchThese = [];
        $matchThese[] = ['tbl_favorite.u_id', '=', $u_id];
        $matchThese[] = ['tbl_favorite.type', '=', 'yt'];
        $matchThese[] = ['tbl_youtube.record_status', '=', 'A'];

        $tbl_youtube = DB::table('tbl_favorite')
            ->select('tbl_favorite.u_id')
            ->join('tbl_youtube', 'tbl_youtube.my_id', '=', 'tbl_favorite.id')
            ->where($matchThese)
            ->count();

        return [
            'status' => true,
            'message' => 'Success',
            'data_object' => [
                'total' => (int) $tbl_product + (int) $tbl_blog + (int) $tbl_youtube,
            ],
        ];
    }

    public static function lists_all($u_id)
    {
        $blog = Favorite::lists_blog($u_id);
        $product = Favorite::lists_product($u_id);
        $youtube = Favorite::lists_youtube($u_id);

        return [
            'status' => true,
            'message' => 'Success',
            'data_object' => [
                'total' => count($blog) + count($product) + count($youtube),
                'items' => array_merge($product, $blog, $youtube),
            ],
        ];
    }

    public static function lists_product($u_id)
    {
        $matchThese = [];
        $matchThese[] = ['tbl_favorite.u_id', '=', $u_id];
        $matchThese[] = ['tbl_favorite.type', '=', 'pd'];
        $matchThese[] = ['tbl_product.record_status', '=', 'A'];

        $tbl_favorite = DB::table('tbl_favorite')
            ->select('tbl_product.pd_id', 'tbl_product.pd_name', 'tbl_product.pd_description')
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_favorite.id')
            ->where($matchThese)
            ->orderBy('tbl_favorite.id', 'desc')
            ->get()->toArray();

        foreach ($tbl_favorite as $k => $v) {
            $image = DB::table('tbl_product_images')->select('path')->where('pd_id', '=', $v->pd_id)->get()->toArray();
            $img = [];
            foreach ($image as $kk => $vv) {
                array_push($img, url($vv->path));
            }
            $tbl_favorite[$k]->pd_image = $img;
            $tbl_favorite[$k]->type = "product";
        }

        return $tbl_favorite;
    }

    public static function lists_blog($u_id)
    {
        $matchThese = [];
        $matchThese[] = ['tbl_favorite.u_id', '=', $u_id];
        $matchThese[] = ['tbl_favorite.type', '=', 'bg'];
        $matchThese[] = ['tbl_blog.record_status', '=', 'A'];

        $tbl_favorite = DB::table('tbl_favorite')
            ->select('tbl_blog.bg_id', 'tbl_blog.bg_title', 'tbl_blog.bg_description')
            ->join('tbl_blog', 'tbl_blog.bg_id', '=', 'tbl_favorite.id')
            ->where($matchThese)
            ->orderBy('tbl_favorite.id', 'desc')
            ->get()->toArray();

        foreach ($tbl_favorite as $k => $v) {
            $image = DB::table('tbl_blog_images')->select('path')->where('bg_id', '=', $v->bg_id)->get()->toArray();
            $img = [];
            foreach ($image as $kk => $vv) {
                array_push($img, url($vv->path));
            }
            $tbl_favorite[$k]->bg_image = $img;
            $tbl_favorite[$k]->type = "blog";
        }

        return $tbl_favorite;
    }

    public static function lists_youtube($u_id)
    {
        $matchThese = [];
        $matchThese[] = ['tbl_favorite.u_id', '=', $u_id];
        $matchThese[] = ['tbl_favorite.type', '=', 'yt'];
        $matchThese[] = ['tbl_youtube.record_status', '=', 'A'];

        $tbl_favorite = DB::table('tbl_favorite')
            ->select('tbl_youtube.my_id', 'tbl_youtube.my_title', 'tbl_youtube.my_desc', 'tbl_youtube.my_href', 'tbl_youtube.my_bytag', 'tbl_youtube.my_image')
            ->join('tbl_youtube', 'tbl_youtube.my_id', '=', 'tbl_favorite.id')
            ->where($matchThese)
            ->orderBy('tbl_favorite.id', 'desc')
            ->get()->toArray();

        foreach ($tbl_favorite as $k => $v) {
            $tbl_favorite[$k]->type = "youtube";
        }

        return $tbl_favorite;
    }

    public static function is_like($type = "P", $id, $u_id)
    {
        DB::beginTransaction();
        if ($type === "P") {
            $matchThese = [];
            $matchThese[] = ['u_id', '=', $u_id];
            $matchThese[] = ['pd_id', '=', $id];
            $matchThese[] = ['record_status', '=', 'A'];
            $tbl_favorite_product = DB::table('tbl_favorite_product')
                ->select('u_id')
                ->where($matchThese)
                ->count();

            return ($tbl_favorite_product > 0) ? true : false;

        } else {
            $matchThese = [];
            $matchThese[] = ['u_id', '=', $u_id];
            $matchThese[] = ['bg_id', '=', $id];
            $matchThese[] = ['record_status', '=', 'A'];
            $tbl_favorite_product = DB::table('tbl_favorite_blog')
                ->select('u_id')
                ->where($matchThese)
                ->count();

            return ($tbl_favorite_product > 0) ? true : false;

        }
    }

    // @param type  : pd = product | bg = blog | yt = youtube
    public static function insert($type = "pd", $id, $u_id)
    {
        DB::beginTransaction();
        $matchThese = [];
        $matchThese[] = ['id', '=', $id];
        $matchThese[] = ['type', '=', $type];
        $matchThese[] = ['u_id', '=', $u_id];
        $tbl_favorite = DB::table('tbl_favorite')
            ->select('u_id')
            ->where($matchThese)
            ->count();

        if ($tbl_favorite > 0) {
            return [
                'status' => false,
                'message' => 'is Favorite now !',
            ];
        } else {
            $args = [
                'id' => $id,
                'type' => $type,
                'u_id' => $u_id,
                'create_date' => date('Y-m-d H:i:s'),
                'create_by' => $u_id,
                'update_date' => date('Y-m-d H:i:s'),
                'update_by' => $u_id,
                'record_status' => 'A',
            ];
            $status = DB::table('tbl_favorite')->insert($args);
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

    public static function delete($type = "pd", $id, $u_id)
    {
        DB::beginTransaction();
        $matchThese = [];
        $matchThese[] = ['id', '=', $id];
        $matchThese[] = ['type', '=', $type];
        $matchThese[] = ['u_id', '=', $u_id];

        $status = DB::table('tbl_favorite')->where($matchThese)->delete();
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

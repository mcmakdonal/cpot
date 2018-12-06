<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Favorite extends ServiceProvider
{

    public static function count_all($u_id)
    {
        $matchThese = [];
        $matchThese[] = ['tbl_favorite_product.u_id', '=', $u_id];
        $matchThese[] = ['tbl_favorite_product.record_status', '=', 'A'];
        $matchThese[] = ['tbl_product.record_status', '=', 'A'];

        $tbl_favorite_product = DB::table('tbl_favorite_product')
            ->select('u_id')
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_favorite_product.pd_id')
            ->where($matchThese)
            ->count();

        $matchThese = [];
        $matchThese[] = ['tbl_favorite_blog.u_id', '=', $u_id];
        $matchThese[] = ['tbl_favorite_blog.record_status', '=', 'A'];
        $matchThese[] = ['tbl_blog.record_status', '=', 'A'];

        $tbl_favorite_blog = DB::table('tbl_favorite_blog')
            ->select('tbl_favorite_blog.u_id')
            ->join('tbl_blog', 'tbl_blog.bg_id', '=', 'tbl_favorite_blog.bg_id')
            ->where($matchThese)
            ->count();

        return [
            'status' => true,
            'message' => 'Success',
            'data_object' => [
                'total' => (int) $tbl_favorite_product + (int) $tbl_favorite_blog,
            ],
        ];
    }

    public static function lists_all($u_id)
    {
        $blog = Favorite::lists_blog($u_id);
        $product = Favorite::lists_product($u_id);

        return [
            'status' => true,
            'message' => 'Success',
            'data_object' => [
                'total' => count($blog) + count($product),
                'items' => array_merge($product, $blog),
            ],
        ];
    }

    public static function lists_product($u_id)
    {
        $matchThese = [];
        $matchThese[] = ['tbl_favorite_product.u_id', '=', $u_id];
        $matchThese[] = ['tbl_favorite_product.record_status', '=', 'A'];
        $matchThese[] = ['tbl_product.record_status', '=', 'A'];

        $tbl_favorite_product = DB::table('tbl_favorite_product')
            ->select('tbl_product.pd_id', 'tbl_product.pd_name', 'tbl_product.pd_description', 'tbl_product.pd_image')
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_favorite_product.pd_id')
            ->where($matchThese)
            ->orderBy('tbl_favorite_product.pd_id', 'desc')
            ->get()->toArray();

        foreach ($tbl_favorite_product as $k => $v) {
            $tbl_favorite_product[$k]->pd_image = url('/files/' . $v->pd_image);
            $tbl_favorite_product[$k]->type = "product";
        }

        return $tbl_favorite_product;
    }

    public static function lists_blog($u_id)
    {
        $matchThese = [];
        $matchThese[] = ['tbl_favorite_blog.u_id', '=', $u_id];
        $matchThese[] = ['tbl_favorite_blog.record_status', '=', 'A'];
        $matchThese[] = ['tbl_blog.record_status', '=', 'A'];

        $tbl_favorite_blog = DB::table('tbl_favorite_blog')
            ->select('tbl_blog.bg_id', 'tbl_blog.bg_title', 'tbl_blog.bg_image', 'tbl_blog.bg_description')
            ->join('tbl_blog', 'tbl_blog.bg_id', '=', 'tbl_favorite_blog.bg_id')
            ->where($matchThese)
            ->orderBy('tbl_favorite_blog.bg_id', 'desc')
            ->get()->toArray();

        foreach ($tbl_favorite_blog as $k => $v) {
            $tbl_favorite_blog[$k]->bg_image = url('/blog/' . $v->bg_image);
            $tbl_favorite_blog[$k]->type = "blog";
        }

        return $tbl_favorite_blog;
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

    // @param type  : p = product | b = blog

    public static function insert($type = "P", $id, $u_id)
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

            if ($tbl_favorite_product > 0) {
                return [
                    'status' => false,
                    'message' => 'is Favorite now !',
                ];
            } else {
                $args = [
                    'pd_id' => $id,
                    'u_id' => $u_id,
                    'create_date' => date('Y-m-d H:i:s'),
                    'create_by' => $u_id,
                    'update_date' => date('Y-m-d H:i:s'),
                    'update_by' => $u_id,
                    'record_status' => 'A',
                ];
                $status = DB::table('tbl_favorite_product')->insert($args);
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
        } else {
            $matchThese = [];
            $matchThese[] = ['u_id', '=', $u_id];
            $matchThese[] = ['bg_id', '=', $id];
            $matchThese[] = ['record_status', '=', 'A'];
            $tbl_favorite_product = DB::table('tbl_favorite_blog')
                ->select('u_id')
                ->where($matchThese)
                ->count();

            if ($tbl_favorite_product > 0) {
                return [
                    'status' => false,
                    'message' => 'is Favorite now !',
                ];
            } else {
                $args = [
                    'bg_id' => $id,
                    'u_id' => $u_id,
                    'create_date' => date('Y-m-d H:i:s'),
                    'create_by' => $u_id,
                    'update_date' => date('Y-m-d H:i:s'),
                    'update_by' => $u_id,
                    'record_status' => 'A',
                ];
                $status = DB::table('tbl_favorite_blog')->insert($args);
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
    }

    public static function delete($type = "P", $id, $u_id)
    {
        DB::beginTransaction();
        $args = [
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $u_id,
            'record_status' => 'I',
        ];
        $status = false;
        if ($type === "P") {
            $status = DB::table('tbl_favorite_product')->where('pd_id', $id)->update($args);
        } else {
            $status = DB::table('tbl_favorite_blog')->where('bg_id', $id)->update($args);
        }
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

<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Product extends ServiceProvider
{
    public static function list($cat_id = "", $search = "") {
        $matchThese = [];
        if ($cat_id != "") {
            $matchThese[] = ['tbl_cat_product.cat_id', '=', $cat_id];
        }
        if ($search != "") {
            $matchThese[] = ['tbl_product.pd_name', 'like', "%$search%"];
            $matchThese[] = ['tbl_product.pd_tag', 'like', "%$search%"];
        }

        $select = ['tbl_product.pd_id', 'tbl_product.pd_name', 'tbl_product.pd_price', 'tbl_product.pd_sprice', 'tbl_product.pd_description', 'tbl_product.pd_image', 'tbl_product.pd_rating', 'tbl_product.pd_tag', 'tbl_product.pd_ref'];
        $data = DB::table('tbl_product')
            ->select($select)
            ->join('tbl_cat_product', 'tbl_product.pd_id', '=', 'tbl_cat_product.pd_id')
            ->where($matchThese)
            ->orderBy('tbl_product.pd_id', 'desc')
            ->groupBy($select)
            ->get()->toArray();
        // dd($data);
        foreach ($data as $k => $v) {
            $data[$k]->pd_image = url('/files/' . $v->pd_image);

            $data[$k]->category = DB::table('tbl_category')
                ->select('tbl_category.cat_id', 'tbl_category.cat_name')
                ->join('tbl_cat_product', 'tbl_cat_product.cat_id', '=', 'tbl_category.cat_id')
                ->where('tbl_cat_product.pd_id', $v->pd_id)->get()->toArray();

            $data[$k]->youtube = DB::table('tbl_product_youtube')
                ->select('my_title', 'my_href','my_image')
                ->where('pd_id', $v->pd_id)->get()->toArray();
        }

        return $data;
    }

    public static function insert($args,$category)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_product')->insertGetId($args);
        if ($status) {
            foreach ($category as $k => $v) {
                $category[$k]['pd_id'] = $status;
            }
            $result = DB::table('tbl_cat_product')->insert($category);
            if ($result) {
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

        } else {
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }

    public static function detail($id)
    {
        $select = ['tbl_product.pd_id', 'tbl_product.pd_name', 'tbl_product.pd_price', 'tbl_product.pd_sprice', 'tbl_product.pd_description', 'tbl_product.pd_image', 'tbl_product.pd_rating', 'tbl_product.pd_tag', 'tbl_product.pd_ref'];
        $data = DB::table('tbl_product')
            ->select($select)
            ->join('tbl_cat_product', 'tbl_product.pd_id', '=', 'tbl_cat_product.pd_id')
            ->where('tbl_product.pd_id', $id)
            ->groupBy($select)
            ->get()->toArray();
        foreach ($data as $k => $v) {
            $data[$k]->pd_image = url('/files/' . $v->pd_image);
            $sub_tag = explode(",", $v->pd_tag);
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
            $data[$k]->category = DB::table('tbl_category')
                ->select('tbl_category.cat_id', 'tbl_category.cat_name')
                ->join('tbl_cat_product', 'tbl_cat_product.cat_id', '=', 'tbl_category.cat_id')
                ->where('tbl_cat_product.pd_id', $v->pd_id)->get()->toArray();
        }
        foreach ($data as $k => $v) {
            $sub_tag = explode(",", $v->pd_tag);
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

            $data[$k]->youtube_relate = DB::table('tbl_product_youtube')
                ->select('my_title', 'my_href','my_image')
                ->where('pd_id', $v->pd_id)->get()->toArray();

        }
        return $data;
    }

    public static function update($args,$category, $id)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_product')->where('pd_id', $id)->update($args);
        if ($status) {
            foreach ($category as $k => $v) {
                $category[$k]['pd_id'] = $id;
            }
            DB::table('tbl_cat_product')->where('pd_id', $id)->delete();
            $result = DB::table('tbl_cat_product')->insert($category);
            if ($result) {
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

        } else {
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }

    public static function delete($id)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_product')->where('pd_id', '=', $id)->delete();
        $count_join = DB::table('tbl_cat_product')
            ->select('pd_id')
            ->where('pd_id', $id)
            ->count();
        if ($count_join > 0) {
            $status2 = DB::table('tbl_cat_product')->where('pd_id', '=', $id)->delete();
        } else {
            $status2 = true;
        }

        if ($status && $status2) {
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

    public static function search($search)
    {
        $q = str_replace(",", "|", $search);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=24&order=relevance&q=$q&key=AIzaSyASB9JR0hgdStc6q6-WMmVj6u0B1xrKDLY",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [
                'status' => false,
                'message' => 'error',
                'data' => $err,
            ];
        } else {
            return [
                'status' => true,
                'message' => 'Success',
                'items' => \json_decode($response),
            ];
        }
    }

    public static function insert_youtube($args, $id)
    {
        DB::beginTransaction();
        $count = DB::table('tbl_product_youtube')
            ->select('pd_id')
            ->where('pd_id', $id)
            ->count();
        if ($count > 0) {
            DB::table('tbl_product_youtube')->where('pd_id', '=', $id)->delete();
        }
        $status = DB::table('tbl_product_youtube')->insert($args);
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

    public static function select_youtube($id)
    {
        $data = DB::table('tbl_product_youtube')
            ->select('*')
            ->where('pd_id', $id)
            ->get();

        return $data;
    }
}

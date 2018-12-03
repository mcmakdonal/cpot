<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Product extends ServiceProvider
{
    public static function lists($search = "", $mcat_id = "", $scat_id = "", $search_tag = ['title', 'tag'])
    {
        $matchThese = [];
        $matchThese[] = ['tbl_product.record_status', '=', 'A'];
        if ($mcat_id != "") {
            $matchThese[] = ['tbl_main_category.mcat_id', '=', $mcat_id];
        }
        if ($scat_id != "") {
            $matchThese[] = ['tbl_sub_category.scat_id', '=', $scat_id];
        }
        if ($search != "") {
            if (in_array("tag", $search_tag)) {
                $matchThese[] = ['tbl_product.pd_tag', 'like', "%$search%"];
            }
            if (in_array("title", $search_tag)) {
                $matchThese[] = ['tbl_product.pd_name', 'like', "%$search%"];
            }
        }

        $select = [
            'tbl_product.pd_id',
            'tbl_product.pd_name',
            'tbl_product.pd_price',
            'tbl_product.pd_sprice',
            'tbl_product.pd_description',
            'tbl_product.pd_image',
            'tbl_product.pd_rating',
            'tbl_product.pd_tag',
            'tbl_product.pd_ref',
            'tbl_main_category.mcat_id',
            'tbl_main_category.mcat_name',
            'tbl_sub_category.scat_id',
            'tbl_sub_category.scat_name',
        ];
        $data = DB::table('tbl_product')
            ->select($select)
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_sub_category', 'tbl_sub_category.scat_id', '=', 'tbl_product.scat_id')
            ->where($matchThese)
            ->orderBy('tbl_product.pd_id', 'desc')
            ->groupBy($select)
            ->get()->toArray();

        foreach ($data as $k => $v) {
            $data[$k]->pd_image = url('/files/' . $v->pd_image);

            $data[$k]->youtube = DB::table('tbl_product_youtube')
                ->select('my_title', 'my_href', 'my_image')
                ->where([['record_status', '=', 'A'], ['pd_id', '=', $v->pd_id]])->get()->toArray();
        }

        return $data;
    }

    public static function insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_product')->insertGetId($args);
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

    public static function detail($id)
    {
        $matchThese = [];
        $matchThese[] = ['tbl_product.pd_id', '=', $id];
        $matchThese[] = ['tbl_product.record_status', '=', 'A'];

        $select = [
            'tbl_product.pd_id',
            'tbl_product.pd_name',
            'tbl_product.pd_price',
            'tbl_product.pd_sprice',
            'tbl_product.pd_description',
            'tbl_product.pd_image',
            'tbl_product.pd_rating',
            'tbl_product.pd_tag',
            'tbl_product.pd_ref',
            'tbl_main_category.mcat_id',
            'tbl_main_category.mcat_name',
            'tbl_sub_category.scat_id',
            'tbl_sub_category.scat_name',
        ];

        $data = DB::table('tbl_product')
            ->select($select)
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_sub_category', 'tbl_sub_category.scat_id', '=', 'tbl_product.scat_id')
            ->where($matchThese)
            ->orderBy('tbl_product.pd_id', 'desc')
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

            $matchThese = "where (record_status = 'A') AND (pd_id != '$id') AND ";
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

            $data[$k]->youtube_relate = DB::table('tbl_product_youtube')
                ->select('my_title', 'my_href', 'my_image')
                ->where('pd_id', $v->pd_id)->get()->toArray();

        }

        return $data;
    }

    public static function update($args, $id)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_product')->where('pd_id', $id)->update($args);
        if ($status) {
            DB::commit();
            return [
                'status' => true,
                'message' => 'Success',
            ];
        } else {
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }

    public static function delete($id,$u_id)
    {
        DB::beginTransaction();
        $args = [
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $u_id,
            'record_status' => 'I',
        ];
        $status = DB::table('tbl_product')->where('pd_id', $id)->update($args);
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
                'items' => json_decode($response),
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
            ->get()->toArray();

        return $data;
    }

    public static function lists_youtube($search, $search_tag = ['title', 'tag'])
    {
        $matchThese = [];
        if ($search != "") {
            if (in_array("tag", $search_tag)) {
                $matchThese[] = ['tbl_product_youtube.my_bytag', 'like', "%$search%"];
            }
            if (in_array("title", $search_tag)) {
                $matchThese[] = ['tbl_product_youtube.my_title', 'like', "%$search%"];
            }
        }
        $select = [
            'my_id',
            'my_title',
            'my_href',
            'my_bytag',
            'my_image',
        ];
        $data = DB::table('tbl_product_youtube')
            ->select($select)
            ->where($matchThese)
            ->groupBy($select)
            ->orderBy('tbl_product_youtube.my_id', 'desc')
            ->get()->toArray();

        return $data;
    }
}

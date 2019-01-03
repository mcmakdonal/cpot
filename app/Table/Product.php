<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Product extends ServiceProvider
{
    private static $product_field = [
        'tbl_product.pd_id',
        'tbl_product.pd_name',
        'tbl_product.pd_price',
        'tbl_product.pd_sprice',
        'tbl_product.pd_description',
        'tbl_product.pd_image',
        'tbl_product.pd_rating',
        'tbl_product.pd_tag',
        'tbl_product.pd_ref',

        'tbl_product.create_date',

        'tbl_province.province_id',
        'tbl_province.province_name',
        'tbl_province.province_sector',

        'tbl_product.pd_history',
        'tbl_product.pd_featured',
        'tbl_product.pd_detail',
        'tbl_product.pd_benefits',

        'tbl_store.s_id',
        'tbl_store.s_name',
        'tbl_store.s_onwer',
        'tbl_store.s_phone',

        'tbl_main_category.mcat_id',
        'tbl_main_category.mcat_name',
        'tbl_sub_category.scat_id',
        'tbl_sub_category.scat_name',

    ];

    public static function lists($search = "", $mcat_id = "", $scat_id = "", $search_tag = ['title', 'tag'], $page = 1, $price = "", $rating = "", $date = false)
    {
        $limit = 10;
        $matchThese = [];

        // For default Release //
        $Release = "tbl_product.pd_id != ''";
        if ($date) {
            $Release = "WEEK(tbl_product.create_date) = WEEK(CURDATE())";
        }
        // For default Release //

        $matchThese[] = ['tbl_product.record_status', '=', 'A'];
        if ($mcat_id != "") {
            $matchThese[] = ['tbl_main_category.mcat_id', '=', $mcat_id];
        }
        if ($scat_id != "") {
            $matchThese[] = ['tbl_sub_category.scat_id', '=', $scat_id];
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
        if ($search != "") {
            if (in_array("tag", $search_tag)) {
                $matchThese[] = ['tbl_product.pd_tag', 'like', "%$search%"];
            }
            if (in_array("title", $search_tag)) {
                $matchThese[] = ['tbl_product.pd_name', 'like', "%$search%"];
            }
        }

        $count = DB::table('tbl_product')
            ->select(self::$product_field)
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_sub_category', 'tbl_sub_category.scat_id', '=', 'tbl_product.scat_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_store', 'tbl_store.s_id', '=', 'tbl_product.s_id')
            ->where($matchThese)
            ->whereRaw($Release)
            ->orderBy('tbl_product.pd_id', 'desc')
            ->groupBy(self::$product_field)
            ->get()->toArray();

        $count_all = count($count);
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        $data = DB::table('tbl_product')
            ->select(self::$product_field)
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_sub_category', 'tbl_sub_category.scat_id', '=', 'tbl_product.scat_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_store', 'tbl_store.s_id', '=', 'tbl_product.s_id')
            ->where($matchThese)
            ->whereRaw($Release)
            ->orderBy('tbl_product.pd_id', 'desc')
            ->groupBy(self::$product_field)
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();

        foreach ($data as $k => $v) {
            $image = DB::table('tbl_product_images')->select('path')->where('pd_id', '=', $v->pd_id)->get()->toArray();
            $img = [];
            foreach ($image as $kk => $vv) {
                array_push($img, url($vv->path));
            }
            $data[$k]->pd_image = $img;

            $data[$k]->youtube = DB::table('tbl_youtube')
                ->select('my_id', 'my_title', 'my_href', 'my_image', 'my_desc')
                ->where([['record_status', '=', 'A'], ['pd_id', '=', $v->pd_id]])->get()->toArray();
        }

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalProduct' => $count_all];
    }

    public static function lists_formatch()
    {
        $data = DB::table('tbl_product')
            ->select(self::$product_field)
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_sub_category', 'tbl_sub_category.scat_id', '=', 'tbl_product.scat_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_store', 'tbl_store.s_id', '=', 'tbl_product.s_id')
            ->orderBy('tbl_product.pd_id', 'desc')
            ->groupBy(self::$product_field)
            ->get()->toArray();

        foreach ($data as $k => $v) {
            $image = DB::table('tbl_product_images')->select('path')->where('pd_id', '=', $v->pd_id)->get()->toArray();
            $img = [];
            foreach ($image as $kk => $vv) {
                array_push($img, url($vv->path));
            }
            $data[$k]->pd_image = $img;

            $data[$k]->youtube = DB::table('tbl_youtube')
                ->select('my_id', 'my_title', 'my_href', 'my_image', 'my_desc')
                ->where([['record_status', '=', 'A'], ['pd_id', '=', $v->pd_id]])->get()->toArray();
        }

        return ['data_object' => $data];
    }


    public static function listsv2($search = "", $mcat_id = [], $search_tag = ['title', 'tag'], $page = 1, $price = "", $rating = "", $sector = [])
    {
        $limit = 10;
        $matchThese = [];

        $Orwhere_1 = [];
        $Orwhere_2 = [];

        $matchThese[] = ['tbl_product.record_status', '=', 'A'];
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

        if ($search != "") {
            if (in_array("tag", $search_tag)) {
                $Orwhere_1 = ['tbl_product.pd_tag', 'like', "%$search%"];
            }
            if (in_array("title", $search_tag)) {
                $Orwhere_2 = ['tbl_product.pd_name', 'like', "%$search%"];
            }
        }

        $count = DB::table('tbl_product')
            ->select(self::$product_field)
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_sub_category', 'tbl_sub_category.scat_id', '=', 'tbl_product.scat_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_store', 'tbl_store.s_id', '=', 'tbl_product.s_id')
            ->where($matchThese)
            ->where(function ($query) use ($search, $search_tag) {
                if ($search != "") {
                    if (in_array("tag", $search_tag)) {
                        $query->where('tbl_product.pd_tag', 'like', "%$search%");
                    }
                    if (in_array("title", $search_tag)) {
                        $query->orWhere('tbl_product.pd_name', 'like', "%$search%");
                    }
                }
            })
            ->where(function ($query) use ($mcat_id) {
                if (count($mcat_id) > 0) {
                    foreach ($mcat_id as $k => $v) {
                        $query->orWhere('tbl_main_category.mcat_id', '=', $v);
                    }
                }
            })
            ->where(function ($query) use ($sector) {
                if (count($sector) > 0) {
                    foreach ($sector as $k => $v) {
                        $query->orWhere('tbl_province.province_sector', '=', $sector);
                    }
                }
            })
            ->orderBy('tbl_product.pd_id', 'desc')
            ->groupBy(self::$product_field)
            ->get()->toArray();

        $count_all = count($count);
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        $data = DB::table('tbl_product')
            ->select(self::$product_field)
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_sub_category', 'tbl_sub_category.scat_id', '=', 'tbl_product.scat_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_store', 'tbl_store.s_id', '=', 'tbl_product.s_id')
            ->where($matchThese)
            ->where(function ($query) use ($search, $search_tag) {
                if ($search != "") {
                    if (in_array("tag", $search_tag)) {
                        $query->where('tbl_product.pd_tag', 'like', "%$search%");
                    }
                    if (in_array("title", $search_tag)) {
                        $query->orWhere('tbl_product.pd_name', 'like', "%$search%");
                    }
                }
            })
            ->where(function ($query) use ($mcat_id) {
                if (count($mcat_id) > 0) {
                    foreach ($mcat_id as $k => $v) {
                        $query->orWhere('tbl_main_category.mcat_id', '=', $v);
                    }
                }
            })
            ->where(function ($query) use ($sector) {
                if (count($sector) > 0) {
                    foreach ($sector as $k => $v) {
                        $query->orWhere('tbl_province.province_sector', '=', $sector);
                    }
                }
            })
            ->orderBy('tbl_product.pd_id', 'desc')
            ->groupBy(self::$product_field)
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();
        // ->toSql();

        foreach ($data as $k => $v) {
            $image = DB::table('tbl_product_images')->select('path')->where('pd_id', '=', $v->pd_id)->get()->toArray();
            $img = [];
            foreach ($image as $kk => $vv) {
                array_push($img, url($vv->path));
            }
            $data[$k]->pd_image = $img;
        }

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalProduct' => $count_all];
    }

    public static function tag_lists()
    {
        $matchThese = [];
        $matchThese[] = ['tbl_product.record_status', '=', 'A'];

        $data = DB::table('tbl_product')
            ->select('tbl_product.pd_tag', DB::raw('count(tbl_product.pd_tag) counter'))
            ->where($matchThese)
            ->orderBy('counter', 'desc')
            ->groupBy('tbl_product.pd_tag')
            ->get()->toArray();

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

        $data = DB::table('tbl_product')
            ->select(self::$product_field)
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_sub_category', 'tbl_sub_category.scat_id', '=', 'tbl_product.scat_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->join('tbl_store', 'tbl_store.s_id', '=', 'tbl_product.s_id')
            ->where($matchThese)
            ->orderBy('tbl_product.pd_id', 'desc')
            ->groupBy(self::$product_field)
            ->get()->toArray();

        foreach ($data as $k => $v) {
            $image = DB::table('tbl_product_images')->select('path')->where('pd_id', '=', $v->pd_id)->get()->toArray();
            $img = [];
            foreach ($image as $kk => $vv) {
                array_push($img, url($vv->path));
            }
            $data[$k]->pd_image = $img;

            $sub_tag = explode(",", $v->pd_tag);
            $matchThese = "where ";
            foreach ($sub_tag as $k_t => $tag) {
                $matchThese .= " bg_title like '%$tag%' or bg_tag like '%$tag%' ";
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

            $matchThese = "where (record_status = 'A') AND (pd_id != '$id') AND ";
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

            $data[$k]->youtube_relate = DB::table('tbl_youtube')
                ->select('my_id', 'my_title', 'my_href', 'my_image', 'my_desc')
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

    public static function delete($id, $u_id)
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
            CURLOPT_URL => "https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=24&type=video&order=relevance&q=$q&key=AIzaSyASB9JR0hgdStc6q6-WMmVj6u0B1xrKDLY",
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
        $data = json_decode($response);
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
                'items' => $data->items,
            ];
        }
    }

    public static function insert_youtube($args, $id)
    {
        DB::beginTransaction();
        $count = DB::table('tbl_youtube')
            ->select('pd_id')
            ->where('pd_id', $id)
            ->count();
        if ($count > 0) {
            DB::table('tbl_youtube')->where('pd_id', '=', $id)->delete();
        }
        $status = DB::table('tbl_youtube')->insert($args);
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
        $data = DB::table('tbl_youtube')
            ->select('my_id', 'my_title', 'my_desc', 'my_href', 'my_image')
            ->where('pd_id', $id)
            ->get()->toArray();

        return $data;
    }

    public static function lists_youtube($search = "", $search_tag = ['title', 'tag'], $page = 1, $mcat_id = [], $price = "", $rating = "", $sector = "")
    {
        $limit = 10;
        $matchThese = [];
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

        $select = [
            'tbl_youtube.my_id',
            'tbl_youtube.my_title',
            'tbl_youtube.my_href',
            'tbl_youtube.my_bytag',
            'tbl_youtube.my_image',
            'tbl_youtube.my_desc',
        ];

        $count = DB::table('tbl_youtube')
            ->select($select)
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_youtube.pd_id')
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->where($matchThese)
            ->where(function ($query) use ($search, $search_tag) {
                if ($search != "") {
                    if (in_array("tag", $search_tag)) {
                        $query->where('tbl_youtube.my_bytag', 'like', "%$search%");
                    }
                    if (in_array("title", $search_tag)) {
                        $query->orWhere('tbl_youtube.my_title', 'like', "%$search%");
                    }
                }
            })
            ->where(function ($query) use ($mcat_id) {
                if (count($mcat_id) > 0) {
                    foreach ($mcat_id as $k => $v) {
                        $query->orWhere('tbl_main_category.mcat_id', '=', $v);
                    }
                }
            })
            ->groupBy($select)
            ->orderBy('tbl_youtube.my_id', 'desc')
            ->get()->toArray();

        $count_all = count($count);
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        $data = DB::table('tbl_youtube')
            ->select($select)
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_youtube.pd_id')
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_product.pd_province')
            ->where($matchThese)
            ->where(function ($query) use ($search, $search_tag) {
                if ($search != "") {
                    if (in_array("tag", $search_tag)) {
                        $query->where('tbl_youtube.my_bytag', 'like', "%$search%");
                    }
                    if (in_array("title", $search_tag)) {
                        $query->orWhere('tbl_youtube.my_title', 'like', "%$search%");
                    }
                }
            })
            ->where(function ($query) use ($mcat_id) {
                if (count($mcat_id) > 0) {
                    foreach ($mcat_id as $k => $v) {
                        $query->orWhere('tbl_main_category.mcat_id', '=', $v);
                    }
                }
            })
            ->groupBy($select)
            ->orderBy('tbl_youtube.my_id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalYoutube' => $count_all];
    }

    public static function detail_youtube($id)
    {
        $select = [
            'my_id',
            'my_title',
            'my_href',
            'my_bytag',
            'my_image',
            'my_desc',
        ];
        $data = DB::table('tbl_youtube')
            ->select($select)
            ->where('my_id', '=', $id)
            ->groupBy($select)
            ->orderBy('tbl_youtube.my_id', 'desc')
            ->get()->toArray();

        return $data;
    }

}

<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class StoreandMaterial extends ServiceProvider
{
    private static $store_field = [
        'tbl_store.s_id',
        'tbl_store.s_name',
        'tbl_store.s_onwer',
        'tbl_store.s_phone',
        'tbl_store.fb_id',
        'tbl_store.s_addr',
        'tbl_store.s_image',

        'tbl_province.province_name',
        'tbl_province.province_sector',
    ];

    private static $material_field = [
        'tbl_material.m_id',
        'tbl_material.m_name',
        'tbl_material.m_price',
        'tbl_province.province_id',
        'tbl_province.province_name',
        'tbl_district.district_id',
        'tbl_district.district_name',
        'tbl_sub_district.sub_district_id',
        'tbl_sub_district.sub_district_name',

        'tbl_store.s_id',
        'tbl_store.s_name',
        'tbl_store.s_onwer',
        'tbl_store.s_phone',
        'tbl_store.fb_id',
        'tbl_store.s_line',
        'tbl_store.s_ig',
        'tbl_store.s_addr',
        'tbl_store.s_image',

    ];

    public static function store_lists($id = "", $search = "", $page = 1, $pv_id = "", $mcat_id = [], $price = "", $rating = "", $sector = [])
    {
        $limit = 10;
        $matchThese = [];
        $matchThese[] = ['tbl_product.record_status', '=', 'A'];

        if ($id != "") {
            $matchThese[] = ['tbl_store.s_id', '=', "$id"];
        }
        if ($pv_id != "") {
            $matchThese[] = ['tbl_province.province_id', '=', "$pv_id"];
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

        $count = DB::table('tbl_store')
            ->select(self::$store_field)
            ->join('tbl_product', 'tbl_product.s_id', '=', 'tbl_store.s_id')
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_store.province_id')
            ->where($matchThese)
            ->where(function ($query) use ($search) {
                if ($search != "") {
                    $query->where('tbl_store.s_name', 'like', "%$search%");
                    $query->orWhere('tbl_store.s_onwer', 'like', "%$search%");
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
            ->orderBy('tbl_store.s_name', 'ASC')
            ->groupBy(self::$store_field)
            ->get()->toArray();

        $count_all = count($count);
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        $data = DB::table('tbl_store')
            ->select(self::$store_field)
            ->join('tbl_product', 'tbl_product.s_id', '=', 'tbl_store.s_id')
            ->join('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_store.province_id')
            ->where($matchThese)
            ->where(function ($query) use ($search) {
                if ($search != "") {
                    $query->where('tbl_store.s_name', 'like', "%$search%");
                    $query->orWhere('tbl_store.s_onwer', 'like', "%$search%");
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
            ->orderBy('tbl_store.s_name', 'ASC')
            ->groupBy(self::$store_field)
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();
        // ->toSql();

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalStore' => $count_all];
    }

    public static function store_lists_province($id = "", $search = "", $page = 1, $pv_id = "", $mcat_id = [], $price = "", $rating = "", $sector = [])
    {
        $limit = 4;
        $count_all = 77;
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        $matchThese = [];
        if ($pv_id != "") {
            $matchThese[] = ['tbl_province.province_id', '=', $pv_id];
        }

        // ร้านค้าทั้งหมด
        $count = DB::table('tbl_store')
            ->select(self::$store_field)
            ->leftJoin('tbl_product', 'tbl_product.s_id', '=', 'tbl_store.s_id')
            ->leftJoin('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_store.province_id')
            ->where($matchThese)
            ->orderBy('tbl_store.s_name', 'ASC')
            ->groupBy(self::$store_field)
            ->get()->toArray();

        // เอาจังหวัดมา วน loop
        $data = DB::table('tbl_province')
            ->select('province_id', 'province_name')
            ->where($matchThese)
            ->offset($offset)
            ->limit($limit)
            ->orderBy('tbl_province.province_name', 'ASC')
            ->get()->toArray();

        foreach ($data as $k => $v) {
            $matchThese = [];
            $matchThese[] = ['tbl_store.province_id', '=', $v->province_id];

            $data[$k]->store = DB::table('tbl_store')
                ->select(self::$store_field)
                ->leftJoin('tbl_product', 'tbl_product.s_id', '=', 'tbl_store.s_id')
                ->leftJoin('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
                ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_store.province_id')
                ->where($matchThese)
                ->orderBy('tbl_store.s_name', 'ASC')
                ->groupBy(self::$store_field)
                ->get()->toArray();
        }

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalStore' => count($count)];

    }

    public static function material_lists($id = "", $search = "", $page = 1, $spv_id = "", $sdt_id = "", $ssdt_id = "", $sector = [])
    {
        $limit = 10;
        $matchThese = [];

        if ($id != "") {
            $matchThese[] = ['tbl_material.m_id', '=', "$id"];
        }
        if ($search != "") {
            $matchThese[] = ['tbl_material.m_name', 'like', "%$search%"];
        }
        if ($spv_id != "") {
            $matchThese[] = ['tbl_province.province_id', '=', "$spv_id"];
        }
        if ($sdt_id != "") {
            $matchThese[] = ['tbl_district.district_id', '=', "$sdt_id"];
        }
        if ($ssdt_id != "") {
            $matchThese[] = ['tbl_sub_district.sub_district_id', '=', "$ssdt_id"];
        }

        $count = DB::table('tbl_material')
            ->select(self::$material_field)
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_material.province_id')
            ->join('tbl_district', 'tbl_district.district_id', '=', 'tbl_material.district_id')
            ->join('tbl_store', 'tbl_store.s_id', '=', 'tbl_material.s_id')
            ->join('tbl_sub_district', 'tbl_sub_district.sub_district_id', '=', 'tbl_material.sub_district_id')
            ->where($matchThese)
            ->where(function ($query) use ($sector) {
                if (count($sector) > 0) {
                    foreach ($sector as $k => $v) {
                        $query->orWhere('tbl_province.province_sector', '=', $sector);
                    }
                }
            })
            ->orderBy('tbl_material.m_name', 'ASC')
            ->get()->toArray();

        $count_all = count($count);
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        $data = DB::table('tbl_material')
            ->select(self::$material_field)
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_material.province_id')
            ->join('tbl_district', 'tbl_district.district_id', '=', 'tbl_material.district_id')
            ->join('tbl_store', 'tbl_store.s_id', '=', 'tbl_material.s_id')
            ->join('tbl_sub_district', 'tbl_sub_district.sub_district_id', '=', 'tbl_material.sub_district_id')
            ->where($matchThese)
            ->where(function ($query) use ($sector) {
                if (count($sector) > 0) {
                    foreach ($sector as $k => $v) {
                        $query->orWhere('tbl_province.province_sector', '=', $sector);
                    }
                }
            })
            ->orderBy('tbl_material.m_name', 'ASC')
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalMaterial' => $count_all];
    }

    public static function store_lists_material($id = "", $search = "", $page = 1)
    {
        $limit = 4;
        $matchThese = [];

        if ($id != "") {
            $matchThese[] = ['tbl_material.m_id', '=', "$id"];
        }
        if ($search != "") {
            $matchThese[] = ['tbl_material.m_name', 'like', "%$search%"];
        }

        // วัตถุดิบทั้งหมด
        $count = DB::table('tbl_material')
            ->select(self::$material_field)
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_material.province_id')
            ->join('tbl_district', 'tbl_district.district_id', '=', 'tbl_material.district_id')
            ->join('tbl_store', 'tbl_store.s_id', '=', 'tbl_material.s_id')
            ->join('tbl_sub_district', 'tbl_sub_district.sub_district_id', '=', 'tbl_material.sub_district_id')
            ->where($matchThese)
            ->orderBy('tbl_material.m_name', 'ASC')
            ->get()->toArray();

        $count_all = count($count);
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        // เอาวัตถุดิบมา วน loop
        $data = DB::table('tbl_material')
            ->select('m_id', 'm_name', 's_id')
            ->where($matchThese)
            ->offset($offset)
            ->limit($limit)
            ->groupBy('m_name')
            ->orderBy('tbl_material.m_name', 'ASC')
            ->get()->toArray();

        foreach ($data as $k => $v) {
            $matchThese = [];
            $matchThese[] = ['tbl_material.m_name', '=', $v->m_name];

            $data[$k]->store = DB::table('tbl_store')
                ->select(self::$store_field)
                ->leftJoin('tbl_product', 'tbl_product.s_id', '=', 'tbl_store.s_id')
                ->join('tbl_material', 'tbl_material.s_id', '=', 'tbl_store.s_id')
                ->leftJoin('tbl_main_category', 'tbl_main_category.mcat_id', '=', 'tbl_product.mcat_id')
                ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_store.province_id')
                ->where($matchThese)
                ->orderBy('tbl_store.s_name', 'ASC')
                ->groupBy(self::$store_field)
                ->get()->toArray();
        }

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalMaterial' => count($count)];

    }

}

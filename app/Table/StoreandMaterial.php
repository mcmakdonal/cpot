<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class StoreandMaterial extends ServiceProvider
{
    public static function store_lists($id = "", $search = "", $page = 1, $pv_id = "")
    {
        $limit = 10;
        $matchThese = [];

        if ($id != "") {
            $matchThese[] = ['s_id', '=', "$id"];
        }
        if ($search != "") {
            $matchThese[] = ['s_name', 'like', "%$search%"];
            $matchThese[] = ['s_onwer', 'like', "%$search%"];
        }
        if ($pv_id != "") {
            $matchThese[] = ['tbl_province.province_id', '=', "$pv_id"];
        }

        $count = DB::table('tbl_store')
            ->select('s_id', 's_name', 's_onwer', 's_phone', 'province_name')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_store.province_id')
            ->where($matchThese)
            ->orderBy('s_id', 'DESC')
            ->get()->toArray();

        $count_all = count($count);
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        $data = DB::table('tbl_store')
            ->select('s_id', 's_name', 's_onwer', 's_phone', 'province_name')
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_store.province_id')
            ->where($matchThese)
            ->orderBy('s_id', 'DESC')
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalStore' => $count_all];
    }

    public static function material_lists($id = "", $search = "", $page = 1, $spv_id, $sdt_id, $ssdt_id)
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

        $select = [
            'tbl_material.m_id', 'tbl_material.m_name', 'tbl_material.m_price', 'tbl_province.province_id', 'tbl_province.province_name', 'tbl_district.district_id', 'tbl_district.district_name', 'tbl_sub_district.sub_district_id', 'tbl_sub_district.sub_district_name',
        ];

        $count = DB::table('tbl_material')
            ->select($select)
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_material.province_id')
            ->join('tbl_district', 'tbl_district.district_id', '=', 'tbl_material.district_id')
            ->join('tbl_sub_district', 'tbl_sub_district.sub_district_id', '=', 'tbl_material.sub_district_id')
            ->where($matchThese)
            ->orderBy('tbl_material.m_id', 'DESC')
            ->get()->toArray();

        $count_all = count($count);
        $total = ceil($count_all / $limit);
        $offset = ($page - 1) * $limit;

        $data = DB::table('tbl_material')
            ->select($select)
            ->join('tbl_province', 'tbl_province.province_id', '=', 'tbl_material.province_id')
            ->join('tbl_district', 'tbl_district.district_id', '=', 'tbl_material.district_id')
            ->join('tbl_sub_district', 'tbl_sub_district.sub_district_id', '=', 'tbl_material.sub_district_id')
            ->where($matchThese)
            ->orderBy('tbl_material.m_id', 'DESC')
            ->offset($offset)
            ->limit($limit)
            ->get()->toArray();

        return ['data_object' => $data, 'totalPages' => $total, 'currentPage' => $page, 'totalMaterial' => $count_all];
    }

}

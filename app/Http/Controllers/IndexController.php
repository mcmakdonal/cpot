<?php

namespace App\Http\Controllers;

use App\Http\JwtService;
use App\Table\Addr;
use App\Table\Blog;
use App\Table\Evaluation;
use App\Table\Favorite;
use App\Table\Log;
use App\Table\Product;
use App\Table\StoreandMaterial;
use Illuminate\Http\Request;
use Validator;

class IndexController extends Controller
{
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seach_type' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $obj = [
            'product' => [],
            'blog' => [
                'cpot' => [],
                'youtube' => [],
            ],
            'store' => [],
            'material' => [],
        ];

        $search_type = ($request->seach_type) ? [trim(strtolower($request->seach_type))] : ['title'];
        $search = ($request->search) ? $request->search : "";
        $type = ($request->type) ? $request->type : "all";
        $page = ($request->page == 0 || $request->page == "") ? 1 : $request->page;

        // Product
        $mcat_id = (array_key_exists('mcat_id', $request->product)) ? $request->product['mcat_id'] : "";
        $scat_id = (array_key_exists('scat_id', $request->product)) ? $request->product['scat_id'] : "";
        $price = (array_key_exists('price', $request->product)) ? $request->product['price'] : "";
        $rating = (array_key_exists('rating', $request->product)) ? $request->product['rating'] : "";
        // Blog
        $bmc_id = (array_key_exists('bmc_id', $request->blog)) ? $request->blog['bmc_id'] : "";
        $bsc_id = (array_key_exists('bsc_id', $request->blog)) ? $request->blog['bsc_id'] : "";
        // Store
        $pv_id = (array_key_exists('pv_id', $request->store)) ? $request->store['pv_id'] : "";
        // Material
        $spv_id = (array_key_exists('pv_id', $request->material)) ? $request->material['pv_id'] : "";
        $sdt_id = (array_key_exists('dt_id', $request->material)) ? $request->material['dt_id'] : "";
        $ssdt_id = (array_key_exists('sdt_id', $request->material)) ? $request->material['sdt_id'] : "";

        if ($type == "pd" || $type == "all") {
            $product = Product::lists($search, $mcat_id, $scat_id, $search_type, $page, $price, $rating);
            array_push($obj['product'], $product);
        }

        if ($type == "bg" || $type == "all") {
            $blog = Blog::lists($search, $bmc_id, $bsc_id, $search_type, $page);
            array_push($obj['blog']['cpot'], $blog);
        }

        if ($type == "yt" || $type == "all") {
            $youtube = Product::lists_youtube($search, $search_type, $page);
            array_push($obj['blog']['youtube'], $youtube);
        }

        if ($type == "st" || $type == "all") {
            $store = StoreandMaterial::store_lists("", $search, $page, $pv_id);
            array_push($obj['store'], $store);
        }

        if ($type == "mt" || $type == "all") {
            $material = StoreandMaterial::material_lists("", $search, $page, $spv_id, $sdt_id, $ssdt_id);
            array_push($obj['material'], $material);
        }

        return $obj;
    }

    // 12 18 2018
    public function searchv2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $obj = [
            'product' => [],
            'blog' => [
                'cpot' => [],
                'youtube' => [],
            ],
            'store' => [],
            'material' => [],
        ];

        $search = ($request->search) ? $request->search : "";
        $page = ($request->page == 0 || $request->page == "") ? 1 : $request->page;

        // Filter Category
        $mcat_id = ($request->mcat_id) ? $request->mcat_id : "";
        $search_tag = ($request->search_tag) ? ['tag'] : ['title'];
        // Filter Sector
        $sector = ($request->sector) ? strtoupper($request->sector) : "";
        // Filter Type
        $type = ($request->type) ? $request->type : "";
        // Filter Rating
        $rating = ($request->rating) ? $request->rating : "";
        // Filter Price
        $price = ($request->price) ? $request->price : "";

        if ($type == "") {
            $data = Product::listsv2($search, $mcat_id, $search_tag, $page, $price, $rating, $sector);
            array_push($obj['product'], $data);
        }

        if ($type == "") {
            $store = StoreandMaterial::store_lists("", $search, $page, "", $mcat_id, $price, $rating, $sector);
            array_push($obj['store'], $store);
        }

        if ($type == "") {
            $material = StoreandMaterial::material_lists("", $search, $page, "", "", "",$sector);
            array_push($obj['material'], $material);
        }

        if ($type == "" || $type == "bg") {
            $data = Blog::listsv2($search, $search_tag, $page, $mcat_id, $price, $rating, $sector);
            array_push($obj['blog']['cpot'], $data);
        }

        if ($type == "" || $type == "yt") {
            $data = Product::lists_youtube($search, $search_tag, $page, $mcat_id, $price, $rating);
            array_push($obj['blog']['youtube'], $data);
        }

        return $obj;
    }

    public function blog_youtube(Request $request)
    {
        $obj = [
            'blog' => [],
            'youtube' => [],
        ];

        $page = ($request->page == 0 || $request->page == "") ? 1 : $request->page;

        $data = Blog::list_only_have_pd($page);
        array_push($obj['blog'], $data);

        $data = Product::lists_youtube("", [], $page);
        array_push($obj['youtube'], $data);

        return $obj;
    }

    public function material(Request $request){
        $search = ($request->search) ? $request->search : "";
        $page = ($request->page == 0 || $request->page == "") ? 1 : $request->page;
        $material = StoreandMaterial::material_lists("", $search, $page);
        return $material;
    }

    public function store(Request $request){
        $search = ($request->search) ? $request->search : "";
        $page = ($request->page == 0 || $request->page == "") ? 1 : $request->page;
        $store = StoreandMaterial::store_lists("", $search, $page);
        return $store;
    }

    public function question()
    {
        $eva = Evaluation::get_question();
        $obj = [
            'data_object' => $eva,
            'status' => true,
            'message' => 'success',
        ];
        return $obj;
    }

    public function answer(Request $request)
    {
        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];

        $validator = Validator::make($request->all(), [
            'et_id' => 'required',
            'answer' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $args = [];
        foreach ($request->answer as $k => $v) {
            $ans = [
                'et_id' => $request->et_id,
                'q_id' => $v['q_id'],
                'q_point' => $v['q_point'],
                'u_id' => $u_id,
                'create_date' => date('Y-m-d H:i:s'),
                'create_by' => $u_id,
                'update_date' => date('Y-m-d H:i:s'),
                'update_by' => $u_id,
                'record_status' => 'A',
            ];
            array_push($args, $ans);
        }

        $eva = Evaluation::answer($args);
        return $eva;
    }

    public function total_user_evaluation(Request $request)
    {
        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];
        $data = Evaluation::total_user_evaluation($u_id);
        return [
            'status' => true,
            'total' => count($data),
            'topic' => $data,
        ];
    }

    public function activities(Request $request)
    {
        $u_id = 0;
        if ($request->header('Authorization') != "") {
            $result = JwtService::de_auth($request);
            if (gettype($result) != "array") {
                die();
            }
            $u_id = $result['u_id'];
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'pros' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $type = "";
        switch (strtolower($request->type)) {
            case 'pd':
                $type = 'pd';
                break;
            case 'bg':
                $type = 'bg';
                break;
            case 'yt':
                $type = 'yt';
                break;
            default:
                $type = 'pd';
                break;
        }

        $pros = "";
        switch (strtolower($request->pros)) {
            case 'view':
                $pros = 'view';
                break;
            case 'share':
                $pros = 'share';
                break;
            default:
                $pros = 'view';
                break;
        }

        $args = [
            'type' => $type,
            'pros' => $pros,
            'id' => $request->id,
            'u_id' => $u_id,
        ];

        $args = array_merge($args, [
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => $u_id,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => $u_id,
            'record_status' => 'A',
        ]);

        $log = Log::insert($args);
        return $log;
    }

    public function lists_activities(Request $request)
    {
        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'action' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $obj = [
            'product' => [],
            'blog' => [],
            'youtube' => [],
        ];

        $type = ($request->type) ? $request->type : "pd";
        $action = ($request->action) ? $request->action : "share";
        if ($type == "pd" || $type == "all") {
            $obj['product'] = Log::lists_activities_product($u_id, $action);
        }

        if ($type == "bg" || $type == "all") {
            $obj['blog'] = Log::lists_activities_blog($u_id, $action);
        }

        if ($type == "yt" || $type == "all") {
            $obj['youtube'] = Log::lists_activities_youtube($u_id, $action);
        }

        return $obj;
    }

    public function youtube(Request $request, $id)
    {
        $favorite = false;
        if ($request->header('Authorization') != "") {
            $result = JwtService::de_auth($request);
            if (gettype($result) != "array") {
                die();
            }
            $u_id = $result['u_id'];
            $favorite = Favorite::is_like("yt", $id, $u_id);
        }
        $data = Product::detail_youtube($id);
        $obj = ['data_object' => $data, 'favorite' => $favorite];
        return $obj;
    }

    public function province(Request $request, $id = "")
    {
        $data = Addr::province_lists($id);
        $obj = [
            'data_object' => $data,
        ];
        return $obj;
    }

    public function distrcit(Request $request, $id)
    {
        $data = Addr::district_lists($id);
        $obj = [
            'data_object' => $data,
        ];
        return $obj;
    }

    public function sub_district(Request $request, $id)
    {
        $data = Addr::sub_district_lists($id);
        $obj = [
            'data_object' => $data,
        ];
        return $obj;
    }

    public function new_release(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'page' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $type = ($request->type) ? trim(strtolower($request->type)) : "pd";
        $page = ($request->page == 0 || $request->page == "") ? 1 : $request->page;

        $data = "";
        if ($type == "pd") {
            $data = Product::lists("", "", "", [], $page, "", "", true);
        } else {
            $data = Blog::lists("", "", "", [], $page, true);
        }
        return $data;
    }

    public function recive_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $args = [
            'token' => $request->token,
            'create_date' => date('Y-m-d H:i:s'),
        ];

        return Log::token_insert($args);
    }
}

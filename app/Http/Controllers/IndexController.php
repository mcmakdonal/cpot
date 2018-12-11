<?php

namespace App\Http\Controllers;

use App\Http\JwtService;
use App\Table\Blog;
use App\Table\Evaluation;
use App\Table\Log;
use App\Table\Product;
use Illuminate\Http\Request;
use Validator;

class IndexController extends Controller
{
    public function search_title_all(Request $request)
    {
        $product = $blog = $youtube = [];
        $obj = [
            'product' => [],
            'blog' => [],
            'youtube' => []
        ];
        $search = ($request->search) ? $request->search : "";
        $type = ($request->type) ? $request->type : "all";
        $pd_page = ($request->pd_page == 0 || $request->pd_page == "") ? 1 : $request->pd_page;
        $bg_page = ($request->bg_page == 0 || $request->bg_page == "") ? 1 : $request->bg_page;

        $mcat_id = ($request->mcat_id) ? $request->mcat_id : "";
        $scat_id = ($request->scat_id) ? $request->scat_id : "";

        $bmc_id = ($request->bmc_id) ? $request->bmc_id : "";
        $bsc_id = ($request->bsc_id) ? $request->bsc_id : "";

        if ($type == "pd" || $type == "all") {
            $product = Product::lists($search, $mcat_id, $scat_id, ['title'], $pd_page);
            $product['product_total'] = $product['totalPages'] * 10;
            array_push($obj['product'], $product);
        }

        if ($type == "bg" || $type == "all") {
            $blog = Blog::lists($search, $bmc_id, $bsc_id, ['title'], $bg_page);
            $blog['blog_total'] = $blog['totalPages'] * 10;
            array_push($obj['blog'], $blog);
        }

        if ($type == "yt" || $type == "all") {
            $youtube = Product::lists_youtube($search, ['title']);
            $args = [
                'data_object' => $youtube,
                'youtube_total' => count($youtube),
                'currentPage' => 0,
                'totalPages' => 0
            ];

            array_push($obj['youtube'], $args);
        }

        return $obj;
    }

    public function search_tag_all(Request $request)
    {
        $product = $blog = $youtube = [];
        $obj = [
            'product' => [],
            'blog' => [],
            'youtube' => []
        ];
        $search = ($request->search) ? $request->search : "";
        $type = ($request->type) ? $request->type : "all";
        $pd_page = ($request->pd_page == 0 || $request->pd_page == "") ? 1 : $request->pd_page;
        $bg_page = ($request->bg_page == 0 || $request->bg_page == "") ? 1 : $request->bg_page;

        $mcat_id = ($request->mcat_id) ? $request->mcat_id : "";
        $scat_id = ($request->scat_id) ? $request->scat_id : "";

        $bmc_id = ($request->bmc_id) ? $request->bmc_id : "";
        $bsc_id = ($request->bsc_id) ? $request->bsc_id : "";

        if ($type == "pd" || $type == "all") {
            $product = Product::lists($search, $mcat_id, $scat_id, ['tag'], $pd_page);
            $product['product_total'] = $product['totalPages'] * 10;
            array_push($obj['product'], $product);
        }

        if ($type == "bg" || $type == "all") {
            $blog = Blog::lists($search, $bmc_id, $bsc_id, ['tag'], $bg_page);
            $blog['blog_total'] = $blog['totalPages'] * 10;
            array_push($obj['blog'], $blog);
        }

        if ($type == "yt" || $type == "all") {
            $youtube = Product::lists_youtube($search, ['tag']);
            $args = [
                'data_object' => $youtube,
                'youtube_total' => count($youtube),
                'currentPage' => 0,
                'totalPages' => 0
            ];

            array_push($obj['youtube'], $args);
        }

        return $obj;
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

    public function youtube(Request $request, $id)
    {
        $favorite = false;
        if ($request->header('Authorization') != "") {
            $result = JwtService::de_auth($request);
            if (gettype($result) != "array") {
                die();
            }
            $u_id = $result['u_id'];
            // $favorite = Favorite::is_like("P", $id, $u_id);
        }
        // return $id;
        $data = Product::detail_youtube($id);
        $obj = [
            'data_object' => $data,
        ];
        return $obj;
    }

    public function province(Request $request)
    {
        $data = Product::province_lists();
        $obj = [
            'data_object' => $data,
        ];
        return $obj;
    }
}

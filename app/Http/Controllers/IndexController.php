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
            'data_object' => [],
        ];
        $search = ($request->search) ? $request->search : "";
        $type = ($request->type) ? $request->type : "all";

        $mcat_id = ($request->mcat_id) ? $request->mcat_id : "";
        $scat_id = ($request->scat_id) ? $request->scat_id : "";

        $bmc_id = ($request->bmc_id) ? $request->bmc_id : "";
        $bsc_id = ($request->bsc_id) ? $request->bsc_id : "";

        if ($type == "pd" || $type == "all") {
            $product = Product::lists($search, $mcat_id, $scat_id, ['title']);
            $args = [
                'product' => $product,
                'product_total' => count($product),
            ];

            array_push($obj['data_object'], $args);
        }

        if ($type == "bg" || $type == "all") {
            $blog = Blog::lists($search, $bmc_id, $bsc_id, ['title']);
            $args = [
                'blog' => $blog,
                'blog_total' => count($blog),
            ];

            array_push($obj['data_object'], $args);
        }

        if ($type == "yt" || $type == "all") {
            $youtube = Product::lists_youtube($search, ['title']);
            $args = [
                'youtube' => $youtube,
                'youtube_total' => count($youtube),
            ];

            array_push($obj['data_object'], $args);
        }

        return $obj;
    }

    public function search_tag_all(Request $request)
    {
        $product = $blog = $youtube = [];
        $obj = [
            'data_object' => [],
        ];
        $search = ($request->search) ? $request->search : "";
        $type = ($request->type) ? $request->type : "all";

        $mcat_id = ($request->mcat_id) ? $request->mcat_id : "";
        $scat_id = ($request->scat_id) ? $request->scat_id : "";

        $bmc_id = ($request->bmc_id) ? $request->bmc_id : "";
        $bsc_id = ($request->bsc_id) ? $request->bsc_id : "";

        if ($type == "pd" || $type == "all") {
            $product = Product::lists($search, $mcat_id, $scat_id, ['tag']);
            $args = [
                'product' => $product,
                'product_total' => count($product),
            ];

            array_push($obj['data_object'], $args);
        }

        if ($type == "bg" || $type == "all") {
            $blog = Blog::lists($search, $bmc_id, $bsc_id, ['tag']);
            $args = [
                'blog' => $blog,
                'blog_total' => count($blog),
            ];

            array_push($obj['data_object'], $args);
        }

        if ($type == "yt" || $type == "all") {
            $youtube = Product::lists_youtube($search, ['tag']);
            $args = [
                'youtube' => $youtube,
                'youtube_total' => count($youtube),
            ];

            array_push($obj['data_object'], $args);
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
                'record_status' => 'A'
            ];
            array_push($args, $ans);
        }

        $eva = Evaluation::answer($args);

        return $eva;
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
            'data_object' => $data
        ];
        return $obj;
    }

    // ////////////////////////////////////////////

    public function jwt(Request $request)
    {
        return JwtService::auth(['u_id' => 1]);
    }

    public function jwtdecode(Request $request)
    {
        $id = JwtService::de_auth($request);
        if (gettype($id) != "array") {
            die();
        } else {
            return $id;
        }
    }
}

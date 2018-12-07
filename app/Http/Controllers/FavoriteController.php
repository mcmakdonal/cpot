<?php

namespace App\Http\Controllers;

use App\Http\JwtService;
use App\Table\Favorite;
use Illuminate\Http\Request;
use Validator;

class FavoriteController extends Controller
{

    public function favorite(Request $request)
    {
        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];

        return Favorite::count_all($u_id);
    }

    public function favorite_all(Request $request)
    {
        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];
        $type = $request->type;
        $data = [];
        if ($type == "pd") {
            $data = Favorite::lists_product($u_id);
        } elseif ($type == "bg") {
            $data = Favorite::lists_blog($u_id);
        } elseif ($type == "yt") {
            $data = Favorite::lists_youtube($u_id);
        } else {
            $data = Favorite::lists_all($u_id);
            return $data;
        }

        return [
            'status' => true,
            'message' => 'Success',
            'data_object' => [
                'total' => count($data),
                'items' => $data,
            ],
        ];
    }

    public function favorite_product(Request $request)
    {
        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];

        $product = Favorite::lists_product($u_id);
        return [
            'status' => true,
            'message' => 'Success',
            'data_object' => [
                'total' => count($product),
                'items' => $product,
            ],
        ];
    }

    public function favorite_blog(Request $request)
    {
        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];

        $blog = Favorite::lists_blog($u_id);
        return [
            'status' => true,
            'message' => 'Success',
            'data_object' => [
                'total' => count($blog),
                'items' => $blog,
            ],
        ];
    }

    public function favorite_like(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];
        $type = strtolower($request->type);
        $id = $request->id;

        if ($type === "pd" || $type === "bg" || $type === "yt") {
            $result = Favorite::insert($type, $id, $u_id);
            return $result;
        } else {
            return [
                'status' => false,
                'message' => 'type not macth',
            ];
        }
    }

    public function favorite_unlike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => 'Please fill all data',
            ];
        }

        $result = JwtService::de_auth($request);
        if (gettype($result) != "array") {
            die();
        }
        $u_id = $result['u_id'];
        $type = strtolower($request->type);
        $id = $request->id;

        if ($type === "pd" || $type === "bg" || $type === "yt") {
            $result = Favorite::delete($type, $id, $u_id);

            return $result;
        } else {
            return [
                'status' => false,
                'message' => 'type not macth',
            ];
        }
    }
}

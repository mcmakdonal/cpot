<?php

namespace App\Http\Controllers;

use App\Http\JwtService;
use App\Table\Blog;
use App\Table\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function search_title_all(Request $request)
    {
        $product = Product::lists($request->search, "", "", ['title']);
        $blog = Blog::lists($request->search, "", "", ['title']);
        $youtube = Product::lists_youtube($request->search, ['title']);
        $obj = [
            'data_object' => [
                'product' => $product,
                'product_total' => count($product),
                'blog' => $blog,
                'blog_total' => count($blog),
                'youtube' => $youtube,
                'youtube_total' => count($youtube),
            ],
        ];

        return $obj;
    }

    public function search_tag_all(Request $request)
    {
        $product = Product::lists($request->search, "", "", ['tag']);
        $blog = Blog::lists($request->search, "", "", ['tag']);
        $youtube = Product::lists_youtube($request->search, ['tag']);
        $obj = [
            'data_object' => [
                'product' => $product,
                'product_total' => count($product),
                'blog' => $blog,
                'blog_total' => count($blog),
                'youtube' => $youtube,
                'youtube_total' => count($youtube),
            ]
        ];

        return $obj;
    }

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

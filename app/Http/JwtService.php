<?php

namespace App\Http;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use \Firebase\JWT\JWT;

class JwtService extends ServiceProvider
{

    public static function auth($data, $scope = 'user')
    {
        $key = env('JWT_SECRET');
        $token = array(
            "iss" => url('/'),
            "aud" => url('/'),
            "iat" => date('Y-m-d H:i:s'), // iat (issued at) : ใช้เก็บเวลาที่ token นี้เกิดปัญหา
            "nbf" => date('Y-m-d H:i:s'), // nbf (not before) : เป็นเวลาที่บอกว่า token จะเริ่มใช้งานได้เมื่อไหร่
            'scope' => $scope,
            "sub" => $data,
        );

        $jwt = JWT::encode($token, $key);
        return "Bearer ".$jwt;
    }

    public static function de_auth(Request $request)
    {
        $key = env('JWT_SECRET');
        $Bearer = str_replace("Bearer ", "", $request->header('Authorization'));
        /**
         * You can add a leeway to account for when there is a clock skew times between
         * the signing and verifying servers. It is recommended that this leeway should
         * not be bigger than a few minutes.
         *
         * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
         */
        JWT::$leeway = 60; // $leeway in seconds
        try {
            $decoded = JWT::decode($Bearer, $key, array('HS256'));
            // return $decoded;
            $decoded = (array) $decoded;
            if (array_key_exists("sub",$decoded)) {
                return [
                    'status' => true,
                    'message' => 'success',
                    'u_id' =>  $decoded['sub']->u_id
                ];
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Access Denind'
                ])->send();
                die();
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ])->send();
            die();
        }

    }
    
}

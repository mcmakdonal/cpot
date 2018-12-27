<?php

namespace App\Table;

use DB;
use Illuminate\Support\Facades\Log as Logs;
use Illuminate\Support\ServiceProvider;

class Log extends ServiceProvider
{
    public static function lists($type = 'pd', $pros = 'share')
    {
        $matchThese[] = ['record_status', '=', 'A'];
        $data = DB::table('tbl_log_user')
            ->select('*')
            ->where($matchThese)
            ->get()->toArray();

        return $data;
    }

    public static function share_lists()
    {
        $product = self::lists_activities_product();
        $blog = self::lists_activities_blog();
        $youtube = self::lists_activities_youtube();

        $data = array_merge($product, $blog, $youtube);
        return $data;
    }

    public static function lists_activities_product($u_id = "", $type = "share")
    {
        $matchThese = [];
        $matchThese[] = ['tbl_product.record_status', '=', 'A'];
        $matchThese[] = ['tbl_log_user.type', '=', 'pd'];
        $matchThese[] = ['tbl_log_user.pros', '=', $type];
        if ($u_id != "") {
            $matchThese[] = ['tbl_log_user.u_id', '=', $u_id];
        }
        $product = DB::table('tbl_log_user')
            ->select('l_id', 'type', 'pd_name as title', DB::raw('count(tbl_product.pd_id) counter'))
            ->join('tbl_product', 'tbl_product.pd_id', '=', 'tbl_log_user.id')
            ->where($matchThese)
            ->groupBy('tbl_product.pd_id')
            ->get()->toArray();

        return $product;
    }

    public static function lists_activities_blog($u_id = "", $type = "share")
    {
        $matchThese = [];
        $matchThese[] = ['tbl_blog.record_status', '=', 'A'];
        $matchThese[] = ['tbl_log_user.type', '=', 'bg'];
        $matchThese[] = ['tbl_log_user.pros', '=', $type];
        if ($u_id != "") {
            $matchThese[] = ['tbl_log_user.u_id', '=', $u_id];
        }
        $blog = DB::table('tbl_log_user')
            ->select('l_id', 'type', 'bg_title as title', DB::raw('count(tbl_blog.bg_id) counter'))
            ->join('tbl_blog', 'tbl_blog.bg_id', '=', 'tbl_log_user.id')
            ->where($matchThese)
            ->groupBy('tbl_blog.bg_id')
            ->get()->toArray();

        return $blog;
    }

    public static function lists_activities_youtube($u_id = "", $type = "share")
    {
        $matchThese = [];
        $matchThese[] = ['tbl_youtube.record_status', '=', 'A'];
        $matchThese[] = ['tbl_log_user.type', '=', 'yt'];
        $matchThese[] = ['tbl_log_user.pros', '=', $type];
        if ($u_id != "") {
            $matchThese[] = ['tbl_log_user.u_id', '=', $u_id];
        }
        $youtube = DB::table('tbl_log_user')
            ->select('l_id', 'type', 'my_title as title', DB::raw('count(tbl_youtube.my_id) counter'))
            ->join('tbl_youtube', 'tbl_youtube.my_id', '=', 'tbl_log_user.id')
            ->where($matchThese)
            ->groupBy('tbl_youtube.my_id')
            ->get()->toArray();

        return $youtube;
    }

    public static function insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_log_user')->insert($args);
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

    public static function token_insert($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_device_token')->insert($args);
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

    public static function dateProduct()
    {
        DB::beginTransaction();
        $matchThese = [];
        $matchThese[] = ['tbl_product.record_status', '=', 'A'];
        $tbl_product = DB::table('tbl_product')
            ->select('pd_id')
            ->where($matchThese)
            ->orderByRaw('rand()')
            ->groupBy('pd_id')
            ->limit(1)
            ->get()->toArray();

        // return $tbl_product;

        $args = [
            'create_date' => date('Y-m-d H:i:s'),
        ];
        $status = DB::table('tbl_product')->where('pd_id', $tbl_product[0]->pd_id)->update($args);
        if ($status) {
            DB::commit();
            self::send_nofti();
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

    public static function send_nofti()
    {
        $tbl_device_token = DB::table('tbl_device_token')
            ->select('token')
            ->groupBy('token')
            ->get()->toArray();

        $args['device_token'] = [];
        foreach ($tbl_device_token as $k => $v) {
            // array_push($args['device_token'], $v->token);
            /*
            $token => ให้ใส่เป็น devices_token ที่มีมาใน services
             */
            $token = array($v->token);
            // $token = array(
            //     "cVzQCdXx73s:APA91bFYYa4ea0bVgffwA8VRkT2pPzcprdN4e6hV7fkhjSr6jAUXuTDhg046HGglku0Rk_vR_QH7hAA1jspD7MvK2vDMHqGXUunQOqxJbjlznLCVeBmXZGpqyd3YaKYijLTFc6Y6FYpJ"
            // );
            $payload_notification = array(
                'title' => 'MCULTURE Mobile', // เปลี่ยนตัวข้อเป็นอะไรก็ได้เช่น 'ข้อความตอบกลับจากเจ้าหน้า'
                'body' => 'สินค้าใหม่', // แก้ตรงนี้ใส่ข้อความที่ admin ตอบกลับ
                // 'sound' => 'default',
                // 'icon' => 1,
                // 'click_action' => 'OPEN_ACTIVITY_1',
            );
            $payload_data = array(
                'answer' => 'success',
            );

            $url = 'https://fcm.googleapis.com/fcm/send';
            $fields = array(
                'registration_ids' => $token,
                'priority' => 'normal',
                'notification' => $payload_notification,
                'data' => $payload_data,
            );
            $headers = array(
                'Authorization: key=AAAANAd6iTc:APA91bE0vkUWsO8GFhKdCiZY75oKMIDy7nLkGX4j9fYZoJgvyqEU3TQj5zfHSmm4RfgHahMkXktVbWJxqIwD3VoFRhus55dbHanGccVue03XQUHGzD9QE09IA0_Bajofp6lInC22gIEn',
                'Content-Type: application/json',
            );
            // Open connection
            $ch = curl_init();
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Disabling SSL Certificate support temporary
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            // Execute post
            $result = curl_exec($ch);
            if ($result === false) {
                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);
            Logs::debug($result);
            $data = json_decode($result);
            // if ($data->success) {
            //     return true;
            // } else {
            //     return false;
            // }

        }

    }

}

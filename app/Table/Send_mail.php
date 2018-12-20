<?php

namespace App\Table;

use Mail;
use Illuminate\Support\ServiceProvider;

class Send_mail extends ServiceProvider
{

    public static function send_mail($to_name, $to_email, $body)
    {
        $data = array('name' => $to_name, "body" => $body);

        try {
            $status = Mail::send('emails.mail', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('รหัสผ่านใหม่ในการเข้าสู่ระบบ MCulture Mobile');
                $message->from('webmaster.mculture@gmail.com', 'Webmaster Mculture');
            });

            return [
                'status' => true,
                'message' => "สำเร็จ ! รหัสผ่านได้ถูกส่งไปยังอีเมล : $to_email",
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => 'ผิดพลาด ! ' . $th->getMessage(),
            ];
        }
    }

    public static function nofti_register($to_name, $to_email, $body){
        $data = array('name' => $to_name, "body" => $body);

        try {
            $status = Mail::send('emails.mail', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('ยินดีต้อนรับท่านเข้าสู่ระบบ MCulture Mobile');
                $message->from('webmaster.mculture@gmail.com', 'Webmaster Mculture');
            });

            return [
                'status' => true,
                'message' => "ยินดีต้อนรับท่านเข้าสู่ระบบ MCulture Mobile",
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => 'ผิดพลาด ! ' . $th->getMessage(),
            ];
        }
    }

}

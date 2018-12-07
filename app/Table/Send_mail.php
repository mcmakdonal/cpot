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
                $message->to($to_email, $to_name)->subject('New Password for login');
                $message->from('webmaster.mculture@gmail.com', 'Webmaster Mculture');
            });

            return [
                'status' => true,
                'message' => "Success ! New password has been send to email : $to_email",
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => 'Fail ! ' . $th->getMessage(),
            ];
        }
    }

}

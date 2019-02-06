<?php

namespace App;
use App\Table\Admin;

class Helper
{
    public function bladeHelper($someValue)
    {
        return "increment $someValue";
    }

    public function startQueryLog()
    {
        \DB::enableQueryLog();
    }

    public function showQueries()
    {
        dd(\DB::getQueryLog());
    }

    public static function instance()
    {
        return new Helper();
    }

    public function check_role($role2check = "")
    {
        $ad_permission = \Cookie::get('ad_permission');
        if($ad_permission == "S"){
            return true;
        }
        $ad_role = json_decode(\Cookie::get('ad_role'));
        if(in_array( $role2check,$ad_role )){
            return true;
        }

        return false;
    }
}

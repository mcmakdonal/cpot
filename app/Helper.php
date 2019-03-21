<?php

namespace App;

use App\Table\Permission;

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
        $per_id = \Cookie::get('per_id');

        $per = Permission::get($per_id);
        if (count($per) == 0) {
            header("location ". url("backend-login"));
        }
        // if($ad_permission == "S"){
        //     return true;
        // }
        $ad_role = json_decode($per[0]->per_role);
        if (in_array($role2check, $ad_role)) {
            return true;
        }

        return false;
    }
}

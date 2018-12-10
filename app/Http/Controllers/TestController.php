<?php

namespace App\Http\Controllers;

use App\Http\JwtService;
use App\Table\Product;
use DB;
use Illuminate\Http\Request;

class TestController extends Controller
{

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

    public function product()
    {
        $file_path = public_path('product.xlsx');
        $reader = \Excel::load($file_path); //this will load file
        $sheets = $reader->noHeading()->get()->toArray(); //this will convert file to array
        $sheets = $sheets[2];
        unset($sheets[0]);
        foreach ($sheets as $k => $v) {
            unset($sheets[$k]['19']);
            unset($sheets[$k]['20']);
            unset($sheets[$k]['21']);
            unset($sheets[$k]['22']);
            unset($sheets[$k]['23']);
        }
        $sheets = array_values($sheets);

        // dd($sheets);

        $args = [];
        foreach ($sheets as $k => $v) {
            $args[] = [
                'pd_name' => $v[1],
                'pd_rating' => (int) $v[2],
                'pd_description' => $v[3],
                'pd_price' => (int) $v[4],
                'pd_sprice' => (int) $v[5],
                'pd_store' => $v[6],
                'pd_province' => $this->province($v[7]),
                'pd_phone' => $v[8],
                'pd_history' => $v[10],
                'pd_featured' => $v[11],
                'pd_process' => $v[12],
                'pd_detail' => $v[13],
                'pd_benefits' => $v[14],
                'pd_tag' => $v[15],
                'pd_ref' => $v[16],
                'mcat_id' => (int) $v[17],
                'scat_id' => (int) $v[18],
                'pd_image' => $v[9],
                'create_date' => date('Y-m-d H:i:s'),
                'create_by' => 1,
                'update_date' => date('Y-m-d H:i:s'),
                'update_by' => 1,
                'record_status' => 'A',
            ];
        }

        // dd($args);

        foreach ($args as $k => $v) {
            DB::beginTransaction();
            $id = DB::table('tbl_product')->insertGetId($v);
            if ($id) {
                $img = explode(",", $v['pd_image']);
                foreach ($img as $kk => $vv) {
                    if ($vv != "" || $vv != null || $vv != " ") {
                        $arr = [
                            'pd_id' => $id,
                            'path' => '/product_images/' . strtolower(trim($vv)),
                        ];
                    }

                    $result = DB::table('tbl_product_images')->insert($arr);
                    if ($result) {
                        DB::commit();
                        echo "Success";
                        echo "<br />";
                    } else {
                        DB::rollBack();
                        echo "Fail Insert Image";
                        echo "<br />";
                    }
                }
            } else {
                DB::rollBack();
                echo 'Fail Insert Product';
                echo "<br />";
            }
        }
    }

    public function blog()
    {
        $file_path = public_path('blog.xlsx');
        $reader = \Excel::load($file_path); //this will load file
        $sheets = $reader->noHeading()->get()->toArray(); //this will convert file to array
        // $sheets = $sheets[2];
        // unset($sheets[0]);
        $sheets = $sheets[1];
        foreach ($sheets as $k => $v) {
            unset($sheets[$k]['13']);
            unset($sheets[$k]['14']);
            unset($sheets[$k]['15']);
            if ($sheets[$k]['0'] == null || $sheets[$k]['0'] == "null") {
                unset($sheets[$k]);
            }

            if ($k == 0) {
                unset($sheets[0]);
            }
        }
        $sheets = array_values($sheets);

        // dd($sheets);

        $args = [];
        foreach ($sheets as $k => $v) {
            $args[] = [
                'bg_title' => $v[1],
                'bg_image' => $v[8],
                'bg_description' => trim($v[3]),
                'bg_embed' => $v[9],
                'bg_ref' => $v[10],
                'bg_store' => $v[2],
                'bg_featured' => $v[4],
                'bg_process' => trim($v[5]),
                'bg_detail' => trim($v[6]),
                'bg_benefits' => $v[7],
                'bmc_id' => (int) $v[11],
                'bsc_id' => (int) $v[12],
                'create_date' => date('Y-m-d H:i:s'),
                'create_by' => 1,
                'update_date' => date('Y-m-d H:i:s'),
                'update_by' => 1,
                'record_status' => 'A',
            ];
        }

        // dd($args);

        foreach ($args as $k => $v) {
            DB::beginTransaction();
            $id = DB::table('tbl_blog')->insertGetId($v);
            if ($id) {
                $img = explode(",", $v['bg_image']);
                foreach ($img as $kk => $vv) {
                    if ($vv != "" || $vv != null || $vv != " ") {
                        $arr = [
                            'bg_id' => $id,
                            'path' => '/blog_images/' . strtolower(trim($vv)),
                        ];
                    }

                    $result = DB::table('tbl_blog_images')->insert($arr);
                    if ($result) {
                        DB::commit();
                        echo "Success";
                        echo "<br />";
                    } else {
                        DB::rollBack();
                        echo "Fail Insert Image";
                        echo "<br />";
                    }
                }
            } else {
                DB::rollBack();
                echo 'Fail Insert Product';
                echo "<br />";
            }
        }
    }

    public function province($name)
    {
        $json = '[{"province_id":1,"province_name":"กรุงเทพมหานคร"},{"province_id":2,"province_name":"สมุทรปราการ"},{"province_id":3,"province_name":"นนทบุรี"},{"province_id":4,"province_name":"ปทุมธานี"},{"province_id":5,"province_name":"พระนครศรีอยุธยา"},{"province_id":6,"province_name":"อ่างทอง"},{"province_id":7,"province_name":"ลพบุรี"},{"province_id":8,"province_name":"สิงห์บุรี"},{"province_id":9,"province_name":"ชัยนาท"},{"province_id":10,"province_name":"สระบุรี"},{"province_id":11,"province_name":"ชลบุรี"},{"province_id":12,"province_name":"ระยอง"},{"province_id":13,"province_name":"จันทบุรี"},{"province_id":14,"province_name":"ตราด"},{"province_id":15,"province_name":"ฉะเชิงเทรา"},{"province_id":16,"province_name":"ปราจีนบุรี"},{"province_id":17,"province_name":"นครนายก"},{"province_id":18,"province_name":"สระแก้ว"},{"province_id":19,"province_name":"นครราชสีมา"},{"province_id":20,"province_name":"บุรีรัมย์"},{"province_id":21,"province_name":"สุรินทร์"},{"province_id":22,"province_name":"ศรีสะเกษ"},{"province_id":23,"province_name":"อุบลราชธานี"},{"province_id":24,"province_name":"ยโสธร"},{"province_id":25,"province_name":"ชัยภูมิ"},{"province_id":26,"province_name":"อำนาจเจริญ"},{"province_id":27,"province_name":"บึงกาฬ"},{"province_id":28,"province_name":"หนองบัวลำภู"},{"province_id":29,"province_name":"ขอนแก่น"},{"province_id":30,"province_name":"อุดรธานี"},{"province_id":31,"province_name":"เลย"},{"province_id":32,"province_name":"หนองคาย"},{"province_id":33,"province_name":"มหาสารคาม"},{"province_id":34,"province_name":"ร้อยเอ็ด"},{"province_id":35,"province_name":"กาฬสินธุ์"},{"province_id":36,"province_name":"สกลนคร"},{"province_id":37,"province_name":"นครพนม"},{"province_id":38,"province_name":"มุกดาหาร"},{"province_id":39,"province_name":"เชียงใหม่"},{"province_id":40,"province_name":"ลำพูน"},{"province_id":41,"province_name":"ลำปาง"},{"province_id":42,"province_name":"อุตรดิตถ์"},{"province_id":43,"province_name":"แพร่"},{"province_id":44,"province_name":"น่าน"},{"province_id":45,"province_name":"พะเยา"},{"province_id":46,"province_name":"เชียงราย"},{"province_id":47,"province_name":"แม่ฮ่องสอน"},{"province_id":48,"province_name":"นครสวรรค์"},{"province_id":49,"province_name":"อุทัยธานี"},{"province_id":50,"province_name":"กำแพงเพชร"},{"province_id":51,"province_name":"ตาก"},{"province_id":52,"province_name":"สุโขทัย"},{"province_id":53,"province_name":"พิษณุโลก"},{"province_id":54,"province_name":"พิจิตร"},{"province_id":55,"province_name":"เพชรบูรณ์"},{"province_id":56,"province_name":"ราชบุรี"},{"province_id":57,"province_name":"กาญจนบุรี"},{"province_id":58,"province_name":"สุพรรณบุรี"},{"province_id":59,"province_name":"นครปฐม"},{"province_id":60,"province_name":"สมุทรสาคร"},{"province_id":61,"province_name":"สมุทรสงคราม"},{"province_id":62,"province_name":"เพชรบุรี"},{"province_id":63,"province_name":"ประจวบคีรีขันธ์"},{"province_id":64,"province_name":"นครศรีธรรมราช"},{"province_id":65,"province_name":"กระบี่"},{"province_id":66,"province_name":"พังงา"},{"province_id":67,"province_name":"ภูเก็ต"},{"province_id":68,"province_name":"สุราษฎร์ธานี"},{"province_id":69,"province_name":"ระนอง"},{"province_id":70,"province_name":"ชุมพร"},{"province_id":71,"province_name":"สงขลา"},{"province_id":72,"province_name":"สตูล"},{"province_id":73,"province_name":"ตรัง"},{"province_id":74,"province_name":"พัทลุง"},{"province_id":75,"province_name":"ปัตตานี"},{"province_id":76,"province_name":"ยะลา"},{"province_id":77,"province_name":"นราธิวาส"}]';
        $arr = json_decode($json);
        foreach ($arr as $k => $v) {
            if ($v->province_name == $name) {
                return $v->province_id;
            }
        }

        return "";
    }
}

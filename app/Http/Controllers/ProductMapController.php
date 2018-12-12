<?php

namespace App\Http\Controllers;

use App\Table\Product;
use Illuminate\Http\Request;

class ProductMapController extends Controller
{
    public function __construct()
    {
        $this->middleware('islogin');
    }

    public function index()
    {
        $data = Product::lists();
        // dd($data['data_object']);
        return view('product-match.index', ['data' => $data['data_object']]);
    }

    public function matching(Request $request, $id)
    {
        $data = Product::detail($id);
        if (count($data) == 0) {
            return redirect("/product-macth")->with('status', 'error');
        }
        $tag = $data[0]->pd_tag;
        $youtube = Product::search($tag);
        $select = Product::select_youtube($id);
        // dd($youtube);
        return view('product-match.match', ['data' => $data, 'youtube' => $youtube['items'], 'select' => $select]);
    }

    public function store(Request $request, $id)
    {
        $args = [];
        if ($request->youtube) {
            foreach ($request->youtube as $k => $v) {
                $json = json_decode($v);
                $args[] = [
                    'my_title' => $json->my_title,
                    'my_href' => $json->my_href,
                    'my_bytag' => $request->pd_tag,
                    'my_desc' => $json->my_desc,
                    'pd_id' => $id,
                    'my_image' => $json->my_image,
                    'create_date' => date('Y-m-d H:i:s'),
                    'create_by' => 1,
                    'update_date' => date('Y-m-d H:i:s'),
                    'update_by' => 1,
                    'record_status' => 'A',
                ];
            }
        } else {
            return redirect()->back()->withErrors(array('error' => 'error'));
        }

        $result = Product::insert_youtube($args, $id);
        if ($result['status']) {
            return redirect("/product-match/$id/matching")->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => 'error'));
        }
    }

    public function search(Request $request)
    {
        $q = str_replace(",", "|", $request->q);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=25&order=relevance&q=$q&key=AIzaSyASB9JR0hgdStc6q6-WMmVj6u0B1xrKDLY",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [
                'status' => false,
                'message' => 'error',
                'data' => $err,
            ];
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'items' => json_decode($response)->items,
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Table\Evaluation;
use App\Table\User;
use App\Table\Product;
use App\Table\Log;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('islogin:6');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function evaluation()
    {
        $data = Evaluation::lists();
        foreach($data as $k => $v){
            $data[$k]->items = Evaluation::get_question_point($v->et_id);
        }
        // dd($data);
        return view('report.evaluation', ['data' => $data]);
    }

    public function user()
    {
        $data = User::lists();
        return view('report.user', ['data' => $data]);
    }

    public function tag()
    {
        $data = Product::tag_lists();
        return view('report.tag', ['data' => $data]);
    }

    public function share()
    {
        $data = Log::share_lists();
        // dd($data);
        return view('report.share', ['data' => $data]);
    }
}

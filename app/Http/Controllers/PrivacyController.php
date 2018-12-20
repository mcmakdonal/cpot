<?php

namespace App\Http\Controllers;

use App\Table\Privacy;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    public function __construct()
    {
        $this->middleware('islogin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Privacy::lists();
        return view('privacy.index', ['data' => $data]);
    }
}

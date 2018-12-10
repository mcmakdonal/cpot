<?php

namespace App\Http\Controllers;

use App\Table\Evaluation;
use Illuminate\Http\Request;
use Validator;

class EvaluationController extends Controller
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
        $data = Evaluation::lists();
        return view('evaluation.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('evaluation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'et_topic' => 'required',
            'et_question.*' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $args = [
            'et_topic' => $request->et_topic,
            'et_active' => "I",
            'create_date' => date('Y-m-d H:i:s'),
            'create_by' => \Cookie::get('ad_id'),
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => \Cookie::get('ad_id'),
            'record_status' => 'A',
        ];

        $question = [];
        foreach ($request->question as $k => $v) {
            $q = [
                'q_question' => $v,
            ];
            array_push($question, $q);
        }

        $status = Evaluation::insert($args, $question);
        if ($status['status']) {
            $id = $status['id'];
            return redirect("/evaluation/$id/edit")->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => 'error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Evaluation::get_data($id);
        return view('evaluation.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'et_topic' => 'required',
            'et_question.*' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $args = [
            'et_topic' => $request->et_topic,
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => \Cookie::get('ad_id'),
        ];

        $question = [];
        foreach ($request->question as $k => $v) {
            $q = [
                'q_question' => $v,
            ];
            array_push($question, $q);
        }

        $status = Evaluation::update($args, $question, $id);
        if ($status['status']) {
            return redirect("/evaluation/$id/edit")->with('status', 'บันทึกสำเร็จ');
        } else {
            return redirect()->back()->withErrors(array('error' => 'error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id == '' || $id == null) {
            return response()->json([
                'status' => false,
            ]);
        }
        $result = Evaluation::delete($id);
        return response()->json($result);
    }

    public function active(Request $request)
    {
        $id = $request->id;
        if ($id == "" || $id == null) {
            return response()->json([
                'status' => false,
                'message' => 'error',
            ]);
        } else {
            $ad_id = \Cookie::get('ad_id');
            $result = Evaluation::active($id, $ad_id, "A");
            return response()->json($result);
        }
    }

    public function unactive(Request $request)
    {
        $id = $request->id;
        if ($id == "" || $id == null) {
            return response()->json([
                'status' => false,
                'message' => 'error',
            ]);
        } else {
            $ad_id = \Cookie::get('ad_id');
            $result = Evaluation::active($id, $ad_id, "I");
            return response()->json($result);
        }
    }
}

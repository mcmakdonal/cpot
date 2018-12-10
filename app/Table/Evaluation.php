<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Evaluation extends ServiceProvider
{
    public static function lists()
    {
        $matchThese[] = ['tbl_evaluation_topic.record_status', '=', 'A'];
        $data = DB::table('tbl_evaluation_topic')
            ->select('tbl_evaluation_topic.*', DB::raw('count(tbl_answer_topic.et_id) totol_vote'))
            ->leftJoin('tbl_answer_topic', 'tbl_answer_topic.et_id', '=', 'tbl_evaluation_topic.et_id')
            ->where($matchThese)
            ->groupBy('tbl_evaluation_topic.et_id')
            ->orderBy('tbl_evaluation_topic.et_id', 'desc')
            ->get()->toArray();

        return $data;
    }

    public static function get_data($id = "")
    {
        $matchThese[] = ['record_status', '=', 'A'];
        $matchThese[] = ['et_id', '=', $id];

        $tbl_evaluation_topic = DB::table('tbl_evaluation_topic')
            ->select('et_id', 'et_topic', 'et_active')
            ->where($matchThese)
            ->get()->toArray();

        $tbl_question_topic = DB::table('tbl_question_topic')
            ->select('*')
            ->where('et_id', '=', $id)
            ->get()->toArray();

        return [
            'question' => $tbl_question_topic,
            'evaluation' => $tbl_evaluation_topic,
        ];
    }

    public static function get_question()
    {
        $matchThese[] = ['et_active', '=', 'A'];
        $matchThese[] = ['record_status', '=', 'A'];

        $tbl_evaluation_topic = DB::table('tbl_evaluation_topic')
            ->select('et_id', 'et_topic')
            ->where($matchThese)
            ->get()->toArray();

        $ques = [];
        foreach($tbl_evaluation_topic as $k => $v){
        $question = DB::table('tbl_question_topic')
            ->select('*')
            ->where('et_id', '=', $v->et_id)
            ->orderBy('q_id', 'asc')
            ->get()->toArray();
            $obj = [
                'topic' => $v,
                'question' => $question
            ];

            $ques[][] = $obj;
        }

        return $ques;
    }

    public static function get_question_w_point($et_id)
    {
        $matchThese[] = ['tbl_question_topic.et_id', '=', $et_id];
        // $matchThese[] = ['tbl_question_topic.record_status', '=', 'A'];

        $data = DB::table('tbl_question_topic')
            ->select('tbl_question_topic.q_id', 'tbl_question_topic.et_id', 'tbl_question_topic.q_question', DB::raw('SUM(tbl_answer_topic.q_point) sum_point'))
            ->leftJoin('tbl_answer_topic', 'tbl_answer_topic.q_id', '=', 'tbl_question_topic.q_id')
            ->where($matchThese)
            ->groupBy('tbl_question_topic.q_id')
            ->orderBy('tbl_question_topic.q_id', 'asc')
            ->get()->toArray();

        return $data;
    }

    public static function insert($args, $question)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_evaluation_topic')->insertGetId($args);
        if ($status) {
            foreach ($question as $k => $v) {
                $question[$k]['et_id'] = $status;
            }

            $result = DB::table('tbl_question_topic')->insert($question);
            if ($result) {
                DB::commit();
                return [
                    'status' => true,
                    'message' => 'Success',
                    'id' => $status,
                ];
            } else {
                DB::rollBack();
                return [
                    'status' => false,
                    'message' => 'Fail',
                ];
            }

        } else {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }

    public static function update($args, $question, $id)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_evaluation_topic')->where('et_id', $id)->update($args);
        if ($status) {
            $result = DB::table('tbl_question_topic')->where('et_id', '=', $id)->delete();
            foreach ($question as $k => $v) {
                $question[$k]['et_id'] = $id;
            }
            $result = DB::table('tbl_question_topic')->insert($question);
            if ($result) {
                DB::commit();
                return [
                    'status' => true,
                    'message' => 'Success',
                    'id' => $status,
                ];
            } else {
                DB::rollBack();
                return [
                    'status' => false,
                    'message' => 'Fail',
                ];
            }

        } else {
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }

    public static function delete($id)
    {
        DB::beginTransaction();
        $args = [
            'update_date' => date('Y-m-d H:i:s'),
            'update_by' => 1,
            'record_status' => 'I',
        ];
        $status = DB::table('tbl_evaluation_topic')->where('et_id', $id)->update($args);
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

    public static function active($id, $ad_id, $status = "A")
    {
        DB::beginTransaction();
        if ($status) {
            $args = [
                'et_active' => $status,
                'update_date' => date('Y-m-d H:i:s'),
                'update_by' => $ad_id,
            ];
            $status = DB::table('tbl_evaluation_topic')->where('et_id', '=', $id)->update($args);
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
        } else {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Fail',
            ];
        }
    }

    public static function answer($args)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_answer_topic')->insert($args);
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


    public static function total_user_evaluation($u_id)
    {
        $matchThese[] = ['tbl_answer_topic.u_id', '=', $u_id];
        $matchThese[] = ['tbl_evaluation_topic.record_status', '=', 'A'];

        $data = DB::table('tbl_evaluation_topic')
            ->select('tbl_evaluation_topic.et_id', 'tbl_evaluation_topic.et_topic')
            ->leftJoin('tbl_answer_topic', 'tbl_answer_topic.et_id', '=', 'tbl_evaluation_topic.et_id')
            ->where($matchThese)
            ->groupBy('tbl_evaluation_topic.et_id')
            ->orderBy('tbl_evaluation_topic.et_id', 'asc')
            ->get()->toArray();

        return $data;
    }

}

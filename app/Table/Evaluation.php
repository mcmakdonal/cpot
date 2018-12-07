<?php

namespace App\Table;

use DB;
use Illuminate\Support\ServiceProvider;

class Evaluation extends ServiceProvider
{
    public static function lists()
    {
        $matchThese[] = ['record_status', '=', 'A'];
        $data = DB::table('tbl_evaluation_topic')
            ->select('*')
            ->where($matchThese)
            ->orderBy('et_id', 'desc')
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

        $et_id = $tbl_evaluation_topic[0]->et_id;

        $tbl_question_topic = DB::table('tbl_question_topic')
            ->select('*')
            ->where('et_id', '=', $et_id)
            ->orderBy('q_id', 'asc')
            ->get()->toArray();

        return [
            'question' => $tbl_question_topic,
            'topic' => $tbl_evaluation_topic[0],
        ];
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

    public static function active($id, $ad_id)
    {
        DB::beginTransaction();
        $status = DB::table('tbl_evaluation_topic')->update(['et_active' => 'I']);
        if ($status) {
            $args = [
                'et_active' => 'A',
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

}

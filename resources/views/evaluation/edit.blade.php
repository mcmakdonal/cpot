@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'แก้ไขแบบประเมิน'])
<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">แก้ไขแบบประเมิน</h4>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif {!! Form::open(['url' => '/evaluation/'.$data['evaluation'][0]->et_id,'class' => 'form-auth-small', 'method' => 'PUT','files' => false]) !!}
            <div class="form-row">
                <div class="col-md-10 mb-3">
                    <label for="">หัวข้อ : </label>
                <input type="text" class="form-control" id="" name="et_topic" value="{{ $data['evaluation'][0]->et_topic }}" required="">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="">เพิ่มคำถาม : </label>
                    <button type="button" class="btn btn-info form-control" onclick="add_question()"><span class="ti-plus"></span></button>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 mb-3">
                    <hr />
                </div>
            </div>
            <div class="form-row" id="q_target">
                @foreach($data['question'] as $k => $v)
                    @php
                        $class = uniqid();
                    @endphp
                    <div class="col-md-11 mb-3 question {{ $class }}">
                        @if($k == 0)
                            <label for="">คำถาม : </label>
                        @endif
                        <label class="sr-only" for="">คำถาม</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">{{$k + 1}}.</div>
                            </div>
                            <input type="text" class="form-control" name="question[]" value="{{$v->q_question}}" required>
                        </div>
                    </div>
                    <div class="col-md-1 mb-3 {{ $class }}">
                        @if($k != 0)
                            <button type="button" data="{{ $class }}" onclick="remove_block(this)" class="btn btn-wanring"><span class="ti-trash"></span></button>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="form-group text-center mt-3">
                <button type="submit" class="btn btn-success">บันทึก</button>
                <?=link_to('/evaluation', $title = 'ยกเลิก', ['class' => 'btn btn-warning'], $secure = null);?>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
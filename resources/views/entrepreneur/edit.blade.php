@extends('master.master') 
@section('main')
    @include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'แก้ไขผู้ประกอบการ'])
<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">แก้ไขผู้ประกอบการ</h4>
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
            @endif {!! Form::open(['url' => "/entrepreneur/".$data[0]->s_id,'class' => 'form-auth-small', 'method' => 'PUT' ]) !!}
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อร้านค้า</label>
                    <input type="text" class="form-control" name="s_name" value="{{ $data[0]->s_name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อผู้ประกอบการ</label>
                    <input type="text" class="form-control" name="s_onwer" value="{{ $data[0]->s_onwer }}"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">เบอร์โทร</label>
                    <input type="text" class="form-control" name="s_phone" value="{{ $data[0]->s_phone }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">จังหวัด</label>
                    <select class="form-control" name="province_id" size="1">
                        @foreach($province as $k => $v)
                            <option value="{{ $v->province_id }}" {{ ($v->province_id == $data[0]->province_id )? "selected" : "" }}  > {{ $v->province_name}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">Facebook</label>
                    <input type="url" class="form-control" id="" name="fb_id" value="{{ $data[0]->fb_id }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">Line ID</label>
                    <input type="text" class="form-control" id="" name="s_line" value="{{ $data[0]->s_line }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">Instagram</label>
                    <input type="text" class="form-control" id="" name="s_ig" value="{{ $data[0]->s_ig }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ที่อยู่</label>
                    <textarea class="form-control" id="" name="s_addr" style="resize:none" rows="4">{{ $data[0]->s_addr }}</textarea>
                </div>
            </div>
            <div class="form-group text-center mt-3">
                <button type="submit" class="btn btn-success">บันทึก</button>
                <?=link_to('/entrepreneur', $title = 'ยกเลิก', ['class' => 'btn btn-warning'], $secure = null);?>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
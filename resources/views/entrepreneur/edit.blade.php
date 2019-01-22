@extends('master.master') 
@section('main')
    @include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'แก้ไขผู้ดูแลระบบ'])
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
            @endif 
            {!! Form::open(['url' => "/entrepreneur/".$data[0]->s_id,'class' => 'form-auth-small', 'method' => 'PUT' ]) !!}
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="">Facebook url</label>
                    <input type="url" class="form-control" id="" name="fb_id" placeholder="Facebook url" value="{{ $data[0]->fb_id }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อร้านค้า</label>
                    <input type="text" class="form-control" id="" name="s_name" placeholder="ชื่อร้านค้า" value="{{ $data[0]->s_name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อผู้ประกอบการ</label>
                    <input type="text" class="form-control" id="" name="s_onwer" placeholder="ชื่อผู้ประกอบการ" value="{{ $data[0]->s_onwer }}"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">เบอร์โทร</label>
                    <input type="text" class="form-control" id="" name="s_phone" placeholder="เบอร์โทร" value="{{ $data[0]->s_phone }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ที่อยู่</label>
                    <textarea class="form-control" id="" name="s_addr" placeholder="ที่อยู่" style="resize:none" rows="4">{{ $data[0]->s_addr }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">จังหวัด</label>
                    <select class="form-control" name="province_id" size="1">
                        @foreach($province as $k => $v)
                            <option value="{{ $v->province_id }}" {{ ($v->province_id == $data[0]->province_id )? "selected" : "" }}  > {{ $v->province_name}} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">บันทึก</button>
                <?=link_to('/entrepreneur', $title = 'ยกเลิก', ['class' => 'btn btn-warning'], $secure = null);?>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
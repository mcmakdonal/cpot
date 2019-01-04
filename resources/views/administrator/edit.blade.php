@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'แก้ไขผู้ดูแลระบบ'])
<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">แก้ไขผู้ดูแลระบบ</h4>
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
            @endif {!! Form::open(['url' => "/administrator/".$data[0]->ad_id,'class' => 'form-auth-small', 'method' => 'PUT','files'
            => false]) !!}
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อ</label>
                    <input type="text" class="form-control" id="" name="ad_firstname" placeholder="First name" value="{{ $data[0]->ad_firstname }}"
                        required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">นามสกุล</label>
                    <input type="text" class="form-control" id="" name="ad_lastname" placeholder="Last name" value="{{ $data[0]->ad_lastname }}"
                        required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อผู้ใช้</label>
                    <input type="text" class="form-control" id="" name="ad_username" placeholder="Username" value="{{ $data[0]->ad_username }}"
                        readonly="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">รหัสผ่าน</label>
                    <input type="password" class="form-control" id="" name="ad_password" placeholder="Password" value="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ยืนยันรหัสผ่าน</label>
                    <input type="password" class="form-control" id="" name="conf_password" placeholder="Confirm Password" value="">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">บันทึก</button>
                <?=link_to('/administrator', $title = 'ยกเลิก', ['class' => 'btn btn-warning'], $secure = null);?>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
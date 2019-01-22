@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'เพิ่มผู้ดูแลระบบ'])
<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">เพิ่มผู้ดูแลระบบ</h4>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif 
            {!! Form::open(['url' => '/administrator','class' => 'form-auth-small', 'method' => 'POST','files' => false]) !!}
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อ</label>
                    <input type="text" class="form-control" id="" name="ad_firstname" placeholder="First name" value="" required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">นามสกลุ</label>
                    <input type="text" class="form-control" id="" name="ad_lastname" placeholder="Last name" value="" required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อผู้ใช้</label>
                    <input type="text" class="form-control" id="" name="ad_username" placeholder="Username" value="" required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">รหัสผ่าน</label>
                    <input type="password" class="form-control" id="" name="ad_password" placeholder="Password" value="" required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ยืนยันรหัสผ่าน</label>
                    <input type="password" class="form-control" id="" name="conf_password" placeholder="Confirm Password" value="" required="">
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
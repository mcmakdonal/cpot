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
                    <input type="text" class="form-control" name="ad_firstname" value="" required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">นามสกลุ</label>
                    <input type="text" class="form-control" name="ad_lastname" value="" required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อผู้ใช้</label>
                    <input type="text" class="form-control" name="ad_username" value="" required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">รหัสผ่าน</label>
                    <input type="password" class="form-control" name="ad_password" value="" required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ยืนยันรหัสผ่าน</label>
                    <input type="password" class="form-control" name="conf_password" value="" required="">
                </div>
            </div>
            <div class="form-group text-center mt-3">
                <button type="submit" class="btn btn-success">บันทึก</button>
                <?=link_to('/administrator', $title = 'ยกเลิก', ['class' => 'btn btn-warning'], $secure = null);?>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
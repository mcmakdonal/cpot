@extends('master.master') 
@section('main')
    @include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'แก้ไขโปรไฟล์'])
<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">แก้ไขโปรไฟล์</h4>
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
            @endif {!! Form::open(['url' => "/edit-profile",'class' => 'form-auth-small', 'method' => 'POST','files'
            => false]) !!}
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อ</label>
                    <input type="text" class="form-control" name="ad_firstname" value="{{ $data[0]->ad_firstname }}" required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">นามสกุล</label>
                    <input type="text" class="form-control" name="ad_lastname" value="{{ $data[0]->ad_lastname }}" required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อผู้ใช้</label>
                    <input type="text" class="form-control" name="ad_username" value="{{ $data[0]->ad_username }}" readonly="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for>อีเมล</label>
                    <input type="email" class="form-control" name="ad_email" value="{{ $data[0]->ad_email }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label for>เบอร์ติดต่อ</label>
                    <input type="text" class="form-control numberinput" name="ad_phone" value="{{ $data[0]->ad_phone }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for>หน่วยงาน</label>
                    <input type="text" class="form-control" name="ad_ogz" value="{{ $data[0]->ad_ogz }}" required>
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
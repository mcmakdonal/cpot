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
            @endif {!! Form::open(['url' => '/administrator','class' => 'form-auth-small', 'method' => 'POST','files' => false]) !!}
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for>ชื่อ</label>
                    <input type="text" class="form-control" name="ad_firstname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for>นามสกุล</label>
                    <input type="text" class="form-control" name="ad_lastname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for>ชื่อผู้ใช้</label>
                    <input type="text" class="form-control" name="ad_username" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for>อีเมล</label>
                    <input type="email" class="form-control" name="ad_email" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for>รหัสผ่าน</label>
                    <input type="password" class="form-control" name="ad_password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for>ยืนยันรหัสผ่าน</label>
                    <input type="password" class="form-control" name="conf_password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for>เบอร์ติดต่อ</label>
                    <input type="text" class="form-control numberinput" name="ad_phone" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for>หน่วยงาน</label>
                    <input type="text" class="form-control" name="ad_ogz" required>
                </div>
                {{-- <div class="col-md-6 mb-3">
                    <label for="">ระดับ</label>
                    <select class="form-control" name="ad_permission" size="1" required>
                        <option value="S">Super Administrator</option>
                        <option value="A" selected>Administrator</option>
                    </select>
                </div> --}}
                <div class="col-md-6 mb-3">
                    <label for="">สิทธิ์การเข้าถึง</label>
                    <select class="form-control role-multiple" name="per_id" size="1">
                        @foreach ($per as $key => $item)
                            <option value="{{$item->per_id}}"> {{$item->per_name}} </option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="col-md-12 mb-3">
                    <label for="">สิทธิ์การเข้าถึง</label><br /> 
                    @foreach ($role as $key => $item)
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" value="{{$item['id']}}" class="custom-control-input" name="ad_role[]" id="customCheck{{$item['id']}}" value="{{$item['id']}}">
                            <label class="custom-control-label" for="customCheck{{$item['id']}}"> {{$item['name']}} </label>
                        </div>
                    @endforeach
                </div> --}}
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
 
@section('script') $(document).ready(function() { $(".role-multiple").select2({ placeholder: 'กำหนดสิทธิ์การเข้าถึง'
}); });
@endsection
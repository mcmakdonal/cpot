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
                    <label for="">รหัสผ่าน</label>
                    <input type="password" class="form-control" name="ad_password" value="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ยืนยันรหัสผ่าน</label>
                    <input type="password" class="form-control" name="conf_password" value="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for>เบอร์ติดต่อ</label>
                    <input type="text" class="form-control numberinput" name="ad_phone" value="{{ $data[0]->ad_phone }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for>หน่วยงาน</label>
                    <input type="text" class="form-control" name="ad_ogz" value="{{ $data[0]->ad_ogz }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ระดับ</label>
                    <select class="form-control" name="per_id" size="1" required>
                        @foreach ($per as $key => $item)
                            <option value="{{$item->per_id}}" {{ ($item->per_id == $data[0]->per_id )? "selected" : "" }} > {{$item->per_name}} </option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-md-6 mb-3">
                    <label for="">สิทธิ์การเข้าถึง</label>
                    <select class="form-control role-multiple" name="ad_role[]" size="1" multiple="multiple">
                        @php
                            $have = json_decode($data[0]->ad_role);
                        @endphp
                        @foreach ($role as $key => $item)
                        @if(count($have) > 0)
                            <option value="{{$item['id']}}" @if(in_array($item['id'],$have)) selected="selected" @else @endif > {{$item['name']}} </option>
                        @else
                            <option value="{{$item['id']}}"> {{$item['name']}} </option>
                        @endif
                        @endforeach
                    </select>
                </div> --}}

                {{-- <div class="col-md-12 mb-3">
                    <label for="">สิทธิ์การเข้าถึง</label><br /> 
                    @php
                        $have = json_decode($data[0]->ad_role);
                    @endphp
                    @foreach ($role as $key => $item)
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" @if(in_array($item['id'],$have)) checked="checked" @else @endif value="{{$item['id']}}" class="custom-control-input" name="ad_role[]" id="customCheck{{$item['id']}}" value="{{$item['id']}}">
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
@section('script')
    $(document).ready(function() {
        $(".role-multiple").select2({
            placeholder: 'กำหนดสิทธิ์การเข้าถึง'
        });
    });
@endsection
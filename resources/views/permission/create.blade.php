@extends('master.master') 
@section('main')
    @include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'เพิ่มสิทธิ์การใช้งาน'])
<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">เพิ่มสิทธิ์การใช้งาน</h4>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif {!! Form::open(['url' => '/permission','class' => 'form-auth-small', 'method' => 'POST','files' => false]) !!}
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for>ชื่อสิทธิ์</label>
                    <input type="text" class="form-control" name="per_name" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="">สิทธิ์การเข้าถึง</label><br /> 
                    @foreach ($role as $key => $item)
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" value="{{$item['id']}}" class="custom-control-input" name="per_role[]" id="customCheck{{$item['id']}}" value="{{$item['id']}}">
                            <label class="custom-control-label" for="customCheck{{$item['id']}}"> {{$item['name']}} </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form-group text-center mt-3">
                <button type="submit" class="btn btn-success">บันทึก</button>
                <?=link_to('/permission', $title = 'ยกเลิก', ['class' => 'btn btn-warning'], $secure = null);?>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
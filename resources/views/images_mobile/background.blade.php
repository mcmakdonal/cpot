@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดการแบล็คกราว'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">การจัดการแบล็คกราว</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif 
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        {!! Form::open(['url' => "/image-store",'class' => 'form-auth-small', 'method' => 'POST','files' => true]) !!}
                        <div class="form-row align-items-center">
                            <div class="col-md-12">
                                <span class="text-info">ขนาดที่แนะนำ : 420px x 820px </span>
                            </div>
                            <div class="col-md-11 mb-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">เลือกรูปภาพ</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="file" accept="image/*" id="">
                                        <label class="custom-file-label" for="">เลือก</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 mb-3">
                                <input type="hidden" name="type" value="background">
                                <button type="submit" class="btn btn-success mb-3">บันทึก</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="data-tables">
                            <table id="" class="text-center dataTable">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>#</th>
                                        <th>รูป</th>
                                        <th>สถานะ</th>
                                        <th>ลบ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td><img src="{{ url($v->path) }}" class="img-responsive" style="width: 120;"></td>
                                        <td>
                                            @if($v->active == "A")
                                                <button class="btn btn-success" type="button" onclick='func_unactive("/image-unactive",{{$v->id}},"background")'>กำลังใช้งาน</button>
                                            @else
                                                <button class="btn btn-info" type="button" onclick='func_active("/image-active",{{$v->id}})'>เปิดใช้งาน</button>
                                            @endif
                                        </td>
                                        <td><button class="btn btn-danger" type="button" onclick='destroy("image-destroy",{{$v->id}})'>ลบ</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
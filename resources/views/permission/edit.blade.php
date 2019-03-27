@extends('master.master') 
@section('main')
    @include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'แก้ไขสิทธิ์การใช้งาน'])
<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">แก้ไขสิทธิ์การใช้งาน</h4>
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
            @endif {!! Form::open(['url' => "/permission/".$data[0]->per_id,'class' => 'form-auth-small', 'method' => 'PUT','files'
            => false]) !!}
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อสิทธิ์</label>
                    <input type="text" class="form-control" value="{{ $data[0]->per_name }}" required="" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">สิทธิ์การเข้าถึง</label><br /> 
                    @php
                        $have = json_decode($data[0]->per_role);
                    @endphp
                    {{-- @foreach ($role as $key => $item)
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" @if(in_array($item['id'],$have)) checked="checked" @else @endif value="{{$item['id']}}" class="custom-control-input" name="per_role[]" id="customCheck{{$item['id']}}" value="{{$item['id']}}">
                            <label class="custom-control-label" for="customCheck{{$item['id']}}"> {{$item['name']}} </label>
                        </div>
                    @endforeach --}}

                    <div class="data-tables">
                        <table id="" class="text-center table">
                            {{-- <thead class="bg-light text-capitalize">
                                <tr>
                                    <th>สิทธิ์การเข้าถึง</th>
                                </tr>
                            </thead> --}}
                            <tbody>
                                @foreach ($role as $key => $item)
                                <tr>
                                    <td class="text-left">
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" @if(in_array($item['id'],$have)) checked="checked" @else @endif value="{{$item['id']}}" class="custom-control-input" name="per_role[]" id="customCheck{{$item['id']}}" value="{{$item['id']}}">
                                            <label class="custom-control-label" for="customCheck{{$item['id']}}"> {{$item['name']}} </label>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

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
@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดการ YouTube และ สินค้า'])
<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">การจัดการ YouTube และ สินค้า</h4>
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
            @endif {!! Form::open(['url' => "/product-match/".$data[0]->pd_id,'class' => 'form-auth-small', 'method' => 'POST','files'
            => false]) !!}
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อสินค้า : </label>
                    <input type="text" class="form-control" name="" value="{{ $data[0]->pd_name }}" readonly="">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">ราคา : </label>
                    <input type="text" class="form-control" name="" value="{{ $data[0]->pd_price }}" readonly="">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">ราคาพิเศษ : </label>
                    <input type="text" class="form-control" name="" value="{{ $data[0]->pd_sprice }}" readonly="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">แท็ก : </label>
                    <input type="text" class="form-control" name="pd_tag" value="{{ $data[0]->pd_tag }}" readonly="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">รายละเอียด : </label>
                    <textarea class="form-control" readonly="" rows="5" style="resize: vertical">{{ $data[0]->pd_description }}</textarea>
                </div>
            </div>

            <div class="row mt-1 mb-1">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <hr />
                    <h3 class="txt-youtube"> ผลลัพท์ที่แสดงทั้งหมดคือ <span>_Show</span> : </h3>
                    {{-- <h3 class="txt-youtube"> ผลลัพท์ที่แสดงทั้งหมดคือ _Show จากทั้งหมด _Total : </h3> --}}
                    <hr />
                </div>
            </div>

            <div class="form-row mt-10" id="html-block">
                @foreach($select as $k => $v)
                    @php
                        $class = uniqid();
                        $json = [
                            'my_title' => $v->my_title,
                            'my_href' => $v->my_href,
                            'my_image' => $v->my_image,
                            'my_desc' => $v->my_desc
                        ]
                    @endphp
                    <div class="col-md-10 col-xs-10 mb-3 cyoutube {{ $class }}">
                        <label class="sr-only" for=""></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">{{$k + 1}}.</div>
                            </div>
                            <input type="text" class="form-control" type="text" value="{{ $v->my_title }}" readonly="">
                        </div>
                    </div>
                    <div class="col-md-2 col-xs-2 mb-3 {{ $class }}">
                        <button type="button" data="{{ $class }}" onclick="remove_block(this)" class="btn btn-danger"> ลบ <span class="ti-trash"></span></button>
                        <textarea class="form-control d-none" name="youtube[]" readonly="">{{ json_encode($json,JSON_UNESCAPED_UNICODE) }}</textarea>
                    </div>
                @endforeach
            </div>

            <div class="row youtube-result">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <hr />
                    <h3> จับคู่ Youtube </h3>
                    <h4 class="mt-3"> คีย์ค้นหา : <span class="tag-search">{{ $data[0]->pd_tag }}</span> </h4>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="text-center">
                        <button type="button" class="btn btn-info prev" onclick="generate_youtube('prev');"> <span class="ti-angle-left"></span> หน้าก่อนหน้า </button>
                        <button type="button" class="btn btn-primary reload" onclick="generate_youtube('reload');"> โหลดใหม่ <span class="ti-reload"></span> </button>
                        <button type="button" class="btn btn-info next" onclick="generate_youtube('next');"> หน้าถัดไป <span class="ti-angle-right"></span> </button>
                    </div>
                </div>
            </div>

            <div class="form-group text-center mt-3">
                <button type="submit" class="btn btn-success">บันทึก</button>
                <?=link_to('/product-match', $title = 'ยกเลิก', ['class' => 'btn btn-warning'], $secure = null);?>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
@section('script')
$(document).ready(function() {
    generate_youtube();
});
@endsection
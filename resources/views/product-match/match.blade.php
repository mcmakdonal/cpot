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
                    <input type="text" class="form-control" id="" name="" placeholder="" value="{{ $data[0]->pd_name }}" readonly="">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">ราคา : </label>
                    <input type="text" class="form-control" id="" name="" placeholder="" value="{{ $data[0]->pd_price }}" readonly="">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">ราคาพิเศษ : </label>
                    <input type="text" class="form-control" id="" name="" placeholder="" value="{{ $data[0]->pd_sprice }}" readonly="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">แท็ก : </label>
                    <input type="text" class="form-control" id="" name="pd_tag" placeholder="" value="{{ $data[0]->pd_tag }}" readonly="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">รายละเอียด : </label>
                    <textarea class="form-control" readonly="" rows="5" style="resize: vertical">{{ $data[0]->pd_description }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <hr />
                    <h3> จับคู่ Youtube </h3>
                    <h5> คีย์ค้นหา : {{ $data[0]->pd_tag }}
                </div>

                @foreach($youtube as $k => $v)
                <div class="col-lg-4 col-md-4 mt-3 d-flex align-items-stretch">
                    <div class="card card-bordered">
                        <a href="https://www.youtube.com/watch?v={{ $v->id->videoId }}" target="_blank">
                            <img class="card-img-top img-fluid" src="{{ $v->snippet->thumbnails->medium->url }}" alt="image">
                        </a>
                        <div class="card-body">
                            <h5 class="title">{{ $v->snippet->title }}</h5>
                            <p class="card-text">{{ $v->snippet->description }}</p>
                             @php 
                             $json = [ 'my_title' => $v->snippet->title, 'my_href' => $v->id->videoId,'my_image' => $v->snippet->thumbnails->medium->url,'my_desc' => $v->snippet->description ]; 
                            @endphp
                            <button type="button" onclick="select_youtube(this)" class="btn btn-primary" data="{{ json_encode($json,JSON_UNESCAPED_UNICODE) }}">เลือก</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="row mt-1 mb-1">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <hr />
                    <h3> ผลลัพท์ที่แสดงทั้งหมดคือ {{ count($youtube) }} : </h3>
                    <hr />
                </div>
            </div>

            <div class="form-row mt-10" id="html-block">
                <span>สามารถเลือกได้สูงสุด</span>
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
                    <div class="col-md-11 mb-3 cyoutube {{ $class }}">
                        <label class="sr-only" for=""></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">{{$k + 1}}.</div>
                            </div>
                            <input type="text" class="form-control" type="text" value="{{ $v->my_title }}" readonly="">
                        </div>
                    </div>
                    <div class="col-md-1 mb-3 {{ $class }}">
                        <button type="button" data="{{ $class }}" onclick="remove_block(this)" class="btn btn-wanring"><span class="ti-trash"></span></button>
                        <textarea class="form-control d-none" name="youtube[]" readonly="">{{ json_encode($json,JSON_UNESCAPED_UNICODE) }}</textarea>
                    </div>
                @endforeach
            </div>

            <div class="form-group">
                <hr />
                <button type="submit" class="btn btn-success">บันทึก</button>
                <?=link_to('/product-match', $title = 'ยกเลิก', ['class' => 'btn btn-warning'], $secure = null);?>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
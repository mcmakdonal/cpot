@extends('master.master') 
@section('main')
    @include('master.breadcrumb')

<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">Matching Product & Youtube</h4>
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
                    <label for="">Product name : </label>
                    <input type="text" class="form-control" id="" name="" placeholder="" value="{{ $data[0]->pd_name }}" readonly="">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">Price : </label>
                    <input type="text" class="form-control" id="" name="" placeholder="" value="{{ $data[0]->pd_price }}" readonly="">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">Spacial Price : </label>
                    <input type="text" class="form-control" id="" name="" placeholder="" value="{{ $data[0]->pd_sprice }}" readonly="">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">Tag : </label>
                    <input type="text" class="form-control" id="" name="pd_tag" placeholder="" value="{{ $data[0]->pd_tag }}" readonly="">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">Image : </label>
                    <br />
                    <img src="{{ url($data[0]->pd_image) }}" class="img-responsive">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">Description : </label>
                    <textarea class="form-control" readonly="">{{ $data[0]->pd_description }}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <hr />
                    <h3> Youtube Matching </h3>
                    <h5> Key Seach : {{ $data[0]->pd_tag }}
                </div>

                @foreach($youtube->items as $k => $v)
                <div class="col-lg-3 col-md-3 mt-5">
                    <div class="card card-bordered">
                        <img class="card-img-top img-fluid" src="{{ $v->snippet->thumbnails->medium->url }}" alt="image">
                        <div class="card-body">
                            <h5 class="title">{{ $v->snippet->title }}</h5>
                            {{--
                            <p class="card-text">{{ $v->snippet->description }}</p> --}}
                             @php 
                             $json = [ 'my_title' => $v->snippet->title, 'my_href' => $v->id->videoId,'my_image' => $v->snippet->thumbnails->medium->url ]; 
                            @endphp
                            <button type="button" onclick="select_youtube(this)" class="btn btn-primary" data="{{ json_encode($json,JSON_UNESCAPED_UNICODE) }}">Select</button>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="col-lg-12 col-md-12 col-xs-12">
                    <hr />
                    <h3> Matching (limit 4) </h3>
                    <hr />
                    <div id="html-block">
                        @foreach($select as $k => $v)
                            @php
                                $uniq = uniqid();
                                $json = [
                                    'my_title' => $v->my_title,
                                    'my_href' => $v->my_href,
                                    'my_image' => $v->my_image
                                ]
                            @endphp
                            <div class="form-group cyoutube" id="{{ $uniq }}">
                                <div class="row">
                                    <h4 class="col-1 m-auto">No.{{ $k + 1 }} </h4>
                                    <input class="form-control col-9" type="text" value="{{ $v->my_title }}" readonly="">
                                    <button onclick="youtube_remove(this)" data="{{ $uniq }}" class="col-2 btn btn-danger">Delete</button>
                                    <textarea class="form-control d-none" name="youtube[]" readonly="">{{ json_encode($json,JSON_UNESCAPED_UNICODE) }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="form-group">
                <hr />
                <button type="submit" class="btn btn-success">Update</button>
                <?=link_to('/product-match', $title = 'Cancle', ['class' => 'btn btn-warning'], $secure = null);?>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
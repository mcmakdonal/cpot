@extends('master.master') 
@section('main')
    @include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'แก้ไขผู้ดูแลระบบ'])
<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">แก้ไขผู้ประกอบการ</h4>
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
            @endif {!! Form::open(['url' => "/material/".$data[0]->m_id,'class' => 'form-auth-small', 'method' => 'PUT' ]) !!}
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อสินค้า</label>
                    <input type="text" class="form-control" id="" name="m_name" placeholder="ชื่อสินค้า" value="{{ $data[0]->m_name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ราคา</label>
                    <input type="number" class="form-control" min="0" id="" name="m_price" placeholder="ราคา" value="{{ $data[0]->m_price }}"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">จังหวัด</label>
                    <select class="form-control" id="province_id" name="province_id" size="1" onchange="search_district();" data-id="{{ $data[0]->province_id }}">
                        @foreach($province as $k => $v)
                            <option value="{{ $v->province_id }}" {{ ($v->province_id == $data[0]->province_id )? "selected" : "" }}  > {{ $v->province_name}} </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="" class="control-label">อำเภอ : </label>
                        <select class="form-control" id="district_id" name="district_id" onchange="search_subdistrict();" size="1" data-id="{{ $data[0]->district_id }}">
                        <select>
                    </div>
                </div>
    
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="" class="control-label">ตำบล : </label>
                        <select class="form-control" id="sub_district_id" name="sub_district_id" size="1" data-id="{{ $data[0]->sub_district_id }}">
                        <select>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="" class="control-label">ผู้ประกอบการ : </label>
                            <select class="form-control select" id="s_id" name="s_id" size="1">
                                @foreach($store as $k => $v)
                                    <option value="{{ $v->s_id }}" {{ ($v->s_id == $data[0]->s_id )? "selected" : "" }}  > {{ $v->s_name}} </option>
                                @endforeach
                            <select>
                        </div>
                    </div>

            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">บันทึก</button>
                <?=link_to('/material', $title = 'ยกเลิก', ['class' => 'btn btn-warning'], $secure = null);?>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('script')
    $(document).ready(function() {
        search_district(true);
    });
@endsection
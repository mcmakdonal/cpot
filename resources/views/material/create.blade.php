@extends('master.master') 
@section('main')
    @include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'เพิ่มทรัพยากร'])
<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">เพิ่มทรัพยากร</h4>
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
            @endif {!! Form::open(['url' => "/material",'class' => 'form-auth-small', 'method' => 'POST' ]) !!}
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="">ชื่อทรัพยากรหรือวัตถุดิบ </label>
                    <input type="text" class="form-control" name="m_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ราคา</label>
                    <input type="number" class="form-control" min="0" name="m_price" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">หน่วยสินค้า</label>
                    <input type="text" class="form-control" name="m_unit" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">แหล่งที่ซื้อทรัพยากร</label>
                    <input type="text" class="form-control" name="sm_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">เบอร์ติดต่อ</label>
                    <input type="text" class="form-control numberinput" name="m_phone">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">Facebook</label>
                    <input type="text" class="form-control" name="m_facebook">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">Line</label>
                    <input type="text" class="form-control" name="m_line">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">Instagram</label>
                    <input type="text" class="form-control" name="m_instagram">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">จังหวัด</label>
                    <select class="form-control" id="province_id" name="province_id" size="1" onchange="search_district();" required>
                        <option value="">กรุณาเลือกจังหวัด</option>
                        @foreach($province as $k => $v)
                            <option value="{{ $v->province_id }}"> {{ $v->province_name}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="" class="control-label">อำเภอ : </label>
                        <select class="form-control" id="district_id" name="district_id" onchange="search_subdistrict();" size="1" required>
                        <select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="" class="control-label">ตำบล : </label>
                        <select class="form-control" id="sub_district_id" name="sub_district_id" size="1" required>
                        <select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ละติจูด</label>
                    <input type="text" class="form-control" name="m_lat">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="">ลองติจูด</label>
                    <input type="text" class="form-control" name="m_long">
                </div>
                {{-- <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="" class="control-label">แหล่งที่ซื้อทรัพยากร : </label>
                        <select class="form-control select" id="s_id" name="s_id" size="1">
                            @foreach($store as $k => $v)
                                <option value="{{ $v->s_id }}"> {{ $v->s_name}} </option>
                            @endforeach
                        <select>
                    </div>
                </div> --}}
            </div>
            <div class="form-group text-center mt-3">
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
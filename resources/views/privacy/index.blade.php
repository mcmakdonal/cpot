@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดการโฆษณา'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">การจัดการข้อกำหนด</h4>
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
                        {!! Form::open(['url' => "/privacy",'class' => 'form-auth-small', 'method' => 'POST','files' => false]) !!}
                        <div class="form-row align-items-center">
                            <div class="col-md-10 my-1">
                                <label class="" for="p_choice">เพิ่มข้อกำหนด</label>
                                <textarea name="p_choice" class="form-control" rows="5" style="resize: none;"></textarea>
                            </div>
                            <div class="col-md-2 my-1">
                                <button type="submit" class="btn btn-primary">บักทึก</button>
                            </div>
                            <div class="col-md-12">
                                <hr />
                            </div>
                        </div>
                        {!! Form::close() !!}
                        <div class="data-tables">
                            <table id="dataTable">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th>ตัวเลือก</th>
                                        <th style="width: 10%">ลบ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td class="text-left" style="word-break: break-all;">{{ $v->p_choice }}</td>
                                        <td>
                                            <button class="btn btn-danger" type="button" onclick='destroy("privacy",{{$v->p_id}})'>ลบ</button>
                                        </td>
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
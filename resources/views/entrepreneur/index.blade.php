@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดการผู้ดูแลระบบ'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">การจัดการผู้ประกอบการ</h4>
                    <?=link_to('/entrepreneur/create', $title = 'สร้าง' , ['class' => 'd-none btn btn-success mb-3 float-right'], $secure = null); ?>
                        <div class="data-tables">
                            <table id="" class="text-center dataTable">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>#</th>
                                        <th>ชื่อร้าน</th>
                                        <th>ชื่อผู้ประกอบการ</th>
                                        <th>เบอร์ติดต่อ</th>
                                        <th>แก้ไข</th>
                                        {{-- <th>ลบ</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $v->s_name }}</td>
                                        <td>{{ $v->s_onwer }}</td>
                                        <td>{{ $v->s_phone }}</td>
                                        <td>
                                            <?=link_to("/entrepreneur/$v->s_id/edit", $title = 'แก้ไข' , ['class' => 'btn btn-warning btn-xs'], $secure = null); ?>
                                        </td>
                                        {{-- <td><button class="btn btn-danger" type="button" onclick='destroy("administrator",{{$v->ad_id}})'>ลบ</button></td> --}}
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
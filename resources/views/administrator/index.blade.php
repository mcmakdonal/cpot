@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดการผู้ดูแลระบบ'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">การจัดการผู้ดูแลระบบ</h4>
                    <?=link_to('/administrator/create', $title = 'สร้าง' , ['class' => 'btn btn-success mb-3 float-right'], $secure = null); ?>
                        <div class="data-tables">
                            <table id="dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>#</th>
                                        <th>ชื่อ</th>
                                        <th>นามสกลุ</th>
                                        <th>ชื่อผู้ใช้</th>
                                        <th>แก้ไข</th>
                                        <th>ลบ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $v->ad_firstname }}</td>
                                        <td>{{ $v->ad_lastname }}</td>
                                        <td>{{ $v->ad_username }}</td>
                                        <td>
                                            <?=link_to("/administrator/$v->ad_id/edit", $title = 'แก้ไข' , ['class' => 'btn btn-warning mb-3'], $secure = null); ?>
                                        </td>
                                        <td><button class="btn btn-danger" type="button" onclick='destroy("administrator",{{$v->ad_id}})'>ลบ</button></td>
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
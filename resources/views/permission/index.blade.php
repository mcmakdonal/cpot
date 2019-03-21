@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดสิทธิ์การใช้งาน'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">การจัดสิทธิ์การใช้งาน</h4>
                    <?=link_to('/permission/create', $title = 'สร้าง' , ['class' => 'btn btn-success mb-3 float-right'], $secure = null); ?>
                        <div class="data-tables">
                            <table id="" class="text-center dataTable">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>#</th>
                                        <th>สิทธ์</th>
                                        <th>แก้ไข</th>
                                        <th>ลบ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $v->per_name }}</td>
                                        <td>
                                            <?=link_to("/permission/$v->per_id/edit", $title = 'แก้ไข' , ['class' => 'btn btn-warning mb-3'], $secure = null); ?>
                                        </td>
                                        <td><button class="btn btn-danger" type="button" onclick='destroy("permission",{{$v->per_id}})'>ลบ</button></td>
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
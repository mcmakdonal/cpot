@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดการทรัพยากร'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">การจัดการทรัพยากร</h4>
                    <?=link_to('/material/create', $title = 'สร้าง' , ['class' => 'btn btn-success mb-3 float-right'], $secure = null); ?>
                        <div class="data-tables">
                            <table id="" class="text-center dataTable">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th>#</th>
                                        <th>แหล่งที่ซื้อทรัพยากร</th>
                                        <th>ชื่อทรัพยากรหรือวัตถุดิบ</th>
                                        <th>ราคา/หน่วย</th>
                                        <th>แก้ไข</th>
                                        <th>ลบ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $v->sm_name }}</td>
                                        <td>{{ $v->m_name }}</td>
                                        <td>{{ $v->m_price }} / {{ $v->m_unit }}</td>
                                        <td>
                                            <?=link_to("/material/$v->m_id/edit", $title = 'แก้ไข' , ['class' => 'btn btn-warning btn-xs'], $secure = null); ?>
                                        </td>
                                        <td><button class="btn btn-danger" type="button" onclick='destroy("material",{{$v->m_id}})'>ลบ</button></td>
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
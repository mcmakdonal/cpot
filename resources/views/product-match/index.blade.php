@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดการ YouTube และ สินค้า'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">การจัดการ YouTube และ สินค้า</h4>
                    <div class="data-tables table-responsive">
                        <table id="" class="table">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th>รหัสสินค้า</th>
                                    <th>ชื่อสินค้า</th>
                                    <th>สถานะจับคู่</th>
                                    <th>จับคู่</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $k => $v)
                                <tr>
                                    <td>{{ $v->pd_id }}</td>
                                    <td>{{ $v->pd_name }}</td>
                                    <td>@php echo (count($v->youtube) > 0) ? '<a href="#" class="badge badge-success">จับคู่แล้ว</a>' : '<a href="#" class="badge badge-secondary">ยังไม่จับคู่</a>' @endphp</td>
                                    <td>
                                        <?=link_to("/product-match/$v->pd_id/matching", $title = 'จับคู่' , ['class' => 'btn btn-warning btn-xs'], $secure = null); ?>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
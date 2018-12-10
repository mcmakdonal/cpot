@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดการ YouTube และ สินค้า'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">การจัดการ YouTube และ สินค้า</h4>
                    <div class="data-tables">
                        <table id="dataTable" class="">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Image</th>
                                    <th>Match</th>
                                    <th>Match</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $k => $v)
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>{{ $v->pd_name }}</td>
                                    <td><img src="{{ url($v->pd_image) }}" class="img-responsive"></td>
                                    <td>@php echo (count($v->youtube) > 0) ? '<a href="#" class="badge badge-success">Match</a>' : '<a href="#" class="badge badge-secondary">Not Match</a>' @endphp</td>
                                    <td>
                                        <?=link_to("/product-match/$v->pd_id/matching", $title = 'Match' , ['class' => 'btn btn-warning mb-3'], $secure = null); ?>
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
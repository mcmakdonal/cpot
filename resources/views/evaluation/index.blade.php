@extends('master.master') 
@section('main')
@include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดการแบบประเมิน'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">การจัดการแบบประเมิน</h4>
                    <?=link_to('/evaluation/create', $title = 'Create' , ['class' => 'btn btn-success mb-3 float-right'], $secure = null); ?>
                        <div class="data-tables">
                            <table id="dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th>Topic</th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $v->et_topic }}</td>
                                        <td>
                                            @if($v->et_active == "A")
                                                <button class="btn btn-success" type="button" onclick='func_unactive("/evaluation/unactive",{{$v->et_id}})'>Unactive</button>
                                            @else
                                                <button class="btn btn-info" type="button" onclick='func_active("/evaluation/active",{{$v->et_id}})'>Active</button>
                                            @endif
                                        </td>
                                        <td>
                                            <?=link_to("/evaluation/$v->et_id/edit", $title = 'Edit' , ['class' => 'btn btn-warning'], $secure = null); ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger" type="button" onclick='destroy("evaluation",{{$v->et_id}})'>Delete</button>
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
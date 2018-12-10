@extends('master.master') 
@section('main')
@section('title', 'รายงาน Tag ที่นิยม' )
    @include('master.breadcrumb', ['mtitle' => 'รายงาน','stitle' => 'รายงาน Tag ที่นิยม'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">รายงาน Tag ที่นิยม</h4>
                        <div class="data-tables">
                            <table id="report_dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th>ชื่อ Tag</th>
                                        <th>จำนวน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr class="text-left">
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $v->pd_tag }}</td>
                                        <td>{{ $v->counter }}</td>
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
@extends('master.master') 
@section('main')
@section('title', 'รายงานการแบ่งปันองค์ความรู้และทรัพยากรวัตถุดิบ' )
@include('master.breadcrumb', ['mtitle' => 'รายงาน','stitle' => 'รายงานการแบ่งปันองค์ความรู้และทรัพยากรวัตถุดิบ'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">รายงานการแบ่งปันองค์ความรู้และทรัพยากรวัตถุดิบ</h4>
                        <div class="data-tables">
                            <table id="report_dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th>ชื่อสินค้า / บทความ</th>
                                        <th>ประเภท</th>
                                        <th>จำนวนแบ่งปัน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr class="text-left">
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $v->title }}</td>
                                        <td>
                                            @if($v->type == "pd")
                                                สินค้า
                                            @else
                                                บทความ
                                            @endif
                                        </td>
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
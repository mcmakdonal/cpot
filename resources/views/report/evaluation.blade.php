@extends('master.master') 
@section('main')
@section('title', 'รายงานแบบประเมิน' )
@include('master.breadcrumb', ['mtitle' => 'รายงาน','stitle' => 'รายงานแบบประเมิน'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">รายงานแบบประเมิน</h4>
                        <div class="data-tables">
                            <table id="report_dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th>ชื่อแบบประเมิน</th>
                                        <th>สถานะ</th>
                                        <th>จำนวนผู้โหวต</th>
                                        <th>คะแนน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr class="text-left">
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $v->et_topic }}</td>
                                        <td>
                                            @if($v->et_active == "A")
                                                เปิดใช้
                                            @else
                                                ไม่ถูกเปิดใช้
                                            @endif
                                        </td>
                                        <td>
                                            {{ $v->totol_vote }}
                                        </td>
                                        <td>@foreach ($v->items as $key => $item)คำถาม : {{ $item->q_question }} | คะแนนรวมทั้งหมด : {{ $item->sum_point }}<br/>@endforeach</td>
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
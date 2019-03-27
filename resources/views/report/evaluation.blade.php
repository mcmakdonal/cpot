@extends('master.master') 
@section('main') 
@section('title', 'รายงานแบบประเมิน' )
    @include('master.breadcrumb', ['mtitle'
=> 'รายงาน','stitle' => 'รายงานแบบประเมิน'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">รายงานแบบประเมิน</h4>
                    <div class="data-tables">
                        <table id="eva_table" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 20%">ชื่อแบบประเมิน</th>
                                    <th style="width: 40%">คะแนน</th>
                                    <th style="width: 10%;display:none;">สถานะ</th>
                                    <th style="width: 10%">จำนวนโหวต</th>
                                </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody>
                                @foreach($data as $k => $v)
                                <tr class="text-center">
                                    <td>{{ $k + 1 }}</td>
                                    <td>{{ $v->et_topic }}</td>
                                    <td class="text-left">
                                        @if(count($v->items) == 0) ไม่มีการให้คะแนน @else @foreach ($v->items as $key => $item)คำถาม : {{ $item['q_question'] }} | คะแนนเฉลี่ยรวมทั้งหมด : {{ $item['result'] }} <br/> @endforeach @endif
                                    </td>
                                    <td style="display: none;">
                                        @if($v->et_active == "A") เปิดใช้ @else ไม่ถูกเปิดใช้ @endif
                                    </td>
                                    <td>
                                        {{ $v->user_vote }}
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
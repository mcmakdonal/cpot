@extends('master.master') 
@section('main')
@section('title', 'รายงานการเข้าใช้งาน แอพพลิเคชัน CPOT' )
@include('master.breadcrumb', ['mtitle' => 'รายงาน','stitle' => 'รายงานการเข้าใช้งาน แอพพลิเคชัน CPOT'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">รายงานการเข้าใช้งาน แอพพลิเคชัน CPOT</h4>
                        <div class="data-tables">
                            <table id="report_dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th>สมัครสมาชิกผ่าน</th>
                                        <th>ชื่อ นามสกุล</th>
                                        <th>เบอร์ติดต่อ</th>
                                        <th>อีเมล์</th>
                                        {{-- <th>จำนวน</th> --}}
                                        <th class="d-none">Facebook ID</th>
                                        <th class="d-none">เลขบัตร ปปช</th>
                                        <th class="d-none">ชื่อเจ้าของร้าน</th>
                                        <th class="d-none">ชื่อร้านค้า</th>
                                        <th class="d-none">ที่อยู่</th>
                                        <th class="d-none">กลุ่ม</th>
                                        <th class="d-none">รายละเอียด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k => $v)
                                    <tr class="text-left">
                                        <td>{{ $k + 1 }}</td>
                                        <td>
                                            @if($v->fb_id == "")
                                                ระบบสมัครสมาชิก
                                            @else
                                                Facebook
                                            @endif
                                        </td>
                                        <td>{{ $v->u_fullname }}</td>
                                        <td>{{ $v->u_phone }}</td>
                                        <td>{{ $v->u_email }}</td>
                                        {{-- <td>{{ $v->counter }}</td> --}}
                                        <td class="d-none">{{ $v->fb_id }}</td>
                                        <td class="d-none">{{ $v->u_identity }}</td>
                                        <td class="d-none">{{ $v->u_owner }}</td>
                                        <td class="d-none">{{ $v->u_store }}</td>
                                        <td class="d-none">{{ $v->u_addr }}</td>
                                        <td class="d-none">{{ $v->u_community }}</td>
                                        <td class="d-none">{{ $v->u_desc }}</td>
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
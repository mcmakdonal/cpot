@extends('master.master') 
@section('main')
    @include('master.breadcrumb', ['mtitle' => 'การจัดการ','stitle' => 'การจัดการ
YouTube และ สินค้า'])
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">การจัดการ YouTube และ สินค้า</h4>
                    <form>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <select class="form-control" size="1" id="mcat" onchange="reload();">
                                    <option value="" selected> - หมวดหมู่ - </option>
                                    @foreach($cat as $k => $v)
                                        <option value="{{ $v->mcat_id}}"> {{ $v->mcat_name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="data-tables table-responsive">
                        <table id="" class="table">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th style="width: 15%">รหัสสินค้า</th>
                                    <th>ชื่อสินค้า</th>
                                    <th>สถานะจับคู่</th>
                                    <th>จับคู่</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
    var tbl = "";
    $(document).ready(function() {
        console.log("aaa");
        tbl = $('table').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/product-process",
                "data": function ( d ) {
                    d.mcat_id = $('#mcat').val();
                }
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                { "data": "pd_id",className: "text-center", },
                { "data": "pd_name" },
                {
                    "mData": "youtube",
                    "mRender": function (data, type, row) {
                        if(data > 0){
                            return "<a href='#' class='badge badge-success'>จับคู่แล้ว</a>";
                        } else {
                            return "<a href='#' class='badge badge-secondary'>ยังไม่จับคู่</a>";
                        }
                    }
                },
                {
                    "mData": "pd_id",
                    "mRender": function (data, type, row) {
                        var url = "/product-match/" + data + "/matching";
                        return "<a class='btn btn-warning btn-xs' href='"+url+"'>จับคู่</a>";
                    },
                }
            ],
            "language": {
                "lengthMenu": "กำลังแสดง _MENU_ ข้อมูล ต่อหน้า",
                "zeroRecords": "ไม่พบข้อมูล",
                "info": "กำลังแสดง หน้า _PAGE_ จาก _PAGES_",
                "infoEmpty": "ไม่พบข้อมูล",
                "infoFiltered": "(กรองจาก _MAX_ ข้อมูลทั้งหมด)",
                "loadingRecords": "กำลังโหลด",
                "processing":     "กำลังประมวลผล",
                "search":         "ค้นหา :",
                "paginate": {
                    "previous": "ก่อนหน้า",
                    "next" : "ถัดไป"
                  }
            }
        });
    });

    function reload(){
        tbl.ajax.reload();
    }
@endsection
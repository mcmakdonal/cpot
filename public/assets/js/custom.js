var dataTable = '';
var report_dataTable = '';
var select = '';
$(document).ready(function () {
    $(".form-control").each(function () {
        var txt = $(this).parent().find("label").text();
        $(this).attr("placeholder", txt);
    });

    $("input").each(function () {
        $(this).attr("maxlength", 255);
        $(this).attr("autocomplete", "off");
    });

    $('input.numberinput').bind('keypress', function (e) {
        return !(e.which != 8 && e.which != 0 &&
            (e.which < 48 || e.which > 57) && e.which != 46);
    });

    // preload ajax
    $.LoadingOverlaySetup({
        image: "",
        fontawesome: "fa fa-circle-o-notch fa-spin",
        zIndex: 1000
    });

    setInterval(() => {
        change_word();
    }, 1000);

    $('select').select2();

    dataTable = $('.dataTable').DataTable({
        "language": {
            "lengthMenu": "กำลังแสดง _MENU_ ข้อมูล ต่อหน้า",
            "zeroRecords": "ไม่พบข้อมูล",
            "info": "กำลังแสดง หน้า _PAGE_ จากทั้งหมด _PAGES_ หน้ามีข้อมูล _TOTAL_ ",
            "infoEmpty": "ไม่พบข้อมูล",
            "infoFiltered": "(กรองจาก _MAX_ ข้อมูลทั้งหมด)",
            "loadingRecords": "กำลังโหลด",
            "processing": "กำลังประมวลผล",
            "search": "ค้นหา :",
            "paginate": {
                "previous": "ก่อนหน้า",
                "next": "ถัดไป"
            }
        },
        "initComplete": function (settings, json) {
            move_info();
        }
    });

    report_dataTable = $('#report_dataTable').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excel',
            text: 'Export to Excel'
        }],
        dom: 'Bfrtip',
        "language": {
            "lengthMenu": "กำลังแสดง _MENU_ ข้อมูล ต่อหน้า",
            "zeroRecords": "ไม่พบข้อมูล",
            "info": "กำลังแสดง หน้า _PAGE_ จากทั้งหมด _PAGES_ หน้ามีข้อมูล _TOTAL_ ",
            "infoEmpty": "ไม่พบข้อมูล",
            "infoFiltered": "(กรองจาก _MAX_ ข้อมูลทั้งหมด)",
            "loadingRecords": "กำลังโหลด",
            "processing": "กำลังประมวลผล",
            "search": "ค้นหา :",
            "paginate": {
                "previous": "ก่อนหน้า",
                "next": "ถัดไป"
            }
        },
        "initComplete": function (settings, json) {
            move_info();
        }
    });

    var buttonCommon = {
        exportOptions: {
            format: {
                body: function (data, column, row) {
                    data = data.replace(/<br\s*\/?>/ig, "\r\n");
                    data = data.replace(/<.*?>/g, "");
                    data = data.replace("&amp;", "&");
                    //   data = data.replace("&nbsp;", "");
                    //   data = data.replace("&nbsp;", "");
                    return data;
                }
            }
        }
    };
    $('#eva_table').DataTable({
        "lengthChange": false,
        "pageLength": 100,
        "orderClasses": false,
        "stripeClasses": [],
        dom: 'Bfrtip',
        "language": {
            "lengthMenu": "กำลังแสดง _MENU_ ข้อมูล ต่อหน้า",
            "zeroRecords": "ไม่พบข้อมูล",
            "info": "กำลังแสดง หน้า _PAGE_ จากทั้งหมด _PAGES_ หน้ามีข้อมูล _TOTAL_ ",
            "infoEmpty": "ไม่พบข้อมูล",
            "infoFiltered": "(กรองจาก _MAX_ ข้อมูลทั้งหมด)",
            "loadingRecords": "กำลังโหลด",
            "processing": "กำลังประมวลผล",
            "search": "ค้นหา :",
            "paginate": {
                "previous": "ก่อนหน้า",
                "next": "ถัดไป"
            }
        },
        "initComplete": function (settings, json) {
            move_info();
        },
        buttons: [
            $.extend(true, {}, buttonCommon, {
                extend: 'excel',
                text: 'Export to Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                },
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c[r^="A"]', sheet).attr('s', '50'); //<-- left aligned text
                    $('row c[r^="C"]', sheet).attr('s', '55'); //<-- wrapped text
                    // $('row:first c', sheet).attr('s', '32');
                    var col = $('col', sheet);
                    var c = 1;
                    col.each(function () {
                        if (c === 3) {
                            $(this).attr('width', 100);
                        }
                        c++;
                    });
                }
            })
        ]
    });

    $("ul.pagination li a").on('click change', function () {
        change_word();
    });

});

function destroy(src, id) {
    var r = confirm("ลบเนื้อหานี้ !");
    if (r == true) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                )
            },
            url: src + "/" + id,
            method: "delete",
            beforeSend() {
                $(".card").LoadingOverlay("show", {
                    fontawesome: "fa fa-circle-o-notch fa-spin",
                    zIndex: 10001
                });
            },
            success: function (result) {
                var obj = result;
                if (obj.status) {
                    window.location.reload();
                } else {
                    swal("Warning !", obj.message, "error");
                }
                $(".card").LoadingOverlay("hide", true);
            },
            error(xhr, status, error) {
                swal("Danger !", "Fail !", error + " Status : " + status, "error");
                $(".card").LoadingOverlay("hide", true);
            }
        });
    }
}

function generate_youtube(type = "") {
    var tag = $(".tag-search").text().trim();
    var pageToken = "";
    if (type === "next") {
        pageToken = $(".next").attr("token").trim();
    }
    if (type === "prev") {
        pageToken = $(".prev").attr("token").trim();
    }
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                "content"
            )
        },
        url: "/youtube-search",
        method: "post",
        data: {
            tag: tag,
            pageToken: pageToken
        },
        beforeSend() {
            $(".youtube-result").LoadingOverlay("show", {
                fontawesome: "fa fa-circle-o-notch fa-spin",
                zIndex: 10001
            });
        },
        success: function (result) {
            var obj = JSON.parse(result);
            var items = obj.items;
            $(".txt-youtube span").text(items.length);
            $(".prev").attr("token", obj.prevPageToken);
            $(".next").attr("token", obj.nextPageToken);

            var txt = [];
            $(".text-select").each(function () {
                // alert($(this).val());
                txt.push($(this).val());
            });

            console.log(txt);

            $(".card-youtube").remove();
            for (i = 0; i < items.length; i++) {
                var value = obj.items[i];
                var path = value.id.videoId;
                var img = value.snippet.thumbnails.high.url;
                var title = value.snippet.title;
                var date = new Date(value.snippet.publishedAt.substring(0, 10));
                var d = (date.getMonth() + 1) + '-' + date.getDate() + '-' + date.getFullYear();
                var desc = value.snippet.description;
                var json = {
                    my_title: title,
                    my_href: path,
                    my_image: img,
                    my_desc: desc
                };

                var btn_class = "";
                var btn_attr = "";
                var btn_txt = "";
                if (jQuery.inArray(title, txt) != -1) {
                    // console.log("is in array");
                    btn_class = "btn-disabled";
                    btn_attr = "disabled";
                    btn_txt = "เลือกแล้ว";
                } else {
                    // console.log("is NOT in array");
                    btn_class = "btn-primary";
                    btn_txt = "เลือก";
                }

                var str_txt = "<div class=\"card-youtube col-lg-4 col-md-4 mt-3 d-flex align-items-stretch\">\n" +
                    "                    <div class=\"card card-bordered\">\n" +
                    "                        <a href=\"https://www.youtube.com/watch?v=" + path + "\" target=\"_blank\">\n" +
                    "                            <img class=\"card-img-top img-fluid\" src=\"" + img + "\" alt=\"image\">\n" +
                    "                        </a>\n" +
                    "                        <div class=\"card-body flex-column d-flex\">\n" +
                    "                            <h4 class=\"title\">" + title + "</h4>\n" +
                    "                            <h6 class=\"title\">วันที่ : " + d + "</h6>\n" +
                    "                            <p class=\"card-text\">\"" + desc + "</p>\n" +
                    "                              <button type=\"button\" onclick=\"select_youtube(this)\" class=\"mt-auto btn " + btn_class + " btn-youtube\" " + btn_attr + " data=\'" + JSON.stringify(json) + "\'> " + btn_txt + " </button>\n" +
                    "                        </div>\n" +
                    "                    </div>\n" +
                    "                </div>";
                $(".youtube-result").append(str_txt);
            }
            $(".youtube-result").LoadingOverlay("hide", true);
        },
        error(xhr, status, error) {
            swal("Danger !", "Fail !", error + " Status : " + status, "error");
            $(".youtube-result").LoadingOverlay("hide", true);
        }
    });
}

function select_youtube(e) {
    var data = JSON.parse($(e).attr('data'));
    var cyoutube = $(".cyoutube").length + 1;
    var class_uq = uuidv4();
    var html = '';
    var title = data.my_title;
    html += '<div class="col-md-11 mb-3 cyoutube ' + class_uq + '"><label class="sr-only" for=""></label><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' + cyoutube + '.</div></div><input type="text" class="form-control text-select" readonly value="' + title.replace('"', '') + '"></div></div>';
    html += '<div class="col-md-1 mb-3 ' + class_uq + '"><button type="button" data="' + class_uq + '" onclick="remove_block(this)" class="btn btn-danger"> ลบ <span class="ti-trash"></span></button></div>';
    $("#q_target").append(html);


    html += '<textarea class="form-control d-none" name="youtube[]" readonly="">' + JSON.stringify(data) + '</textarea>';
    html += '</div>';
    html += '</div>';

    $("#html-block").append(html);
    $(e).attr('disabled', 'disabled');
    $(e).removeClass('btn-primary');
    $(e).addClass('btn-disabled');
    $(e).text("เลือกแล้ว");
}

function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16 | 0,
            v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

function remove_block(e) {
    var id = $(e).attr('data');
    $("." + id).remove();

    // if ($(".question").length > 0) {
    $('.input-group .input-group-prepend .input-group-text').each(function (index) {
        var $this = $(this);
        $this.text(index + 1 + ".");
    });
    // }
}

///////////////////////////

function add_question() {
    var question = $(".question").length + 1;
    var class_uq = uuidv4();
    var html = '<div class="col-md-11 mb-3 question ' + class_uq + '"><label class="sr-only" for="">Question</label><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' + question + '.</div></div><input type="text" class="form-control" name="question[]" placeholder="Question" required></div></div>';
    html += '<div class="col-md-1 mb-3 ' + class_uq + '"><button type="button" data="' + class_uq + '" onclick="remove_block(this)" class="btn btn-wanring"><span class="ti-trash"></span></button></div>';
    $("#q_target").append(html);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////

$("input[name^=file]").change(function () {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.readAsDataURL(this.files[0]);
        $('.custom-file-label').html(this.files[0].name);
    }
});

function func_active(src, id) {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                "content"
            )
        },
        url: src,
        method: "post",
        data: {
            id: id
        },
        beforeSend() {
            $(".card").LoadingOverlay("show", {
                fontawesome: "fa fa-circle-o-notch fa-spin",
                zIndex: 10001
            });
        },
        success: function (result) {
            var obj = result;
            if (obj.status) {
                window.location.reload();
            } else {
                swal("Warning !", obj.message, "error");
            }
            $(".card").LoadingOverlay("hide", true);
        },
        error(xhr, status, error) {
            swal("Danger !", "Fail !", error + " Status : " + status, "error");
            $(".card").LoadingOverlay("hide", true);
        }
    });
}

function func_unactive(src, id, type = "") {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                "content"
            )
        },
        url: src,
        method: "post",
        data: {
            id: id,
            type: type
        },
        beforeSend() {
            $(".card").LoadingOverlay("show", {
                fontawesome: "fa fa-circle-o-notch fa-spin",
                zIndex: 10001
            });
        },
        success: function (result) {
            var obj = result;
            if (obj.status) {
                window.location.reload();
            } else {
                swal("Warning !", obj.message, "error");
            }
            $(".card").LoadingOverlay("hide", true);
        },
        error(xhr, status, error) {
            swal("Danger !", "Fail !", error + " Status : " + status, "error");
            $(".card").LoadingOverlay("hide", true);
        }
    });
}

/////////////////////////////////////

function search_district(init = false) {
    var province_id = "";
    if (init) {
        province_id = $("#province_id").attr("data-id");
    } else {
        province_id = $("#province_id").val();
    }
    if (province_id == "") {
        return clear_addr();
    }
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        url: "/api/get-district/" + province_id,
        method: "GET",
        beforeSend() {
            $(".card").LoadingOverlay("show", {
                fontawesome: "fa fa-circle-o-notch fa-spin",
                zIndex: 10001
            });
        },
        success: function (result) {
            clear_addr();
            var check = false;
            for (var i = 0; i < result.data_object.length; i++) {
                if (result.data_object[i]["district_id"] == $("#district_id").attr("data-id")) {
                    check = true;
                }
                $("#district_id").append(
                    $("<option>", {
                        value: result.data_object[i]["district_id"],
                        text: result.data_object[i]["district_name"]
                    })
                );
            }
            if (check) {
                $("#district_id").val($("#district_id").attr("data-id"));
            }

            search_subdistrict();
            $(".card").LoadingOverlay("hide", true);
        },
        error(xhr, status, error) {
            swal("Danger !", "Fail !", error + " Status : " + status, "error");
            $(".card").LoadingOverlay("hide", true);
        }
    });
}

function search_subdistrict() {
    var district_id = $("#district_id").val();
    if (district_id == "") {
        return false;
    }

    $("#sub_district_id option").remove();
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        beforeSend() {
            $(".card").LoadingOverlay("show", {
                fontawesome: "fa fa-circle-o-notch fa-spin",
                zIndex: 10001
            });
        },
        url: "/api/get-sub-district/" + district_id,
        method: "GET",
        success: function (result) {
            var check = false;
            for (var i = 0; i < result.data_object.length; i++) {
                if (result.data_object[i]["sub_district_id"] == $("#sub_district_id").attr("data-id")) {
                    check = true;
                }
                $("#sub_district_id").append(
                    $("<option>", {
                        value: result.data_object[i]["sub_district_id"],
                        text: result.data_object[i]["sub_district_name"]
                    })
                );
            }
            if (check) {
                $("#sub_district_id").val($("#sub_district_id").attr("data-id"));
            }
            $(".card").LoadingOverlay("hide", true);
        },
        error(xhr, status, error) {
            swal("Danger !", "Fail !", error + " Status : " + status, "error");
            $(".card").LoadingOverlay("hide", true);
        }
    });
}

function clear_addr() {
    $("#district_id option").remove();
    $("#sub_district_id option").remove();
    $("#district_id").append(
        $("<option>", {
            value: "",
            text: "กรุณาเลือกอำเภอ"
        })
    );
    $("#sub_district_id").append(
        $("<option>", {
            value: "",
            text: "กรุณาเลือกตำบล"
        })
    );
    return false;
}

function move_info() {
    $(".dataTables_wrapper div.row div.col-md-6").removeClass("col-md-6").addClass("col-md-4");
    $(".dataTables_wrapper div.row div.col-md-4:first").after("<div class='col-sm-12 col-md-4 info_txt'><div style='padding: 5px;' class='dataTables_info'></div></div>");
    $("#DataTables_Table_0_info").hide();
    change_word();
}

function change_word() {
    var txt = $("#DataTables_Table_0_info").text();
    $(".info_txt .dataTables_info").text(txt);
}

$("input[name=ad_username].edit").on('keyup', function () {
    var data = {
        ad_username: $(this).val(),
        ad_id: $(this).attr("data-id")
    };
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                "content"
            )
        },
        url: "/check-username-exists",
        method: "post",
        data: data,
        beforeSend() {},
        success: function (result) {
            var obj = result;
            console.log(obj);
            if(obj.status){
                $(".username-check").show();
                $("input[name=check]").val("false");
            } else {
                $(".username-check").hide();
                $("input[name=check]").val("true");
            }
        },
        error(xhr, status, error) {
            swal("Danger !", "Fail !", error + " Status : " + status, "error");
            $(".card").LoadingOverlay("hide", true);
        }
    });
});

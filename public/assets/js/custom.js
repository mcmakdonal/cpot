var dataTable = '';
var report_dataTable = '';
var select = '';
$(document).ready(function () {
    dataTable = $('#dataTable').DataTable();

    report_dataTable = $('#report_dataTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['excel'],
        dom: 'Bfrtip'
    });

    $('select').select2();
});

function destroy(src, id) {
    var r = confirm("Delete this content !");
    if (r == true) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                    "content"
                )
            },
            url: src + "/" + id,
            method: "delete",
            beforeSend() {},
            success: function (result) {
                var obj = result;
                if (obj.status) {
                    window.location.reload();
                } else {
                    swal("Warning !", obj.message, "error");
                }
            },
            error(xhr, status, error) {
                swal("Danger !", "Fail !", error + " Status : " + status, "error");
            }
        });
    }
}

function select_youtube(e) {
    var data = JSON.parse($(e).attr('data'));
    var cyoutube = $(".cyoutube").length + 1;
    var class_uq = uuidv4();
    var html = '';
    var title = data.my_title;
    html += '<div class="col-md-11 mb-3 cyoutube ' + class_uq + '"><label class="sr-only" for=""></label><div class="input-group"><div class="input-group-prepend"><div class="input-group-text">' + cyoutube + '.</div></div><input type="text" class="form-control" readonly value="' + title.replace('"', '') + '"></div></div>';
    html += '<div class="col-md-1 mb-3 ' + class_uq + '"><button type="button" data="' + class_uq + '" onclick="remove_block(this)" class="btn btn-wanring"><span class="ti-trash"></span></button></div>';
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
        beforeSend() {},
        success: function (result) {
            var obj = result;
            if (obj.status) {
                window.location.reload();
            } else {
                swal("Warning !", obj.message, "error");
            }
        },
        error(xhr, status, error) {
            swal("Danger !", "Fail !", error + " Status : " + status, "error");
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
        beforeSend() {},
        success: function (result) {
            var obj = result;
            if (obj.status) {
                window.location.reload();
            } else {
                swal("Warning !", obj.message, "error");
            }
        },
        error(xhr, status, error) {
            swal("Danger !", "Fail !", error + " Status : " + status, "error");
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
        },
        error(xhr, status, error) {
            alert(error);
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
        },
        error(xhr, status, error) {
            alert(error);
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

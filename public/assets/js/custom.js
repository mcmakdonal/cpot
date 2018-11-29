var dataTable = '';
$(document).ready(function () {
    dataTable = $('#dataTable').DataTable();
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
                    swal("Warning !", "Delete not success", "error");
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
    var cyoutube = $(".cyoutube").length;
    if (parseInt(cyoutube) == 4 || parseInt(cyoutube) > 3) {
        swal("Info !", "Maximum Vdo Selected", "warning");
        return;
    }
    var id = uuidv4();
    var html = '';
    html += '<div class="form-group cyoutube" id="' + id + '">';
    html += '<div class="row">';
    html += '<h4 class="col-1 m-auto">No.' + (cyoutube + 1) + ' </h4>';
    html += '<input class="form-control col-9" type="text" value="' + data.my_title + '" readonly>';
    html += '<button onclick="youtube_remove(this)" data="' + id + '" class="col-2 btn btn-danger">Delete</button>';
    html += '<textarea class="form-control d-none" name="youtube[]" readonly="">' + JSON.stringify(data) + '</textarea>';
    html += '</div>';
    html += '</div>';

    $("#html-block").append(html);
    $(e).attr('disabled', 'disabled');
}

function uuidv4() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16 | 0,
            v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

function youtube_remove(e) {
    var id = $(e).attr('data');
    $("#" + id).remove();
}

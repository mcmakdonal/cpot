$(".forget-password-modal-lg").on("hidden.bs.modal", function () {
    $("form")
        .find("input")
        .val("");
    $("#msg").text("");
});

$(".forget-password-modal-lg").on("show.bs.modal", function () {

});

function forget_password() {
    var email = $("#email").val();
    if (email === "") {
        $("#msg").text("กรุณากรอกอีเมล");
        $("#email").focus();
        $(".forget-password-modal-lg").LoadingOverlay("hide", true);
    }
    $.ajax({
        url: "/forget-password",
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                "content"
            )
        },
        data: {
            email: email
        },
        beforeSend() {
            $(".forget-password-modal-lg").LoadingOverlay("show", {
                fontawesome: "fa-spin",
                zIndex: 10001
            });
        },
        success: function (result) {
            $("#msg").text(result.message);
            $(".forget-password-modal-lg").LoadingOverlay("hide", true);
        },
        error: function (xhr) {
            alert(xhr);
            $(".forget-password-modal-lg").LoadingOverlay("hide", true);
        }
    });
}

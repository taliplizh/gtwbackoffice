$(".create").click(function (e) {
    e.preventDefault();
    var href = $(this).attr("href");
    var title = $(this).attr("title");
    $.ajax({
        url: href,
        beforeSend: function () {
            loadWait();
        },
        success: function (result) {
            $(".label-header").html(title);
            $(".modal-dialog").removeClass("modal-md");
            $("#mediumBody").html(result).show();
        },
        complete: function () {},
        error: function (jqXHR, testStatus, error) {
            console.log(error);
            alert("Page " + href + " cannot open. Error:" + error);
        },
    });
});

$(".edit").click(function (e) {
    e.preventDefault();
    var href = $(this).attr("href");
    var title = $(this).attr("title");
    console.log(title);
    $.ajax({
        url: href,
        beforeSend: function () {
            loadWait();
        },
        success: function (result) {
            // $(".label-header").html(title);
            $('#modalTitle').html('<h4>'+title+'</h4>')
            $(".modal-dialog").removeClass("modal-md");
            $("#mediumBody").html(result).show();
        },
        complete: function () {},
        error: function (jqXHR, testStatus, error) {
            console.log(error);
            alert("Page " + href + " cannot open. Error:" + error);
        },
    });
});

$(".delete").click(function (e) {
    e.preventDefault();
    var url = $(this).attr("href");
    var title = $(this).attr("title");
    swal({
        title: title,
        text: "ยืนยันลบ " + title + " ?",
        type: "warning",
        showCancelButton: !0,
        confirmButtonText: "ใช่ ฉันต้องการลบ!",
        cancelButtonText: "ไม่, ฉันไม่ต้องการ!",
        reverseButtons: !0,
    }).then(
        function (e) {
            if (e.value === true) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
                $.ajax({
                    type: "DELETE",
                    url: url,
                    success: function (response) {
                        if (response.success) {
                            swal("Done !", response.message, "success");
                            window.location.reload(true);
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                });

                } else {
                    e.dismiss;
            }
        },
        function (dismiss) {
            return false;
        }
    );
});


function loadWait(){
    $('#mediumModal').modal("show");
    $('.label-header').html('<i class="far fa-clock"></i> กรุณารอสักครู่');
    // $('#mediumBody').html('<img src="/image/loading-gif.gif" width="200px" style=" margin: auto;display: block"/>');
}
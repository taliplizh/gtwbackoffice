//  $("form")
//     .find("input")
//     .each(function (index) {
//         var name = $(this).attr("name");
//         var val = $(this).attr("value");
//         $(this).prop('id',name);
       
//     });

// $("form")
//     .find(".date")
//     .each(function (index) {
//         var e = $(this).attr("id");
//         var val = $(this).attr("value");
//             if($('#'+e).val() !==''){
//                 if($(this).val().split('/')){
//                     var date = $(this).val().split('/')
//                     var dateYear = date[2]-543;
//                     var formatDate = date[0]+'/'+date[1]+'/'+dateYear;
//                     console.log(formatDate);
//                 }else{
//                     var formatDate = 0;
//                 }
//                 }
//             $("#" + e)
//             .datepicker({
//                 format: "dd/mm/yyyy",
//                 autoclose: true,
//                 language: "th",
//                 thaiyear: true,
//                 clearBtn:true,
//                 todayHighlight:true
//             })
//             .datepicker("setDate", formatDate);
//             $('#'+e).prop('readonly', true);
//     })

// $("select").select2({
//     width: '100%',
// });

//     $(".select2").select2({
//         width: '100%',
//     });
    function formSubmit(form) {
        // window.location.href = "http://stackoverflow.com";
        $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            dataType: "json",
            success: function (response) {
                // console.log(response)
                // window.location.href(response);
                if(response.status === 1){

                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกข้อมูลสำเร็จ',
                        showConfirmButton: false,
                      })

                    $('.loading-page').show();
                    window.location.href = response.url;
                    
                }
            },
            error: function (jqXHR, testStatus, error) {
            var errorObj = jqXHR.responseJSON;
            if(errorObj){

                $.each( errorObj.errors, function( key, value ) {
                    //เลือก eliment ที่เป็น ID
                    // $('#'+key).addClass('is-invalid');
                    
                    //เลือก eliment ที่เป็นชื่อ
                    $("input[name='"+key+"']").addClass('is-invalid');


                    console.log(key)
                });
                Swal.fire({
                    icon: 'error',
                    title: 'กรอกข้อมูลให้ครบถ้วน',
                    text: 'Something went wrong!',
                   
                  })
                
            }
        },
        });
    }
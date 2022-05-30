@extends('layouts.backend')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="{{ asset('asset/ets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
<link href="{{ asset('datepicker/dist/css/bootstrap-datepicker.css') }}" rel="stylesheet" />
<link href="{{asset('select2/select2.min.css')}}" rel="stylesheet" />
@section('content')
<?php
  function RemovegetAge($birthday) {
    $then = strtotime($birthday);
    return(floor((time()-$then)/31556926));
}
?>
 <div class="container">
     <div class="block block-rounded block-bordered col md-8" style="margin-left: auto; margin-right:auto; margin-top:50px;">
        <div class="block-header">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="content-heading pt-0" style="font-family: 'Kanit', sans-serif; margin-top: 30px;">
                        ข้อมูลเครื่องราชอิสริยาภรณ์</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($regalias as $regalia)
            <input type="hidden" value="{{ $regalia->id}}" name="id" id="id">
            <div class="block-content font-content">
                <div class="col-md-12" style="margin-top:10px;">
                    <div class="row">
                    <div class="col-md-4">
                        <span><b>ปีที่ได้รับ</b></span>
                        <span>
                        <select class="form-control" name="yearOfReceipt" id="yearOfReceipt" value="{{ $regalia->YEAR_OF_RECEIPT }}">
                            <option value="2565">2565</option>
                            <option value="2564">2564</option>
                            <option value="2563">2563</option>
                            <option value="2562">2562</option>
                            <option value="2561">2561</option>
                            <option value="2560">2560</option>
                        </select>
                        </span>
                    </div>
                    <div class="col-md-4">
                        <span><b>วันที่ได้รับ</b></span>
                        <span><input type="text" class="form-control datepicker" name="dayOfReceipt" id="dayOfReceipt" value="{{ $regalia->DAY_OF_RECEIPT }}"></span>
                    </div>
                    <div class="col-md-4">
                        <span><b>วันที่ประกาศ</b></span>
                        <span><input type="text" class="form-control datepicker" name="announcementDate" id="announcementDate" value="{{ $regalia->ANNOUNCEMENT_DATE }}"></span>
                    </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top:30px;">
                <div class="row">
                    <div class="col-md-4">
                    <span><b>ชั้นตราเครื่องราช</b></span>
                    <span>
                        <select class="form-control" name="badge" id="badge" value="{{ $regalia->BADGE }}">
                        <option value="จ.ม.">จ.ม.</option>
                        <option value="จ.ช.">จ.ช.</option>
                        <option value="ต.ม.">ต.ม.</option>
                        <option value="ต.ช.">ต.ช.</option>
                        <option value="ท.ม.">ท.ม.</option>
                        <option value="ร.จ.พ.">ร.จ.พ</option>
                        <option value="ท.ช.">ท.ช.</option>
                        </select>
                    </span>
                    </div>
                    <div class="col-md-4">
                    <span><b>ยศ/ตำแหน่ง</b></span>
                    <span><input type="text" class="form-control" name="position" id="position" value="{{ $regalia->POSITION }}"></span>
                    </div>
                    <div class="col-md-4">
                    <span><b>หน่วยงาน</b></span>
                    <span><input type="text" class="form-control" name="agency" id="agency" value="{{ $regalia->AGENCY }}"></span>
                    </div>
                </div>
                </div>
                <div class="col-md-12" style="margin-top:30px;">
                <div class="row">
                    <div class="col-md-4">
                    <span><b>เล่มที่</b></span>
                    <span><input type="text" class="form-control" name="volume" id="volume" value="{{ $regalia->VOLUME }}"></span>
                    </div>
                    <div class="col-md-4">
                    <span><b>หน้าที่</b></span>
                    <span><input type="text" class="form-control" name="duty" id="duty" value="{{ $regalia->DUTY }}"></span>
                    </div>
                    <div class="col-md-4">
                    <span><b>ลำดับที่</b></span>
                    <span><input type="text" class="form-control" name="no" id="no" value="{{ $regalia->NO }}"></span>
                    </div>
                </div>
                </div>
                <div class="col-md-12" style="margin-top:30px;">
                <div class="row">
                    <div class="col-md-2">
                    <span><b>รก.ล.</b></span>
                    <span><input type="text" class="form-control" name="badgeRgl" id="badgeRgl" value="{{ $regalia->BADGE_R_G_L }}"></span>
                    </div>
                    <div class="col-md-2">
                    <span><b>รก.ต.</b></span>
                    <span><input type="text" class="form-control" name="badgeRgd" id="badgeRgd" value="{{ $regalia->BADGE_R_G_D }}"></span>
                    </div>
                </div>
                </div>
                <div class="col-md-12" style="text-align: right; margin-top:30px; margin-bottom:30px;">
                <button type="button" class="btn btn-hero-sm btn-hero-info btn-up-config">
                    <i class="fas fa-save"></i> แก้ไขข้อมูล
                </button>
                <button type="button" class="btn btn-hero-sm btn-hero-danger" 
                onclick="window.location.href='/person/info-regalia/main'">
                    <i class="fas fa-window-close"></i> ยกเลิก
                </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
 </div>
@endsection
@section('footer')
<script src="{{asset('select2/select2.min.js')}}"></script>
<script src="{{ asset('datepicker/bootstrap-3.3.7-dist/js/bootstrap.js') }}"></script>
<script src="{{ asset('datepicker/dist/js/bootstrap-datepicker-custom.js') }}"></script>
<script src="{{ asset('datepicker/dist/locales/bootstrap-datepicker.th.min.js') }}" charset="UTF-8"></script>

<!-- Page JS Plugins -->
<script src="{{ asset('asset/js/plugins/easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
<script src="{{ asset('asset/js/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('asset/js/plugins/chart.js/Chart.bundle.min.js') }}"></script>
<!-- Page JS Code -->
<script src="{{ asset('asset/js/pages/be_comp_charts.min.js') }}"></script>
<script>
    jQuery(function () {
        Dashmix.helpers(['easy-pie-chart', 'sparkline']);
    });
</script>


<!-- Page JS Plugins -->
<script src="{{ asset('asset/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('asset/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('asset/js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
<script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
<script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
<script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>

{{-- dataTables v1  --}}
<script scr="{{ asset('asset/js/plugins/dataTablesV1/dataTables.min.js') }}"></script>
<script scr="{{ asset('asset/js/plugins/pace.min.js') }}"></script>
<script scr="{{ asset('asset/js/inspinia.js') }}"></script>
<script scr="{{ asset('asset/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script scr="{{ asset('asset/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>


<!-- Page JS Code -->
<script src="{{ asset('asset/js/pages/be_tables_datatables.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {    
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            todayBtn: true,
            language: 'th',  //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
            thaiyear: true,
            autoclose: true  //Set เป็นปี พ.ศ.
        });  //กำหนดเป็นวันปัจุบัน
        document.getElementById("birth_date").style.display = "none";
    });

      $(document).on('click','.btn-up-config', function() {
    // alert(token);
    var token = $("meta[name='csrf-token']").attr("content");
    var id = $('#id').val();
    var yearOfReceipt = $('#yearOfReceipt').val();
    var dayOfReceipt = $('#dayOfReceipt').val();
    var announcementDate = $('#announcementDate').val();
    var badge = $('#badge').val();
    var position = $('#position').val();
    var agency = $('#agency').val();
    var volume = $('#volume').val();
    var duty = $('#duty').val();
    var no = $('#no').val();
    var badgeRgl = $('#badgeRgl').val();
    var badgeRgd = $('#badgeRgd').val();

    $.ajax({

      type: 'POST',
      url: "/person/info-regalia/edit-config/"+id,

      data:{
        _token: token,
        _method: 'POST',
        id: id,
        yearOfReceipt: yearOfReceipt,
        dayOfReceipt: dayOfReceipt,
        announcementDate: announcementDate,
        badge: badge,
        position: position,
        agency: agency,
        volume: volume,
        duty: duty,
        no: no,
        badgeRgl: badgeRgl,
        badgeRgd: badgeRgd,
      },

      success: function (response) {
        consloe.log("OK Update SQL");
      }

    });
    window.location = "/person/info-regalia/main";
  });
</script>
@endsection
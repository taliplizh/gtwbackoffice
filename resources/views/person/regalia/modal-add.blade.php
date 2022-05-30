
<?php 
  $time_now = date('d/m/Y'); 
  $year_now = date('Y'); 
?>
<button type="button" class="btn btn-hero-sm btn-hero-info" data-toggle="modal" data-target=".bd-example-modal-lg">
  <i class="fas fa-plus"></i> เพิ่มข้อมูลเครื่องราชอิสริยาภรณ์
</button>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="block-header">
        <span class="font-title" style="margin-top:20px;">
          <b>เพิ่มข้อมูลราชอิสริยาภรณ์</b>
        </span>
    </div>
    <div class="block-content font-content">
        <div class="col-md-12" style="margin-top:10px;">
            <div class="row">
              <div class="col-md-4">
                <span><b>ปีที่ได้รับ</b></span>
                <span>
                  <select class="form-control" name="yearOfReceipt" id="yearOfReceipt">
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
                <span><input type="text" class="form-control datepicker" name="dayOfReceipt" id="dayOfReceipt" value="วัน/เดือน/ปี"></span>
              </div>
              <div class="col-md-4">
                <span><b>วันที่ประกาศ</b></span>
                <span><input type="text" class="form-control datepicker" name="announcementDate" id="announcementDate" value="วัน/เดือน/ปี"></span>
              </div>
            </div>
        </div>
        <div class="col-md-12" style="margin-top:30px;">
          <div class="row">
            <div class="col-md-4">
              <span><b>ชั้นตราเครื่องราช</b></span>
              <span>
                <select class="form-control" name="badge" id="badge">
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
              <span><input type="text" class="form-control" name="position" id="position"></span>
            </div>
            <div class="col-md-4">
              <span><b>หน่วยงาน</b></span>
              <span><input type="text" class="form-control" name="agency" id="agency"></span>
            </div>
          </div>
        </div>
        <div class="col-md-12" style="margin-top:30px;">
          <div class="row">
            <div class="col-md-4">
              <span><b>เล่มที่</b></span>
              <span><input type="text" class="form-control" name="volume" id="volume"></span>
            </div>
            <div class="col-md-4">
              <span><b>หน้าที่</b></span>
              <span><input type="text" class="form-control" name="duty" id="duty"></span>
            </div>
            <div class="col-md-4">
              <span><b>ลำดับที่</b></span>
              <span><input type="text" class="form-control" name="no" id="no"></span>
            </div>
          </div>
        </div>
        <div class="col-md-12" style="margin-top:30px;">
          <div class="row">
            <div class="col-md-2">
              <span><b>รก.ล.</b></span>
              <span><input type="text" class="form-control" name="badgeRgl" id="badgeRgl"></span>
            </div>
            <div class="col-md-2">
              <span><b>รก.ต.</b></span>
              <span><input type="text" class="form-control" name="badgeRgd" id="badgeRgd"></span>
            </div>
          </div>
        </div>
        <div class="col-md-12" style="text-align: right; margin-top:30px; margin-bottom:30px;">
          <button type="button" class="btn btn-hero-sm btn-hero-info btn-add-config">
            <i class="fas fa-save"></i> บันทึกข้อมูล
          </button>
          <button type="button" class="btn btn-hero-sm btn-hero-danger" data-dismiss="modal">
            <i class="fas fa-window-close"></i> ยกเลิก
          </button>
        </div>
    </div>
    </div>
  </div>
</div>
{{-- <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="block-header">
          <span class="font-title" style="margin-top:20px;">
            <b>เพิ่มข้อมูลราชอิสริยาภรณ์</b>
          </span>
      </div>
      <div class="block-content font-content">
          <div class="col-md-12" style="margin-top:10px;">
              <div class="row">
                <div class="col-md-4">
                  <span><b>ปีที่ได้รับ</b></span>
                  <span>
                    <select class="form-control" name="yearOfReceipt" id="yearOfReceipt">
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
                  <span><input type="text" class="form-control datepicker" name="dayOfReceipt" id="dayOfReceipt" value="วัน/เดือน/ปี"></span>
                </div>
                <div class="col-md-4">
                  <span><b>วันที่ประกาศ</b></span>
                  <span><input type="text" class="form-control datepicker" name="announcementDate" id="announcementDate" value="วัน/เดือน/ปี"></span>
                </div>
              </div>
          </div>
          <div class="col-md-12" style="margin-top:30px;">
            <div class="row">
              <div class="col-md-4">
                <span><b>ชั้นตราเครื่องราช</b></span>
                <span>
                  <select class="form-control" name="badge" id="badge">
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
                <span><input type="text" class="form-control" name="position" id="position"></span>
              </div>
              <div class="col-md-4">
                <span><b>หน่วยงาน</b></span>
                <span><input type="text" class="form-control" name="agency" id="agency"></span>
              </div>
            </div>
          </div>
          <div class="col-md-12" style="margin-top:30px;">
            <div class="row">
              <div class="col-md-4">
                <span><b>เล่มที่</b></span>
                <span><input type="text" class="form-control" name="volume" id="volume"></span>
              </div>
              <div class="col-md-4">
                <span><b>หน้าที่</b></span>
                <span><input type="text" class="form-control" name="duty" id="duty"></span>
              </div>
              <div class="col-md-4">
                <span><b>ลำดับที่</b></span>
                <span><input type="text" class="form-control" name="no" id="no"></span>
              </div>
            </div>
          </div>
          <div class="col-md-12" style="margin-top:30px;">
            <div class="row">
              <div class="col-md-2">
                <span><b>รก.ล.</b></span>
                <span><input type="text" class="form-control" name="badgeRgl" id="badgeRgl"></span>
              </div>
              <div class="col-md-2">
                <span><b>รก.ต.</b></span>
                <span><input type="text" class="form-control" name="badgeRgd" id="badgeRgd"></span>
              </div>
            </div>
          </div>
          <div class="col-md-12" style="text-align: right; margin-top:30px; margin-bottom:30px;">
            <button type="button" class="btn btn-hero-sm btn-hero-info btn-add-config">
              <i class="fas fa-save"></i> บันทึกข้อมูล
            </button>
            <button type="button" class="btn btn-hero-sm btn-hero-danger" data-dismiss="modal">
              <i class="fas fa-window-close"></i> ยกเลิก
            </button>
          </div>
      </div>
    </div>
  </div>
</div> --}}
<script>

  $(document).on('click','.btn-add-config', function() {
    // alert(token);
    var token = $("meta[name='csrf-token']").attr("content");
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
      url: "/person/info-regalia/add-config",

      data:{
        _token: token,
        _method: 'POST',
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
        consloe.log("OK Insert SQL");
      }

    });
    window.location = "/person/info-regalia/main";
  });

</script>
@extends('layouts.launder')
<!-- Page JS Plugins CSS -->

<link rel="stylesheet" href="{{ asset('asset/ets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
@section('css_before')


<style>
    body * {
        font-family: 'Kanit', sans-serif;
    }

    body {
    font-family: 'Kanit', sans-serif;
    font-size: 14px;

  }
    p {
        word-wrap: break-word;
    }

    .text {
        font-family: 'Kanit', sans-serif;
    }

    .card-white .card-heading {
        color: #333;
        background-color: #fff;
        border-color: #ddd;
        border: 1px solid #dddddd;
    }

    .card-white .card-footer {
        background-color: #fff;
        border-color: #ddd;
    }

    .card-white .h5 {
        font-size: 14px;
        //font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    }

    .card-white .time {
        font-size: 12px;
        //font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    }

    .post .post-heading {
        height: 95px;
        padding: 20px 15px;
    }

    .post .post-heading .avatar {
        width: 60px;
        height: 60px;
        display: block;
        margin-right: 15px;
    }

    .post .post-heading .meta .title {
        margin-bottom: 0;
    }

    .post .post-heading .meta .title a {
        color: black;
    }

    .post .post-heading .meta .title a:hover {
        color: #aaaaaa;
    }

    .post .post-heading .meta .time {
        margin-top: 8px;
        color: #999;
    }

    .post .post-image .image {
        width: 100%;
        height: auto;
    }

    .post .post-description {
        padding: 15px;
    }

    .post .post-description p {
        font-size: 14px;
    }

    .post .post-description .stats {
        margin-top: 20px;
    }

    .post .post-description .stats .stat-item {
        display: inline-block;
        margin-right: 15px;
    }

    .post .post-description .stats .stat-item .icon {
        margin-right: 8px;
    }

    .post .post-footer {
        border-top: 1px solid #ddd;
        padding: 15px;
    }

    .post .post-footer .input-group-addon a {
        color: #454545;
    }

    .post .post-footer .comments-list {
        padding: 0;
        margin-top: 20px;
        list-style-type: none;
    }

    .post .post-footer .comments-list .comment {
        display: block;
        width: 100%;
        margin: 20px 0;
    }

    .post .post-footer .comments-list .comment .avatar {
        width: 35px;
        height: 35px;
    }

    .post .post-footer .comments-list .comment .comment-heading {
        display: block;
        width: 100%;
    }

    .post .post-footer .comments-list .comment .comment-heading .user {
        font-size: 14px;
        font-weight: bold;
        display: inline;
        margin-top: 0;
        margin-right: 10px;
    }

    .post .post-footer .comments-list .comment .comment-heading .time {
        font-size: 12px;
        color: #aaa;
        margin-top: 0;
        display: inline;
    }

    .post .post-footer .comments-list .comment .comment-body {
        margin-left: 50px;
    }

    .post .post-footer .comments-list .comment>.comments-list {
        margin-left: 50px;
    }

    .gradiant-bg {
        font-size: 25px;
        /* font-weight: bold; */
        background: linear-gradient(45deg, #f0a30a, #DB7C49, #f0a30a, #DB7C49, #f0a30a);
        background-size: 40%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;

        animation: gradient 5s infinite;
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        100% {
            background-position: 100% 50%;
        }
    }
    table,
  td,
  th {
    border: 1px solid black;
  }
  .text-pedding {
    padding-left: 10px;
  }

  .text-font {
    font-size: 13px;
  }

</style>

@endsection

@section('content')
{{-- <style>
  .center {
    margin: auto;
    width: 100%;
    padding: 10px;
  }

  body {
    font-family: 'Kanit', sans-serif;
    font-size: 14px;

  }

  label {
    font-family: 'Kanit', sans-serif;
    font-size: 14px;

  }

  @media only screen and (min-width: 1200px) {
    label {
      float: right;
    }

  }


  .text-pedding {
    padding-left: 10px;
  }

  .text-font {
    font-size: 13px;
  }


  .form-control {
    font-family: 'Kanit', sans-serif;
    font-size: 13px;
  }

  table,
  td,
  th {
    border: 1px solid black;
  }
</style> --}}
<script>
  function checklogin() {
    window.location.href = '{{route("index")}}';
  }
</script>
<?php

if(Auth::check()){
    $status = Auth::user()->status;
    $id_user = Auth::user()->PERSON_ID;   
}else{
    
    echo "<body onload=\"checklogin()\"></body>";
    exit();
} 

$url = Request::url();
$pos = strrpos($url, '/') + 1;
$user_id = substr($url, $pos); 


?>
<?php
function RemoveDateThai($strDate)
{
  $strYear = date("Y",strtotime($strDate))+543;
  $strMonth= date("n",strtotime($strDate));
  $strDay= date("j",strtotime($strDate));

  $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
  $strMonthThai=$strMonthCut[$strMonth];
  return "$strDay $strMonthThai $strYear";
  }

  function RemoveDateThairetire($strDate)
{

  $strMonth= date("n",strtotime($strDate));
  if($strMonth  > 9){
    $strYear = date("Y",strtotime($strDate))+543+61;
  }else{
    $strYear = date("Y",strtotime($strDate))+543+60;
  }

  return "30 ก.ย. $strYear";
  }

  function RemovegetAge($birthday) {
    $then = strtotime($birthday);
    return(floor((time()-$then)/31556926));
}

function RemovegetAgeretire($birthday) {
  $then = strtotime($birthday);

  return(60-(floor((time()-$then)/31556926)));
}

?>

<br>
<br>
<center>
  <div class="block" style="width: 95%;">
    <div class="block block-rounded block-bordered">
      <div class="block-content">
        <h2 class="content-heading pt-0" style="font-family: 'Kanit', sans-serif;">ตั้งค่าหน่วยงานรับผ้า</h2>
          <div class="col-lg-12" align="right">
            <a href="{{ url('manager_launder/launder_dep_add') }}" align="right" class="btn btn-hero-sm btn-hero-info loadscreen"
              style="font-family: 'Kanit', sans-serif;font-weight:normal;"><i class="fas fa-plus"></i> เพิ่มข้อมูล</a>

          </div>

        <div class="block-content">

          {{-- <div class="panel-body" style="overflow-x:auto;"> --}}
            <table class="gwt-table table-striped table-vcenter " id="myTable" width="100%">
              <thead style="background-color: #FFEBCD;">

                <tr height="40">
                  <th class="text-font"
                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;text-align: center;"
                    width="5%">ลำดับ</th>

                  <th class="text-font"
                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;text-align: center;"
                    width="10%">ชื่อย่อหน่วยงาน</th>
                  <th class="text-font"
                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;text-align: center;">หน่วยงาน
                  </th>

                  <th class="text-font"
                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;text-align: center;"
                    width="10%">คำสั่ง</th>


                </tr>
                </tr>
              </thead>
              <tbody>
                <?php $number = 0; ?>
                @foreach ($infolaunderdeps as $infolaunderdep)
                <?php $number++; 
                
                   ?>
                <tr height="20">
                  <td class="text-font" align="center"> {{ $number }}</td>

                  <td class="text-pedding text-font"> {{ $infolaunderdep -> LAUNDER_DEP_NAMECODE }}</td>
                  <td class="text-pedding text-font"> {{ $infolaunderdep -> LAUNDER_DEP_NAME }}</td>


                  <td align="center">
                    <div class="dropdown">
                      <button type="button" class="btn btn-outline-info dropdown-toggle"
                        id="dropdown-align-outline-info" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false"
                        style="font-family: 'Kanit', sans-serif; font-size: 13px;font-weight:normal;">
                        ทำรายการ
                      </button>
                      <div class="dropdown-menu" style="width:10px">
                        <a class="dropdown-item loadscreen"
                          href="{{ url('manager_launder/launder_dep_edit/'.$infolaunderdep->LAUNDER_DEP_ID)  }}"
                          style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">แก้ไข</a>
                        <a class="dropdown-item loadscreen"
                          href="{{ url('manager_launder/launder_dep_clothingtype/'.$infolaunderdep->LAUNDER_DEP_ID)  }}"
                          style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">เพิ่มประเภทผ้า</a>
                        <a class="dropdown-item"
                          href="{{ url('manager_launder/launder_dep_destroy/'.$infolaunderdep->LAUNDER_DEP_ID)  }}"
                          onclick="return confirm('ต้องการที่จะลบข้อมูล ?')"
                          style="font-family: 'Kanit', sans-serif; font-size: 13px;font-weight:normal;">ลบข้อมูล</a>

                      </div>
                    </div>

                </tr>


                @endforeach
              </tbody>
            </table>
          </div>



          @endsection
          @section('footer')

          <!-- Page JS Plugins -->
          {{-- <script src="{{ asset('asset/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
          <script src="{{ asset('asset/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script> --}}
          {{-- <script src="{{ asset('asset/js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script> --}}
          {{-- <script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.print.min.js') }}"></script> --}}
          {{-- <script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script> --}}
          {{-- <script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script> --}}
          {{-- <script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script> --}}

          <!-- Page JS Code -->
          {{-- <script src="{{ asset('asset/js/pages/be_tables_datatables.min.js') }}"></script> --}}

          <script>
            $(document).ready(function() {
                $("#myTable").DataTable();
            });
        </script>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> --}}
    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <!-- Page JS Plugins -->
        <script src="{{ asset('asset/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('asset/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    

          <script>
            function switchleave(idperson) {
              // alert(budget);
              var checkBox = document.getElementById(idperson);
              var onoff;

              if (checkBox.checked == true) {
                onoff = "True";
              } else {
                onoff = "False";
              }
              //alert(onoff);

              var _token = $('input[name="_token"]').val();
              $.ajax({
                url: "{{route('mleave.idperson')}}",
                method: "GET",
                data: {
                  onoff: onoff,
                  idperson: idperson,
                  _token: _token
                }
              })
            }
          </script>

          <script>
            function checkpass(id) {
              var text;
              var NEWPASSWORD = document.getElementById("NEWPASSWORD_" + id).value;
              var CHECK_NEWPASSWORD = document.getElementById("CHECK_NEWPASSWORD_" + id).value;


              // alert(NEWPASSWORD);

              if (NEWPASSWORD !== CHECK_NEWPASSWORD) {
                document.getElementById("text" + id).style.display = "";
                text = "*กรุณาระบุรหัสผ่านให้ตรงกับยืนยันรหัสผ่าน";
                document.getElementById("text" + id).innerHTML = text;


              }


              if (NEWPASSWORD !== CHECK_NEWPASSWORD) {
                return false;
              } else if (NEWPASSWORD == null || NEWPASSWORD == '' || CHECK_NEWPASSWORD == null || CHECK_NEWPASSWORD ==
                '') {

                return false;
              }

            }
          </script>
          @endsection
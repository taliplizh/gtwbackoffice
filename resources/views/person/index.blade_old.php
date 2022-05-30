@extends('layouts.backend_admin')
<!-- Page JS Plugins CSS -->

<link rel="stylesheet" href="{{ asset('asset/ets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">


@section('content')
<style>
.center {
  margin: auto;
  width: 100%;
  padding: 10px;
}
body {
      font-family: 'Kanit', sans-serif;
      font-size: 13px;
      
      }

      label{
            font-family: 'Kanit', sans-serif;
            font-size: 14px;
         
      }

       .text-pedding{
   padding-left:10px;
}

.text-font {
    font-size: 13px;
}   
</style>

<script>
function checklogin(){
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
           
                    <!-- Advanced Tables -->
             
                    
                                     
          <div class="content">
          <div class="block block-rounded block-bordered">
          <div class="block-content">    
          <h2 class="content-heading pt-0" style="font-family: 'Kanit', sans-serif;">
          <div class="row">
          <div class="col-md-9">
          ข้อมูลบุคลากรจำนวน  {{ $count }} คน
          </div>
          <div class="col-md-3">
              
               
             
                <a href="{{ url('person/excelall')}}"  class="btn btn-sm btn-success shadow"  ><li class="fa fa-file-excel"></li>&nbsp;&nbsp;&nbsp;Excel&nbsp;&nbsp;</a>
             
                <a href="#exampleModal"   data-toggle="modal"  class="btn btn-sm btn-warning shadow" ><li class="fa fa-file-excel"></li>&nbsp;&nbsp;&nbsp;Import Excel&nbsp;&nbsp;</a>
              
                

         
         
              </div>
          </div>
          </h2>  
                                
             <form action="{{ route('addperson.search') }}" method="get">
            
             <div class="row">
                   <div class="col-md-5">
                   <a href="{{ url('person/adduser') }}" class="btn btn-info" role="button" ><i class="fas fa-plus"></i> เพิ่มข้อมูล</a>
                   </div>
                   <div class="col-md-3">
                    
                  </div>  
                  <div class="col-md-3">
                  <span>
                 <input type="search"  name="search" class="form-control"  >
                </span>
                 </div>
                 <div class="col-md-1">
                 <span>
                     <button type="submit" class="btn btn-info" >ค้นหา</button>
                 </span> 
                 </div>
               
          
                  </div>  
             </form>
            
         
        <div class="panel-body" style="overflow-x:auto;">     
        <table class="gwt-table table-striped table-vcenter js-dataTable-simple" width="100%">
        <thead style="background-color: #FFEBCD;">
                  
        <tr height="40">    
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="5%" >ลำดับ</th>       
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="10%" >CID</th>
      
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="18%">ชื่อ นามสกุล</th>
            
     
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="3%">เพศ</th>
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="10%">วันเกิด</th>
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="3%">อายุ</th>
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="10%">วันเกษียณ</th>
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="8%">คงเหลือ</th>
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="10%">สถานะ</th>
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;">ตำแหน่ง</th>
      
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;">หน่วยงาน</th>
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;">ฝ่าย/แผนก</th>
       
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;"width="8%">คำสั่ง</th>
        
      </tr>
                   </tr>
                   </thead>
                   <tbody>
                   <?php $number = 0; ?>
                   @foreach ($persons as $person)
                   <?php $number++; 
                   if( $person->HR_STATUS_ID == 5 || $person->HR_STATUS_ID == 6 || $person->HR_STATUS_ID == 7 || $person->HR_STATUS_ID == 8){
                   $color = 'background-color: #FFF0F5;';

                    }else{
                    $color = '';
                   }
                   ?> 
                   <tr style="{{$color}}" height="20">
                   <td class="text-font" align="center"> {{ $number }}</td>  
                     <td class="text-font" align="center"> {{ $person -> HR_CID }}</td>  
                     <td class="text-pedding text-font">{{ $person -> HR_PREFIX_NAME }}{{ $person -> HR_FNAME }} {{ $person -> HR_LNAME }}</td>                     
                     <td class="text-pedding text-font"> {{ $person -> SEX_NAME }}</td> 
                     <td class="text-font" align="center"> {{ DateThai($person -> HR_BIRTHDAY) }}</td>  
                     <td class="text-font" align="center"> {{ getAge($person -> HR_BIRTHDAY) }} </td> 
                     <td class="text-font" align="center"> {{ DateThairetire($person -> HR_BIRTHDAY) }}</td>  
                     <td class="text-font" align="center"> {{getAgeretire($person -> HR_BIRTHDAY) }} </td> 
                     <td class="text-pedding text-font"> {{ $person -> HR_STATUS_NAME }}</td>   
                     <td class="text-pedding text-font"> {{ $person -> POSITION_IN_WORK }}</td>   
                   
                     <td class="text-pedding text-font"> {{ $person -> HR_DEPARTMENT_SUB_SUB_NAME }}</td> 
                     <td class="text-pedding text-font"> {{ $person -> HR_DEPARTMENT_SUB_NAME }}</td>     
                      
                     <td align="center">
                     <div class="dropdown">
                     <button type="button" class="btn btn-outline-info dropdown-toggle" id="dropdown-align-outline-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-family: 'Kanit', sans-serif; font-size: 10px;font-weight:normal;">
                                                    ทำรายการ
                                                </button>
                                                <div class="dropdown-menu" style="width:10px">
                                                @if($id_user !== '0')
                                                    <a class="dropdown-item" href="{{ url('dashboard/'.$person -> ID)}}" style="font-family: 'Kanit', sans-serif; font-size: 15px;font-weight:normal;" target="_blank" >รายละเอียด</a>
                                                    <a class="dropdown-item" href="#changpass_modal{{ $person -> ID}}"   data-toggle="modal" style="font-family: 'Kanit', sans-serif; font-size: 15px;font-weight:normal;">แก้ไขรหัสผ่าน</a>
                                                 @endif   
                                                    <a class="dropdown-item" href="#changstatususer_modal{{ $person -> ID}}" data-toggle="modal" style="font-family: 'Kanit', sans-serif; font-size: 15px;font-weight:normal;">กําหนดสิทธิ์</a>
                                                  
                                                </div>
                    </div>
                     </td>                        

                   </tr>

                   
<div id="changpass_modal{{ $person -> ID }}" class="modal fade edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
     
          <h2 class="modal-title" style="font-family: 'Kanit', sans-serif; font-size:15px;font-size: 1.5rem;font-weight:normal;">แก้ไขรหัสผ่าน {{ $person -> HR_PREFIX_NAME }}{{ $person -> HR_FNAME }} {{ $person -> HR_LNAME }}</h2>
        </div>
        <div class="modal-body">
        <body>
        <form  method="post" action="{{ route('updatepass.store') }}" onSubmit="return checkpass({{ $person -> ID }})">
        @csrf
        <input type="hidden" name="ID" value="{{ $person -> ID }}"/>
       <center><div style="color: red;" id="text{{ $person -> ID }}"></div></center>
     
        <div class="row push">
      <div class="col-sm-3 text-left">  
      <label >รหัสผ่านใหม่</label>
      </div>
      <div class="col-sm-7">  
      <input type="password" name = "NEWPASSWORD"  id="NEWPASSWORD_{{ $person -> ID }}"  class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;">
      </div>
      </div>
      <div class="row push">
      <div class="col-sm-3 text-left">  
      <label >ยืนยันรหัสผ่านใหม่</label>
      </div>
      <div class="col-sm-7">  
      <input type="password" name = "CHECK_NEWPASSWORD"  id="CHECK_NEWPASSWORD_{{ $person -> ID }}"  class="form-control input-lg"  style=" font-family: 'Kanit', sans-serif;">
      </div>
      </div>
      <input type="hidden" name = "USER_EDIT_ID"  id="USER_EDIT_ID" value="{{ $id_user }} ">


      </div>
        <div class="modal-footer">
        <div align="right">
        <button type="submit"  class="btn btn-hero-sm btn-hero-info" >บันทึกแก้ไขข้อมูล</button>
        <button type="button" class="btn btn-hero-sm btn-hero-danger" data-dismiss="modal" >ยกเลิก</button>
        </div>
        </div>
        </form>  
</body>
     
     
    </div>
  </div>
</div>






                   
<div id="changstatususer_modal{{ $person -> ID }}" class="modal fade edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
     
          <h2 class="modal-title" style="font-family: 'Kanit', sans-serif; font-size:15px;font-size: 1.5rem;font-weight:normal;">กำหนดประเภทผู้ใช้งาน {{ $person -> HR_PREFIX_NAME }}{{ $person -> HR_FNAME }} {{ $person -> HR_LNAME }}</h2>
        </div>
        <div class="modal-body">
        <body>
        <form  method="post" action="{{ route('updatestatusuer.store') }}">
        @csrf
        <input type="hidden" name="ID" value="{{ $person -> ID }}"/>
     
        <div class="row push">
      <div class="col-sm-3 text-left">  
      <label >กำหนดประเภทผู้ใช้งาน</label>
      </div>
      <div class="col-sm-7">  
    
      <select  name = "status"  id="status"  class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;">
          @if( $person->status == 'USER')<option value="USER" selected>USER</option>@else<option value="USER">USER</option>@endif
          @if( $person->status == 'ADMIN')<option value="ADMIN" selected>ADMIN</option>@else<option value="ADMIN">ADMIN</option>@endif
          @if( $person->status == 'NOTUSER')<option value="NOTUSER" selected>NOTUSER</option>@else<option value="NOTUSER">NOTUSER</option>@endif
     
      </select>
    
    </div>
      </div>

      <input type="hidden" name = "USER_EDIT_ID"  id="USER_EDIT_ID" value="{{ $id_user }} ">


      </div>
        <div class="modal-footer">
        <div align="right">
        <button type="submit"  class="btn btn-hero-sm btn-hero-info" >บันทึกแก้ไขข้อมูล</button>
        <button type="button" class="btn btn-hero-sm btn-hero-danger" data-dismiss="modal" >ยกเลิก</button>
        </div>
        </div>
        </form>  
</body>
     
     
    </div>
  </div>
</div>
                 
                   @endforeach 
                   </tbody>
                  </table>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
  <form action="{{ route('hrdperson_excel')}}" method="POST" enctype="multipart/form-data">           
      @csrf

          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Import Excel Person</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
          </div>
          <div class="modal-body">
              <input type="file" name="HRD_PERSON_EXCEL" id="HRD_PERSON_EXCEL" class="form-control input-sm" >
              {{-- <label class="custom-file-label" for="customFile">Choose file</label> --}}
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="invalid-feedback">กรุณาเลือกไฟล์ Excel</div>

              {{-- <div class="form-group">
                <div class="custom-file">
                    <input type="file" name="HRD_PERSON_EXCEL" id="HRD_PERSON_EXCEL">
                        class="custom-file-input {{ $errors->has('files') ? 'is-invalid' : '' }}"
                        value="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <label class="custom-file-label" for="customFile">กรุณาเลือกไฟล์</label>
                </div> --}}
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-secondary shadow-lg" data-dismiss="modal"><i class="fas fa-power-off fa-sm text-white-80" style="font-size:15px "></i>&nbsp;&nbsp;ปิด&nbsp;&nbsp;</button>
              <button type="submit" class="btn btn-sm btn-success shadow-lg"><i class="fas fa-upload fa-sm text-white-80" style="font-size:15px "></i>&nbsp;&nbsp;นำเข้า&nbsp;&nbsp;</button>
          </div>
      </div>
  </form>
  </div>
</div>
  <!-----------gเพิ่มข้อมูลเร่งรัด----------->

                  
<div id="add_usernow" class="modal fade edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
     
          <h2 class="modal-title" style="font-family: 'Kanit', sans-serif; font-size:15px;font-size: 1.5rem;font-weight:normal;"><i class="fas fa-plus"></i> เพิ่มผู้เข้าใช้งานแบบย่อ</h2>
        </div>
        <div class="modal-body">
        <body>
        <form  method="post" action="{{ route('adduserintensive.save') }}">
        @csrf
     
     
        <div class="row push">
      <div class="col-sm-3 text-left">  
      <label >ชื่อ</label>
      </div>
      <div class="col-sm-7">  
      <input  name = "HR_FNAME"  id="HR_FNAME"  class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;">
      </div>
      </div>
      <div class="row push">
      <div class="col-sm-3 text-left">  
      <label>นามสกุล</label>
      </div>
      <div class="col-sm-7">  
      <input  name = "HR_LNAME"  id="HR_LNAME"  class="form-control input-lg"  style=" font-family: 'Kanit', sans-serif;">
      </div>
      </div>

      <div class="row push">
      <div class="col-sm-3 text-left">  
      <label>E-mail</label>
      </div>
      <div class="col-sm-7">  
      <input  name = "HR_EMAIL"  id="HR_EMAIL"  class="form-control input-lg"  style=" font-family: 'Kanit', sans-serif;">
      </div>
      </div>
      

      </div>
        <div class="modal-footer">
        <div align="right">
        <button type="submit"  class="btn btn-hero-sm btn-hero-info" >บันทึก</button>
        <button type="button" class="btn btn-hero-sm btn-hero-danger" data-dismiss="modal" >ยกเลิก</button>
        </div>
        </div>
        </form>  
</body>
     
     
    </div>
  </div>
</div>


  <!----------------------------------->  
                      

@endsection
@section('footer')
    
     <!-- Page JS Plugins -->
        <script src="{{ asset('asset/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('asset/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('asset/js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
        <script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
        <script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>

        <!-- Page JS Code -->
        <script src="{{ asset('asset/js/pages/be_tables_datatables.min.js') }}"></script>


        <script>

          function checkpass(id) {
          var  text;
          var  NEWPASSWORD = document.getElementById("NEWPASSWORD_"+id).value;
          var  CHECK_NEWPASSWORD = document.getElementById("CHECK_NEWPASSWORD_"+id).value;

       
         // alert(NEWPASSWORD);
         
            if(NEWPASSWORD !== CHECK_NEWPASSWORD){
              document.getElementById("text"+id).style.display = "";     
            text = "*กรุณาระบุรหัสผ่านให้ตรงกับยืนยันรหัสผ่าน";
            document.getElementById("text"+id).innerHTML = text;
            

          }
          

          if(NEWPASSWORD !== CHECK_NEWPASSWORD){
             return false; 
          }else if(NEWPASSWORD==null || NEWPASSWORD=='' || CHECK_NEWPASSWORD==null || CHECK_NEWPASSWORD==''){
  
            return false; 
          }

          }



          $('body').on('keydown', 'input, select, textarea', function(e) {
    var self = $(this)
      , form = self.parents('form:eq(0)')
      , focusable
      , next
      ;
    if (e.keyCode == 13) {
        focusable = form.find('input,a,select,button,textarea').filter(':visible');
        next = focusable.eq(focusable.index(this)+1);
        if (next.length) {
            next.focus();
        } else {
            form.submit();
        }
        return false;
    }
});

        </script>
@endsection
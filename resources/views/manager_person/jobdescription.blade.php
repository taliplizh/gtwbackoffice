@extends('layouts.person')
<!-- Page JS Plugins CSS -->

<link rel="stylesheet" href="{{ asset('asset/ets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
<style>
.center {
  margin: auto;
  width: 100%;
  padding: 10px;
}
body {
      font-family: 'Kanit', sans-serif;
      font-size: 14px;
  
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

@section('content')
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
           
        <center>
                   
                <div style="width:95%;" >
          <div class="block block-rounded block-bordered">
          <div class="block-content">    
          <h2 class="content-heading pt-0" style="font-family: 'Kanit', sans-serif;">
          <div class="row">
          <div class="col-md-7" align="left">
          Job description :: 
            </div>
          <div class="col-md-5" align="right">
               <a href="{{ url('manager_person/resultability')  }}"  class="btn btn-success btn-lg"  >ย้อนกลับ</a>
              
          </div>
          </div>
          </h2>  
                                
             <form action="" method="get">
            
            <!--- <div class="row">
                 
                   <div class="col-md-4">
                    
                  </div>  
                  <div class="col-md-2">
                    ปีงบประมาณ
                  </div>  
                  <div class="col-md-2">
                  <select class="form-control" id="exampleFormControlSelect1">
                    <option>2563</option>
                    <option>2562</option>
                  
                  </select>
                    </div>  
                  <div class="col-md-3">
                  <span>
                 <input type="search"  name="search" class="form-control" style="font-family: 'Kanit', sans-serif; font-size: 13px;font-weight:normal;" >
                </span>
                 </div>
                 <div class="col-md-1">
                 <span>
                     <button type="submit" class="btn btn-info" >ค้นหา</button>
                 </span> 
                 </div>
               
          
                  </div>  
             </form>-->
            
         
        <div class="panel-body" style="overflow-x:auto;">     
        <table class="gwt-table table-striped table-vcenter js-dataTable-full" width="100%">
        <thead style="background-color: #FFEBCD;">
                  
        <tr height="40">    
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="5%" >ลำดับ</th>     
        <th class="text-font" style="border-color:#F0FFFF;text-align: center;"  >Job description</th>

        <th class="text-font" style="border-color:#F0FFFF;text-align: center;" width="8%">เปิดใช้</th>
    
        
      </tr>
                   </tr>
                   </thead>
                   <tbody>


                    <?php $number = 0; ?>
                      @foreach ($infojabdiss as $infojabdis)
                      <?php $number++;  ?>

                   <tr height="20">
                     <td class="text-font text-pedding" >{{$number}}</td> 
                     <td class="text-font text-pedding">{{ $infojabdis-> JOD_DIS_NAME }}</td>  
                   
                     <td align="center" width="5%">
                                            <div class="custom-control custom-switch custom-control-lg ">
                                             @if($infojabdis-> ACTIVE == 'True' )
                                                 <input type="checkbox" class="custom-control-input" id="{{ $infojabdis-> JOD_DIS_ID }}" name="{{ $infojabdis-> JOD_DIS_ID }}" onchange="switchactive({{ $infojabdis-> JOD_DIS_ID }});" checked>
                                             @else
                                                <input type="checkbox" class="custom-control-input" id="{{ $infojabdis-> JOD_DIS_ID }}" name="{{ $infojabdis-> JOD_DIS_ID }}" onchange="switchactive({{ $infojabdis-> JOD_DIS_ID }});">
                                             @endif
                                                <label class="custom-control-label" for="{{ $infojabdis-> JOD_DIS_ID }}"></label>
                                            </div>
                    </td>
                
                    </tr> 
                    @endforeach 
                 

              
                   </tbody>
                  </table>
</div>
    
                      

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

        </script>
@endsection
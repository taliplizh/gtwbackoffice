@extends('layouts.backend_admin')
  
    <link href="{{ asset('datepicker/dist/css/bootstrap-datepicker.css') }}" rel="stylesheet" />

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

if($status=='USER' and $user_id != $id_user  ){
    echo "You do not have access to data.";
    exit();
}
?>

           
                    <!-- Advanced Tables -->
                 
                <div class="content">
                <div class="block block-rounded block-bordered">

                <div class="block-content"> 
                <h2 class="content-heading pt-0" style="font-family: 'Kanit', sans-serif;">แก้ไขข้อมูลวิธีการจัดหา</h2>    

    
        <form  method="post" action="{{ route('admin.updatesuppliesmethod') }}" enctype="multipart/form-data">
        @csrf
        <div class="row push">
       
    <div class="col-lg-2">
      <label >วิธีการจัดหา</label>
      </div>
      <div class="col-lg-3">
      <input  name = "METHOD_NAME"  id="METHOD_NAME" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;" value="{{$infosuppliesmethod->METHOD_NAME}}" onkeyup="check_methodname();">
      <div style="color: red; font-size: 16px;" id="methodname"></div>   
    </div>
    
      <input  type="hidden" name = "METHOD_ID"  id="METHOD_ID" class="form-control input-lg" value="{{$infosuppliesmethod->METHOD_ID}}">
      </div></div>
        <div class="modal-footer">
        <div align="right">
        <button type="submit"  class="btn btn-hero-sm btn-hero-info" >บันทึกแก้ไขข้อมูล</button>
         <a href="{{ url('admin_asset_supplies/setupsuppliesmethod')  }}" class="btn btn-hero-sm btn-hero-danger" onclick="return confirm('ต้องการที่จะยกเลิกการแก้ไขข้อมูล ?')" >ยกเลิก</a> 
         </div>    
       
        </div>
        </form>  
           
      
        
                  
      
                      

@endsection

@section('footer')

<script>   
    function check_methodname()
    {                         
        methodname = document.getElementById("METHOD_NAME").value;             
            if (methodname==null || methodname==''){
            document.getElementById("methodname").style.display = "";     
            text_methodname = "*กรุณาระบุวิธีการจัดหา";
            document.getElementById("methodname").innerHTML = text_methodname;
            }else{
            document.getElementById("methodname").style.display = "none";
            }
    }
    

   </script>
    <script>      
    $('form').submit(function () {
     
      var methodname,text_methodname; 
     
      methodname = document.getElementById("METHOD_NAME").value; 
   
                     
      if (methodname==null || methodname==''){
      document.getElementById("methodname").style.display = "";     
      text_methodname = "*กรุณาระบุวิธีการจัดหา";
      document.getElementById("methodname").innerHTML = text_methodname;
      }else{
      document.getElementById("methodname").style.display = "none";
      }
  
  
      if(methodname==null || methodname==''     
       )
    {
    alert("กรุณาตรวจสอบความถูกต้องของข้อมูล");      
    return false;   
    }
    }); 
</script>



@endsection
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
                <h2 class="content-heading pt-0" style="font-family: 'Kanit', sans-serif;">แก้ไขข้อมูลห้อง</h2>    

    
        <form  method="post" action="{{ route('admin.updatesupplieslocationlevelroom') }}" enctype="multipart/form-data">
        @csrf
        <div class="row push">
       
    <div class="col-lg-2">
      <label >ชื่อห้อง</label>
      </div>
      <div class="col-lg-3">
      <input  name = "LEVEL_ROOM_NAME"  id="LEVEL_ROOM_NAME" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;" value="{{$infosupplieslocationlevelroom->LEVEL_ROOM_NAME}}">
      </div>

      <input type="hidden" name = "LEVEL_ROOM_ID"  id="LEVEL_ROOM_ID" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;" value="{{$infosupplieslocationlevelroom->LEVEL_ROOM_ID}}">
      <input type="hidden" name = "LOCATION_ID"  id="LOCATION_ID" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;" value="{{$idlocation}}">  
      <input type="hidden" name = "LOCATION_LEVEL_ID"  id="LOCATION_LEVEL_ID" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;" value="{{$idlocationlevel}}">

      </div></div>
        <div class="modal-footer">
        <div align="right">
        <button type="submit"  class="btn btn-hero-sm btn-hero-info" >บันทึกข้อมูล</button>
         <a href="{{ url('admin_asset_supplies/setupsupplieslocationlevelroom/'.$idlocation.'/'.$idlocationlevel)  }}" class="btn btn-hero-sm btn-hero-danger" onclick="return confirm('ต้องการที่จะยกเลิกการแก้ไขข้อมูล ?')" >ยกเลิก</a> 
         </div>    
       
        </div>
        </form>  
           
      
        
                  
      
                      

@endsection

@section('footer')





@endsection
@extends('layouts.warehouse')
<link href="{{ asset('datepicker/dist/css/bootstrap-datepicker.css') }}" rel="stylesheet" />

<link href="{{ asset('select2/select2.min.css') }}" rel="stylesheet" />

@section('content')


<?php
$status = Auth::user()->status; 
$id_user = Auth::user()->PERSON_ID; 
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


  function Removeformate($strDate)
{
  $strYear = date("Y",strtotime($strDate));
  $strMonth= date("m",strtotime($strDate));
  $strDay= date("d",strtotime($strDate));

  return $strDay."/".$strMonth."/".$strYear;
  }

      
  $m_budget = date("m");
  //$m_budget = 10;
 // echo $m_budget; 
  if($m_budget>9){
    $yearbudget = date("Y")+544;
  }else{
    $yearbudget = date("Y")+543;
  }

  
?>

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

      @media only screen and (min-width: 1200px) {
label {
    float:right;
  }

      }

      .text-pedding{
   padding-left:10px;
   padding-right:10px;
                    }

        .text-font {
    font-size: 13px;
                  }

                  .form-control {
    font-size: 13px;
                  }   
                  table, td, th {
            border: 1px solid black;
            } 

</style>
<body onload="run01();">

<center>
<div class="block" style="width: 95%;" >
<div class="block-header block-header-default" >
<h3 class="block-title" style="font-family: 'Kanit', sans-serif;"><B>
                         
                          แก้ไขรายละเอียดพัสดุตรวจรับ
                
</B></h3>

</div>
<br>
<form  method="post" action="{{ route('mwarehouse.detail_update') }}" enctype="multipart/form-data">
    @csrf
<input type="hidden" name="RECEIVE_ID" id="RECEIVE_ID" class="form-control input-sm" style=" font-family: 'Kanit', sans-serif;font-size: 13px;" value="{{$infocheckreceive->RECEIVE_ID}}">

        <div class="col-sm-12">
        <div class="row">
        <div class="col-lg-1" style="text-align: left">
        <label >                           
                            รหัส :              
        </label>
        </div> 
        <div class="col-lg-2 text-left">
            <input type="text" name="RECEIVE_CODE" id="RECEIVE_CODE" class="form-control input-sm" style=" font-family: 'Kanit', sans-serif;font-size: 13px;" value="{{$infocheckreceive->RECEIVE_CODE}}">
     
        </div> 
        <div class="col-lg-1" style="text-align: left">
        <label >                           
                            เลขที่เอกสาร :              
        </label>
        </div> 
        <div class="col-lg-2 text-left">
            <input type="text" name="RECEIVE_NUMBER" id="RECEIVE_NUMBER" class="form-control input-sm" style=" font-family: 'Kanit', sans-serif;font-size: 13px;" value="{{$infocheckreceive->RECEIVE_NUMBER}}">
      
        </div> 
        <div class="col-lg-2" style="text-align: left">
        <label >                           
                            ผู้ตรวจรับเข้า :              
        </label>
        </div> 
        <div class="col-lg-4">
        
                    <select name="RECEIVE_PERSON_ID" id="RECEIVE_PERSON_ID" class="form-control input-sm js-example-basic-single" style=" font-family: 'Kanit', sans-serif;" >
                                @foreach ($infopersons as $infoperson) 
                                        @if($infoperson -> ID == $infocheckreceive->RECEIVE_PERSON_ID)
                                        
                                        <option value="{{ $infoperson -> ID }}" selected>{{ $infoperson -> HR_FNAME }} {{ $infoperson -> HR_LNAME }}</option>                                          
                                        @else
                                        <option value="{{ $infoperson -> ID }}" >{{ $infoperson -> HR_FNAME }} {{ $infoperson -> HR_LNAME }}</option>                                          
                                        @endif 
                                    
                                @endforeach  
                    </select> 

        </div> 
        
        </div>

        <br>

        <div class="row">
        <div class="col-lg-1" style="text-align: left">
        <label >                           
                            คลัง :              
        </label>
        </div> 
        <div class="col-lg-2 ">

        <select name="RECEIVE_STORE" id="RECEIVE_STORE" class="form-control input-sm " style=" font-family: 'Kanit', sans-serif;" >
        <option value="" >--เลือกคลัง--</option>                                          
            @foreach ($infosuppliesinvens as $infosuppliesinven) 
               @if($infosuppliesinven -> INVEN_ID == $infocheckreceive -> RECEIVE_STORE )
               <option value="{{ $infosuppliesinven -> INVEN_ID }}" selected>{{ $infosuppliesinven -> INVEN_NAME }}</option>                                          
               @else 
                <option value="{{ $infosuppliesinven -> INVEN_ID }}" >{{ $infosuppliesinven -> INVEN_NAME }}</option>                                          
               @endif

             @endforeach  
        </select> 
      
       
        </div> 
        <div class="col-lg-1" style="text-align: left">
        <label >                           
        เลข PO :              
        </label>
        </div> 
        <div class="col-lg-2 text-left">
     
        <input type="text" name="RECEIVE_PO" id="RECEIVE_PO" class="form-control input-sm" style=" font-family: 'Kanit', sans-serif;font-size: 13px;" value="{{$infocheckreceive->RECEIVE_PO}}">
        </div> 
        <div class="col-lg-2" style="text-align: left">
        <label >                           
                            วันที่กรรมการตรวจรับ :              
        </label>
        </div> 
        <div class="col-lg-2 text-left">
      
        <input name="RECEIVE_CHECK_DATE" id="RECEIVE_CHECK_DATE" class="form-control input-sm datepicker" style=" font-family: 'Kanit', sans-serif;font-size: 13px;"value="{{formate($infocheckreceive->RECEIVE_CHECK_DATE)}}" readonly>

        </div> 
       
    </div> 
        <br>
       <div class="row">
       
        <div class="col-lg-1" style="text-align: left">
        <label>รับจาก :</label>
        </div> 
        <div class="col-lg-5 text-left">
        
        <select name="RECEIVE_ACCEPT_FROM" id="RECEIVE_ACCEPT_FROM" class="form-control input-sm js-example-basic-single" style=" font-family: 'Kanit', sans-serif;" >
        <option value="" >--เลือกที่มา--</option>                                          
            @foreach ($infosuppliesvendors as $infosuppliesvendor) 
                @if($infosuppliesvendor -> VENDOR_NAME == $infocheckreceive -> RECEIVE_ACCEPT_FROM)
                <option value="{{ $infosuppliesvendor -> VENDOR_NAME }}" selected>{{ $infosuppliesvendor -> VENDOR_NAME }}</option>                                          
                @else
                <option value="{{ $infosuppliesvendor -> VENDOR_NAME }}" >{{ $infosuppliesvendor -> VENDOR_NAME }}</option>                                          
                @endif
               
             @endforeach  
        </select> 
        </div>

        <div class="col-lg-2" style="text-align: left">
            <label >                           
                                วันที่ตรวจรับเข้าคลัง :              
            </label>
            </div> 
            <div class="col-lg-2 text-left">
          
            <input name="RECEIVE_CHECKSTOR_DATE" id="RECEIVE_CHECKSTOR_DATE" class="form-control input-sm datepicker" style=" font-family: 'Kanit', sans-serif;font-size: 13px;"value="{{formate($infocheckreceive->RECEIVE_CHECKSTOR_DATE)}}" readonly>
    
            </div> 
            <div class="col-lg-1">
            <label style="text-align: left">                           
                                เวลา :              
            </label>
            </div> 
            <div class="col-lg-1 text-left">
            
            <input type="text" name="RECEIVE_CHECK_TIME" id="RECEIVE_CHECK_TIME" class="form-control js-masked-time  input-sm" style=" font-family: 'Kanit', sans-serif;font-size: 13px;" value="{{$infocheckreceive->RECEIVE_CHECK_TIME}}">
            </div> 
            </div>
         
     
        
       </div>
       <br>
       <div class="row">
       
        <div class="col-lg-1" style="text-align: left">
        <label>ประเภทวัสดุ :</label>
        </div> 
        <div class="col-lg-5">
       
       
        <select name="RECEIVE_SUP_TYPE" id="RECEIVE_SUP_TYPE"
            class="form-control input-sm js-example-basic-single"
            style=" font-family: 'Kanit', sans-serif;" required>
                <option value="" selected>--กรุณาเลือกวัสดุครุภัณฑ์--</option>
            @foreach ($suppliestypes as $suppliestype)
                 @if($suppliestype->SUP_TYPE_ID == $infocheckreceive->RECEIVE_SUP_TYPE)
                 <option value="{{ $suppliestype -> SUP_TYPE_ID }}" selected>{{ $suppliestype -> SUP_TYPE_NAME }}</option>
                 @else
                <option value="{{ $suppliestype -> SUP_TYPE_ID }}">{{ $suppliestype -> SUP_TYPE_NAME }}</option>
                @endif
            @endforeach
        </select>
       
       
       
        </div>
        <div class="col-lg-2 " style="text-align: left">
            <label>ปีงบประมาณ :</label>
            </div> 
            <div class="col-lg-2 text-left">
           
            <select name="RECEIVE_BUDGET_YEAR" id="RECEIVE_BUDGET_YEAR" class="form-control input-sm " style=" font-family: 'Kanit', sans-serif;" >
                                @foreach ($infobudgetyears as $infobudgetyear) 
    
                               
                                 @if($infobudgetyear -> LEAVE_YEAR_ID == $infocheckreceive->RECEIVE_BUDGET_YEAR)
                                 <option value="{{ $infobudgetyear -> LEAVE_YEAR_ID }}" selected>{{ $infobudgetyear -> LEAVE_YEAR_ID }}</option>                                          
                                 @else
                                 <option value="{{ $infobudgetyear -> LEAVE_YEAR_ID }}" >{{ $infobudgetyear -> LEAVE_YEAR_ID }}</option>                                          
                                 @endif 
                                       
                                @endforeach  
                    </select> 
            
    
            </div>
    </div>
       <br>
 
        


       <div class="row">
                        <div class="col-sm-12 row"  align="right">
                                    <div class="col-sm-7"></div> <div class="col-sm-1"><label>รวมมูลค่า </div><div class="col-sm-3 text-left"><input class="form-control input-sm " style="text-align:  right;background-color:#E0FFFF ;font-size: 16px;" type="text" name="total" id="total" value="{{number_format($infocheckreceive->RECEIVE_VALUE,5)}}" readonly></div><div class="col-sm-1"><label>  บาท</label></div>
                                        </div><br>
                                <!-- Block Tabs Default Style -->
                                <div class="block block-rounded block-bordered">
                                <ul class="nav nav-tabs nav-tabs-info" data-toggle="tabs" role="tablist" style="background-color: #FFEBCD;">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#object1" style="font-family: 'Kanit', sans-serif; font-size:14px;font-weight:normal;">วัสดุรับเข้า</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#object2" style="font-family: 'Kanit', sans-serif; font-size:14px;font-weight:normal;">กรรมการตรวจรับ</a>
                                    </li>


                                  
                                </ul>
                                <div class="block-content tab-content">
                                    <div class="tab-pane active" id="object1" role="tabpanel">
                                      
                                    <table class="table-striped table-vcenter js-dataTable-simple" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <td style="text-align: center; font-size: 13px;">ลำดับ</td>
                                                <td style="text-align: center; font-size: 13px;" width="20%">รายการรับเข้า</td>
                                                <td style="text-align: center; font-size: 13px;" width="10%">ประเภท</td>
                                                <td style="text-align: center; font-size: 13px;"  width="10%">หน่วย</td>
                                                <td style="text-align: center; font-size: 13px;" >จำนวนรับ</td>
                                                <td style="text-align: center; font-size: 13px;" >ราคาต่อหน่วย</td>
                                                <td style="text-align: center; font-size: 13px;" width="10%">มูลค่า</td>
                                                <td style="text-align: center; font-size: 13px;" >ล็อตผลิต</td>
                                                <td style="text-align: center; font-size: 13px;" >วันที่ผลิต</td>
                                                <td style="text-align: center; font-size: 13px;" >วันที่หมดอายุ</td>
                                                <td style="text-align: center; font-size: 13px;" width="5%"><a  class="btn btn-success addRow" style="color:#FFFFFF;"><i class="fa fa-plus"></i></a></td>
                                            </tr>
                                        </thead> 
                                        <tbody class="tbody1"> 
                                        
                                        <?php $count=1;$number=0;?>
                                            @foreach ($infocheckreceivesubs as $infocheckreceivesub)
                                            
                                            <tr>
                                        <td style="text-align: center;">
                                        {{$count}}
                                        </td>
                                        <td>

                                        <select name="RECEIVE_SUB_CODE[]" id="RECEIVE_SUB_CODE{{$number}}" class="form-control input-sm js-example-basic-single" style=" font-family: 'Kanit', sans-serif;" onchange="checkunitref({{$number}})">
                                        
                                        <option value="" >--เลือกวัสดุ--</option>  
                                                 @foreach ($infosuppliess as $infosupplies) 
                                                     @if($infosupplies -> ID == $infocheckreceivesub->RECEIVE_SUB_CODE)
                                                     <option value="{{ $infosupplies -> ID }}" selected>{{ $infosupplies -> SUP_NAME }}</option>                                          
                                                     @else 
                                                    <option value="{{ $infosupplies -> ID }}" >{{ $infosupplies -> SUP_NAME }}</option>                                          
                                                     @endif
                                                @endforeach  
                                        </select> 
                                       
                                       
                                        </td>
                                        <td>
                                        
                                        <select name="RECEIVE_SUB_TYPE[]" id="RECEIVE_SUB_TYPE[]" class="form-control input-sm " style=" font-family: 'Kanit', sans-serif;" >
                                                 @foreach ($infosuptypes as $infosuptype)  
                                                     @if($infosuptype -> ID_SUP_TYPE == $infocheckreceivesub->RECEIVE_SUB_TYPE)
                                                     <option value="{{ $infosuptype -> ID_SUP_TYPE }}" selected>{{ $infosuptype -> SUP_TYPE_NAME }}</option>                                          
                                                     @else
                                                     <option value="{{ $infosuptype -> ID_SUP_TYPE }}" >{{ $infosuptype -> SUP_TYPE_NAME }}</option>                                          
                                                     @endif
                                                    
                                                @endforeach  
                                        </select> 
                                        
                                        </td>
                                        <td ><div class="showunit{{$number}}">

                                        <select name="SUP_UNIT_ID[]" id="SUP_UNIT_ID[]" class="form-control input-sm " style=" font-family: 'Kanit', sans-serif;" >
                                        <option value="" >--เลือกหน่วย--</option>  
                                                 @foreach ($infosuppliesunitrefs as $infosuppliesunitref)  
                                               
                                                    @if($infosuppliesunitref -> ID == $infocheckreceivesub->SUP_UNIT_ID)
                                                    <option value="{{ $infosuppliesunitref -> ID }}" selected>{{ $infosuppliesunitref -> SUP_UNIT_NAME }}</option> 
                                                    @else
                                                    <option value="{{ $infosuppliesunitref -> ID }}" >{{ $infosuppliesunitref -> SUP_UNIT_NAME }}</option> 
                                                    @endif
                                                  
                                                
                                                                                            
                                                @endforeach  
                                        </select> 

                                        </div></td>
                                        <td>
                                        <input name="RECEIVE_SUB_AMOUNT[]" id="RECEIVE_SUB_AMOUNT{{$number}}" class="form-control input-sm" style=" font-family: 'Kanit', sans-serif;" value="{{$infocheckreceivesub->RECEIVE_SUB_AMOUNT}}" onkeyup="checksummoney({{$number}})">
                                        </td>
                                        <td>
                                        <input name="RECEIVE_SUB_PICE_UNIT[]" id="RECEIVE_SUB_PICE_UNIT{{$number}}" class="form-control input-sm" style=" font-family: 'Kanit', sans-serif;" value="{{$infocheckreceivesub->RECEIVE_SUB_PICE_UNIT}}" onkeyup="checksummoney({{$number}})">
                                        </td>
                                        <td>
                                        <div class="summoney{{$number}}"></div> 
                                        </td>
                                        <td>
                                            <?php if($infocheckreceivesub->RECEIVE_SUB_LOT <> '' && $infocheckreceivesub->RECEIVE_SUB_LOT <> null){$detallot = $infocheckreceivesub->RECEIVE_SUB_LOT; }else{ $detallot = 'L'.substr(date("Ymd"),2).'-'.date("His"); } ?>
                                        <input name="RECEIVE_SUB_LOT[]" id="RECEIVE_SUB_LOT[]" class="form-control input-sm" style=" font-family: 'Kanit', sans-serif;" value="{{$detallot}}" required>
                                        </td>
                                        <td>
                                         @if($infocheckreceivesub->RECEIVE_SUB_GEN_DATE !== '' && $infocheckreceivesub->RECEIVE_SUB_GEN_DATE !== null)
                                        <input name="RECEIVE_SUB_GEN_DATE[]" id="RECEIVE_SUB_GEN_DATE[]" class="form-control input-sm datepicker" style=" font-family: 'Kanit', sans-serif;" value="{{ formate($infocheckreceivesub->RECEIVE_SUB_GEN_DATE)}}" readonly>
                                         @else
                                        <input name="RECEIVE_SUB_GEN_DATE[]" id="RECEIVE_SUB_GEN_DATE[]" class="form-control input-sm datepicker" style=" font-family: 'Kanit', sans-serif;" readonly>
                                         @endif
                                        </td>
                                        <td>
                                        @if($infocheckreceivesub->RECEIVE_SUB_EXP_DATE !== '' && $infocheckreceivesub->RECEIVE_SUB_EXP_DATE !== null)
                                        <input name="RECEIVE_SUB_EXP_DATE[]" id="RECEIVE_SUB_EXP_DATE[]" class="form-control input-sm datepicker" style=" font-family: 'Kanit', sans-serif;" value="{{ formate($infocheckreceivesub->RECEIVE_SUB_EXP_DATE)}}" readonly>
                                        @else
                                        <input name="RECEIVE_SUB_EXP_DATE[]" id="RECEIVE_SUB_EXP_DATE[]" class="form-control input-sm datepicker" style=" font-family: 'Kanit', sans-serif;" readonly>
                                        @endif
                                        </td>
                                    
                                        <td style="text-align: center;"><a class="btn btn-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt"></i></a></td>
                                        <input type="hidden" name="SUP_UNIT_ID_H[]" id="SUP_UNIT_ID_H{{$number}}" value="{{$number}}">
                                    </tr>
                                        <?php  $count++;?>
                                        <?php $number++;?>
                                            @endforeach   
                                    </tbody>   
                                </table>
                            </div>



                            <div class="tab-pane" id="object2" role="tabpanel">
                                      
                                      <table class="table gwt-table" >
                                          <thead>
                                              <tr>
                                                 
                                                  <td style="text-align: center; font-size: 13px; border: 1px solid black;" width="1000px">ชื่อกรรมการ</td>
                                                  <td style="text-align: center; font-size: 13px; border: 1px solid black;" width="400px">ตำแหน่ง</td>
                                          
                                                  <td style="text-align: center; font-size: 13px; border: 1px solid black;" width="12%"><a  class="btn btn-success addRow2" style="color:#FFFFFF;"><i class="fa fa-plus"></i></a></td>
                                              </tr>
                                          </thead> 
                                          <tbody class="tbody2"> 
                                          
  
                                      <tr>
                                        
                                          <td>
                                         
                                            <select name="RECEIVE_BOARD_PERSON_ID[]" id="RECEIVE_BOARD_PERSON_ID[]" class="form-control input-sm js-example-basic-single" style=" font-family: 'Kanit', sans-serif;" >
                                                    <option value="" >--เลือกกรรมการ--</option>                                          
                                                    @foreach ($infopersons as $infoperson) 
                                                    <option value="{{ $infoperson -> ID }}" >{{ $infoperson -> HR_FNAME }} {{ $infoperson -> HR_LNAME }}</option>                                          
                                                    @endforeach  
                                            </select> 
                                          </td>
                                          <td>
                                        
                                          
                                          <select name="RECEIVE_BOARD_POSITION_ID[]" id="RECEIVE_BOARD_POSITION_ID[]" class="form-control input-sm " style=" font-family: 'Kanit', sans-serif;" >
                                                    <option value="" >--เลือกตำแหน่ง--</option>                                          
                                                  
                                                    <option value="1" >กรรมการ</option>    
                                                    <option value="2" >ประธาน</option>                                          
                                                
                                            </select> 
                                          
                                          
                                          </td>
                                      
                                          <td style="text-align: center;"><a class="btn btn-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt "></i></a></td>
                                      </tr>
                                    

                               
                                      
                    
                              
                                      </tbody>   
                                      </table>
  
                                      </div>
                        <br>
                                                            </div>
                                                        </div>
                                                
                            
                        </div>
                        
                      

        <div class="modal-footer">
                <div align="right">
                        <button type="submit" class="btn btn-hero-sm btn-hero-info" style="font-family: 'Kanit', sans-serif;" <i class="fas fa-save"></i> บันทึกข้อมูล</button>
                        <a href="{{ url('manager_warehouse/detail')  }}" style="font-family: 'Kanit', sans-serif;" onclick="return confirm('ต้องการที่จะยกเลิกการแก้ไขข้อมูล ?')" class="btn btn-hero-sm btn-hero-danger"  >ยกเลิกการแก้ไข</a>
                </div>       
       
    </div>
</div>
    </form>  


       
       
         
               
                      

@endsection

@section('footer')

<script src="{{ asset('select2/select2.min.js') }}"></script>

<script src="{{ asset('asset/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script>
<script>jQuery(function(){ Dashmix.helpers(['masked-inputs']); });</script>

<script src="{{ asset('datepicker/dist/js/bootstrap-datepicker-custom.js') }}"></script>
<script src="{{ asset('datepicker/dist/locales/bootstrap-datepicker.th.min.js') }}" charset="UTF-8"></script>
<script>

function run01(){
    var count = $('.tbody1').children('tr').length;
    //alert(count);
    var number;
        for (number = 0; number < count; number++) { 
            checkunitref(number);
            checksummoney(number);
        }

}

$(document).ready(function() {
    $('.js-example-basic-single').select2();
});

function checkunitref(number){
      
    
      var SUP_ID=document.getElementById("RECEIVE_SUB_CODE"+number).value;
      var SUP_UNIT_ID_H=document.getElementById("SUP_UNIT_ID_H"+number).value;
      
     
      
      var _token=$('input[name="_token"]').val();
           $.ajax({
                   url:"{{route('msupplies.checkunitref')}}",
                   method:"GET",
                   data:{SUP_ID:SUP_ID,SUP_UNIT_ID_H:SUP_UNIT_ID_H,_token:_token},
                   success:function(result){
                      $('.showunit'+number).html(result);
                   }
           })

  }


    $(document).ready(function () {
                
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    todayBtn: true,
                    language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                    thaiyear: true,
                    autoclose: true                    //Set เป็นปี พ.ศ.
                });  //กำหนดเป็นวันปัจุบัน
        });
        </script>
<script>
 

 function datepicker(number){
        
        $('.datepicker'+number).datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true,
                autoclose: true                         //Set เป็นปี พ.ศ.
            }).datepicker("setDate", 0);  //กำหนดเป็นวันปัจุบัน
   }

$('.addRow').on('click',function(){
        addRow();
        var count = $('.tbody1').children('tr').length;
        var number =  (count).valueOf();
        datepicker(number);
        $('.js-example-basic-single').select2();
    });

    function addRow(){
    var count = $('.tbody1').children('tr').length;
    var number =  (count + 1).valueOf();


    var today = new Date();
    var date = String(today.getFullYear()).substring(2)+''+String(today.getMonth()+1).padStart(2, '0')+''+String(today.getDate()).padStart(2, '0');
    var time = String(today.getHours()).padStart(2, '0') + "" + String(today.getMinutes()).padStart(2, '0') + "" + String(today.getSeconds()).padStart(2, '0');;
    var dateTime = 'L'+date+'-'+time;
  
         var tr =   '<tr>'+
                '<td style="text-align: center;">'+
                +number+
                '</td>'+
                '<td>'+
                '<select name="RECEIVE_SUB_CODE[]" id="RECEIVE_SUB_CODE'+count+'" class="form-control input-sm js-example-basic-single" style=" font-family: \'Kanit\', sans-serif;" onchange="checkunitref('+count+')">'+                   
                '<option value="" >--เลือกวัสดุ--</option>'+  
                '@foreach ($infosuppliess as $infosupplies)'+  
                '<option value="{{ $infosupplies -> ID }}" >{{ $infosupplies -> SUP_NAME }}</option>'+                                          
                 '@endforeach'+  
                '</select>'+
                '</td>'+
                '<td>'+         
                '<select name="RECEIVE_SUB_TYPE[]" id="RECEIVE_SUB_TYPE[]" class="form-control input-sm " style=" font-family: \'Kanit\', sans-serif;" >'+
                '@foreach ($infosuptypes as $infosuptype)'+  
                '<option value="{{ $infosuptype -> ID_SUP_TYPE }}" >{{ $infosuptype -> SUP_TYPE_NAME }}</option>'+                                          
                '@endforeach'+  
                '</select>'+   
                '</td>'+
                '<td><div class="showunit'+count+'">'+
                '<select name="SUP_UNIT_ID[]" id="SUP_UNIT_ID[]" class="form-control input-sm " style=" font-family: \'Kanit\', sans-serif;" >'+
                '<option value="" >--เลือกหน่วย--</option>'+  
                ' @foreach ($infosuppliesunitrefs as $infosuppliesunitref)'+                       
                '<option value="{{ $infosuppliesunitref -> ID }}" >{{ $infosuppliesunitref -> SUP_UNIT_NAME }}</option>'+                                                                          
                '@endforeach'+  
                '</select>'+ 
                '</div></td>'+
                '<td>'+
                '<input name="RECEIVE_SUB_AMOUNT[]" id="RECEIVE_SUB_AMOUNT'+number+'" class="form-control input-sm" style=" font-family: \'Kanit\', sans-serif;" onkeyup="checksummoney('+number+');">'+
                '</td>'+
                '<td>'+
                '<input name="RECEIVE_SUB_PICE_UNIT[]" id="RECEIVE_SUB_PICE_UNIT'+number+'" class="form-control input-sm" style=" font-family: \'Kanit\', sans-serif;" onkeyup="checksummoney('+number+');">'+
                '</td>'+
                '<td>'+
                 '<div class="summoney'+number+'"></div>'+
                '</td>'+
                '<td>'+
                '<input name="RECEIVE_SUB_LOT[]" id="RECEIVE_SUB_LOT[]" class="form-control input-sm" style=" font-family: \'Kanit\', sans-serif;" value="'+dateTime+'"required>'+
                '</td>'+
                '<td>'+
                '<input name="RECEIVE_SUB_GEN_DATE[]" id="RECEIVE_SUB_GEN_DATE[]" class="form-control input-sm datepicker'+number+'" style=" font-family: \'Kanit\', sans-serif;" readonly>'+
                '</td>'+
                '<td>'+
                '<input name="RECEIVE_SUB_EXP_DATE[]" id="RECEIVE_SUB_EXP_DATE[]" class="form-control input-sm datepicker'+number+'" style=" font-family: \'Kanit\', sans-serif;" readonly>'+
                '</td>'+                  
                '<td style="text-align: center;"><a class="btn btn-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt"></i></a></td>'+
                '<input type="hidden" name="SUP_UNIT_ID_H[]" id="SUP_UNIT_ID_H'+count+'" value="0">'+
                '</tr>';
    $('.tbody1').append(tr);
    };

    $('.tbody1').on('click','.remove', function(){
        $(this).parent().parent().remove();
});



$('.addRow2').on('click',function(){
        addRow2();
        $('.js-example-basic-single').select2();
    });

    function addRow2(){
    var count = $('.tbody2').children('tr').length;

        var tr =  '<tr>'+                    
        '<td>'+              
        '<select name="RECEIVE_BOARD_PERSON_ID[]" id="RECEIVE_BOARD_PERSON_ID[]" class="form-control input-sm js-example-basic-single" style=" font-family: \'Kanit\', sans-serif;" >'+ 
        '<option value="" >--เลือกกรรมการ--</option>'+                                           
        '@foreach ($infopersons as $infoperson)'+  
        '<option value="{{ $infoperson -> ID }}" >{{ $infoperson -> HR_FNAME }} {{ $infoperson -> HR_LNAME }}</option>'+                                           
        '@endforeach'+   
        '</select>'+  
        '</td>'+ 
        '<td>'+                                
        '<select name="RECEIVE_BOARD_POSITION_ID[]" id="RECEIVE_BOARD_POSITION_ID[]" class="form-control input-sm " style=" font-family: \'Kanit\', sans-serif;" >'+ 
        '<option value="" >--เลือกตำแหน่ง--</option>'+                                           
        '<option value="1" >กรรมการ</option>'+     
        '<option value="2" >ประธาน</option>'+                                                  
        '</select>'+               
        '</td>'+ 
        '<td style="text-align: center;"><a class="btn btn-danger remove" style="color:#FFFFFF;"><i class="fa fa-trash-alt"></i></a></td>'+ 
        '</tr>';
    $('.tbody2').append(tr);
    };

    $('.tbody2').on('click','.remove', function(){
        $(this).parent().parent().remove();
});
</script>
<script> 
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



//-----------------------------------------------------



function checksummoney(number){
      
    
      var SUP_TOTAL=document.getElementById("RECEIVE_SUB_AMOUNT"+number).value;
      var PRICE_PER_UNIT=document.getElementById("RECEIVE_SUB_PICE_UNIT"+number).value;
      
     // alert(SUP_TOTAL);
      
      var _token=$('input[name="_token"]').val();
           $.ajax({
                   url:"{{route('msupplies.checksummoney')}}",
                   method:"GET",
                   data:{SUP_TOTAL:SUP_TOTAL,PRICE_PER_UNIT:PRICE_PER_UNIT,_token:_token},
                   success:function(result){
                      $('.summoney'+number).html(result);
                      findTotal();
                   }
           })
           
  }


  function numberWithCommas(nStr) {
    nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
}

  function findTotal(){
    var arr = document.getElementsByName('sum');
    var tot=0;

    count = $('.tbody1').children('tr').length;
    for(var i=0;i<count;i++){
            tot += parseFloat(arr[i].value);
    }

    number = tot.toFixed(5);

    document.getElementById('total').value = numberWithCommas(number);


}




</script>


@endsection
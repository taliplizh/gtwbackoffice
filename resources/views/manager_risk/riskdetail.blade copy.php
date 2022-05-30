@extends('layouts.risk')   
    <link href="{{ asset('datepicker/dist/css/bootstrap-datepicker.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('asset/js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
@section('content')

<style>
    .center {
    margin: auto;
    width: 100%;
    padding: 10px;
    }
    body {
      font-family: 'Kanit', sans-serif;
      font-size: 10px;
      font-size: 1.0rem;
      }

      label{
            font-family: 'Kanit', sans-serif;
            font-size: 10px;
            font-size: 1.0rem;
      } 

      @media only screen and (min-width: 1200px) {
    label {
    float:right;
  }

      }
      
      .text-pedding{
   padding-left:10px;
                    }

        .text-font {
    font-size: 13px;
                  }   
      
      
      .form-control{
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

   
    use App\Http\Controllers\MeetingController;
    $checkver = MeetingController::checkver($user_id);
    $countver = MeetingController::countver($user_id);
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
    
    function Removeformatetime($strtime)
    {
    $H = substr($strtime,0,5);
    return $H;
    }  
?>          

<center>    
    <div class="block mt-5" style="width: 95%;">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <div align="left">
                    <h2 class="block-title" style="font-family: 'Kanit', sans-serif;">รายงานความเสี่ยง</h2> 
                </div>
                 
                <div align="right">
                    <a href="{{ url('manager_risk/detail_add')}}"  class="btn btn-hero-sm btn-hero-info foo15" ><i class="fas fa-plus"></i> เพิ่มรายงานความเสี่ยง</a>
                    </div>
                </div>
                <div class="block-content block-content-full">
            <form action="{{route('mrisk.detail_search')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-0.5">
                        &nbsp;&nbsp; &nbsp;&nbsp; ปีงบ &nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="col-sm-1.5">
                        <span>
                                <select name="BUDGET_YEAR" id="BUDGET_YEAR" class="form-control input-lg budget" style=" font-family: 'Kanit', sans-serif;">
                                @foreach ($budgets as $budget)
                                @if($budget->LEAVE_YEAR_ID== $year_id)
                                    <option value="{{ $budget->LEAVE_YEAR_ID  }}" selected>{{ $budget->LEAVE_YEAR_ID}}</option>
                                @else
                                    <option value="{{ $budget->LEAVE_YEAR_ID  }}">{{ $budget->LEAVE_YEAR_ID}}</option>
                                @endif                                 
                            @endforeach                         
                                </select>
                            </span>
                        </div>

            <div class="col-sm-4 date_budget">
            <div class="row">
                        <div class="col-sm">
                        วันที่
                        </div>
                    <div class="col-md-4">
             
                    <input  name = "DATE_BIGIN"  id="DATE_BIGIN"  class="form-control input-lg datepicker" data-date-format="mm/dd/yyyy"  value="{{ formate($displaydate_bigen) }}" readonly>
                    
                    </div>
                    <div class="col-sm">
                        ถึง 
                        </div>
                    <div class="col-md-4">
           
                    <input  name = "DATE_END"  id="DATE_END"  class="form-control input-lg datepicker" data-date-format="mm/dd/yyyy"  value="{{ formate($displaydate_end) }}" readonly>
                  
                    </div>
                    </div>

                </div>


<div class="col-sm-0.5">
&nbsp;ค้นหา &nbsp;
</div>

<div class="col-sm-2">
<span>

<input type="search"  name="search" class="form-control" style="font-family: 'Kanit', sans-serif;font-weight:normal;" value="{{$search}}">

</span>
</div>

<div class="col-sm-30">
&nbsp;
</div> 
<div class="col-sm-1.5">
<span>
<button type="submit" class="btn btn-hero-sm btn-hero-info foo15" ><i class="fas fa-search mr-2"></i> ค้นหา</button>
</span> 
</div>


              
                  </div>  
             </form>
             <div class="table-responsive"> 
                <table class="gwt-table table-striped table-vcenter js-dataTable-simple" style="width: 100%;">
                    <thead style="background-color: #C39BD3;">
                        <tr height="40">
                            <th class="text-font" style="text-align: center;" width="4">ลำดับ</th>
                            <th class="text-font" style="text-align: center;"width="7%">สถานะ</th>
                            <th class="text-font" style="text-align: center;"width="7%">ทบทวน</th>
                            <th class="text-font" style="text-align: center;"width="7%">ความรุนแรง</th>
                            <th class="text-font" style="text-align: center;" width="8%" >วันที่เกิดเหตุ</th>
                            <th class="text-font" style="text-align: center;"width="15%" >ที่มา</th>
                            <th class="text-font" style="text-align: center;"  width="10%">เรื่อง</th>
                            <th class="text-font" style="text-align: center;" width="10%">รายละเอียดเหตุการณ์</th>
                            <th class="text-font" style="text-align: center;">การจัดการเบื้องต้น</th>
                            <th class="text-font" style="text-align: center;" width="10%">หน่วยงาน</th>
                            <th class="text-font" style="text-align: center;" width="8%">วันที่รายงาน</th>
                            <th class="text-font" style="text-align: center;" width="5%" >คำสั่ง</th> 
                        </tr >
                    </thead>
                    <tbody>
                    <?php $number = 0; ?>
                        @foreach ($rigreps as $rigrep)
                            <?php $number++; 
                                $status =  $rigrep -> RISKREP_STATUS;                                      
                                        
                                if( $status === 'CONFIRM'){
                                        $statuscol =  "badge badge-primary";
                                    }else if($status === 'REPORT'){
                                    $statuscol =  "badge badge-warning";

                                    }else if($status === 'ACCEPT'){
                                    $statuscol =  "badge badge-danger";

                                    }else if($status === 'CHECK'){
                                        $statuscol =  "badge badge-info";
                                    }else if($status === 'SUCCESS'){
                                        $statuscol =  "badge badge-success";
                                    }else{
                                        $statuscol =  "badge badge-secondary";
                                    } 
                                    
                                    $level = $rigrep->RISKREP_LEVEL;

                                        if ($level === '1') {
                                        $statuslevel = 'badge badge-danger';
                                        } elseif ($level === '2') {
                                        $statuslevel = 'badge badge-warning';
                                        } elseif ($level === '3') {
                                        $statuslevel = 'badge badge-danger';
                                        } elseif ($level === '4') {
                                        $statuslevel = 'badge badge-info';
                                        } elseif ($level === '5') {
                                        $statuslevel = 'badge badge-success';
                                        } elseif ($level === '6') {
                                        $statuslevel = 'badge badge-secondary';                                  
                                        } elseif ($level === '7') {
                                        $statuslevel = 'badge badge-info';
                                        } elseif ($level === '8') {
                                        $statuslevel = 'badge badge-success';
                                        } elseif ($level === '9') {
                                        $statuslevel = 'badge badge-secondary';
                                        } elseif ($level === '10') {
                                        $statuslevel = 'badge badge-primary';
                                        } elseif ($level === '11') {
                                        $statuslevel = 'badge badge-secondary';
                                        } elseif ($level === '12') {
                                        $statuslevel = 'badge badge-info';
                                        } elseif ($level === '13') {
                                        $statuslevel = 'badge badge-success';
                                        } elseif ($level === '14') {
                                        $statuslevel = 'badge badge-secondary';
                                        } else {
                                        $statuslevel = '';
                                        }
                                ?>
                            <tr height="20">
                                <td class="text-font" style="text-align: center;font-size: 14px;font-weight:normal;" width="4">{{ $number}}</td>
                                <td align="center"> <span class="{{$statuscol}}">{{ $rigrep -> RISK_STATUS_NAME_TH }}</span></td>
                                <td align="center">
                                        <a href="" class="btn btn-hero-sm btn-hero-warning"><i class="fa fa-file-signature"></i></a>
                                </td>
                                <td align="center"> <span class="{{ $statuslevel }}" width="8%">{{ $rigrep->RISK_REP_LEVEL_NAME }}</span></td>
                                {{-- <td class="text-font text-pedding" style="text-align: center;" width="7%">{{$rigrep->RISK_REP_LEVEL_NAME}}</td> --}}
                                <td class="text-font text-pedding" style="text-align: center;" width="8%">{{DateThai($rigrep->RISKREP_STARTDATE)}}</td>
                                <td class="text-font text-pedding" style="text-align: left;" width="10%">{{$rigrep->INCIDENCE_LOCATION_NAME}}</td>
                                <td class="text-font text-pedding" style="text-align: left;" >{{$rigrep->RISK_REPITEMS_NAME}}</td>                                   
                                <td class="text-font text-pedding" >{!!$rigrep->RISKREP_DETAILRISK!!}</td>
                                <td class="text-font text-pedding" style="text-align: left;" >{!!$rigrep->RISKREP_BASICMANAGE!!}</td>
                                <td class="text-font text-pedding" style="text-align: left;" width="10%">{{$rigrep->HR_DEPARTMENT_SUB_SUB_NAME}}</td>
                                <td class="text-font text-pedding " style="text-align: center;" width="8%">{{DateThai($rigrep->RISKREP_DATESAVE)}}</td>

                            
                                <td align="center" width="5%">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-outline-info dropdown-toggle" id="dropdown-align-outline-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-family: 'Kanit', sans-serif; font-size: 12px;font-weight:normal;">
                                            ทำรายการ
                                        </button>
                                        <div class="dropdown-menu fo13" style="width:10px">      
                                                
                                                <a class="dropdown-item" href="{{ url('manager_risk/detail_detail/'.$rigrep->RISKREP_ID) }}" style="font-family:'Kanit',sans-serif;font-size:13px;">รายละเอียด</a> 
                                                {{-- <a class="dropdown-item"  href="{{ url('manager_risk/risk_notify_repeat/'.$rigrep->RISKREP_ID) }}" style="font-family:'Kanit',sans-serif;font-size:13px;">ทบทวน</a>  --}}
                                                {{-- <a class="dropdown-item"  href="{{ url('manager_risk/risk_notify_accept/'.$rigrep->RISKREP_ID) }}" style="font-family:'Kanit',sans-serif;font-size:13px;">ตอบกลับ</a>  --}}
                                                <a class="dropdown-item" href="{{ url('manager_risk/detail_edit/'.$rigrep->RISKREP_ID)}}">แก้ไขข้อมูล</a>
                                                <a class="dropdown-item" href="{{ url('manager_risk/detail_check/'.$rigrep->RISKREP_ID)}}">ตรวจสอบข้อมูล</a>
                                                <a class="dropdown-item" href="{{ url('manager_risk/detail_edit/'.$rigrep->RISKREP_ID)}}">ยืนยันความเสี่ยง</a>
                                                <a class="dropdown-item" href="{{ url('manager_risk/detail_edit/'.$rigrep->RISKREP_ID)}}">พิมพ์ใบอุบัติการณ์</a>
                                                {{-- <a class="dropdown-item"  href="{{ url('manager_risk/detail_cancel/'.$rigrep->RISKREP_ID)}}"  style="font-family:'Kanit',sans-serif;font-size:13px;">ยกเลิก</a> --}}
                                                {{-- <a class="dropdown-item"  href="{{ url('manager_risk/detail_destroy/'.$rigrep->RISKREP_ID)}}" onclick="return confirm('ต้องการที่จะยกเลิกการลบข้อมูล ?')" style="font-family:'Kanit',sans-serif;font-size:13px;">ลบ</a> --}}
                                            </div>
                                        </div>
                                </td>                                    
                            </tr>
                        @endforeach     

                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</div>

  
@endsection

@section('footer')

<script src="{{ asset('datepicker/bootstrap-3.3.7-dist/js/bootstrap.js') }}"></script>
    <script src="{{ asset('datepicker/dist/js/bootstrap-datepicker-custom.js') }}"></script>
    <script src="{{ asset('datepicker/dist/locales/bootstrap-datepicker.th.min.js') }}" charset="UTF-8"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('asset/js/plugins/easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Page JS Code -->
    <script src="{{ asset('asset/js/pages/be_comp_charts.min.js') }}"></script>
    <script>jQuery(function(){ Dashmix.helpers(['easy-pie-chart', 'sparkline']); });</script>

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

function detail(id){

$.ajax({
           url:"{{route('suplies.detailapp')}}",
          method:"GET",
           data:{id:id},
           success:function(result){
               $('#detail').html(result);
             
         
              //alert("Hello! I am an alert box!!");
           }
            
   })
    
}

    $(document).ready(function () {

$('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    todayBtn: true,
    language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
    thaiyear: true,
    autoclose: true                         //Set เป็นปี พ.ศ.
});  //กำหนดเป็นวันปัจุบัน
});

$('.budget').change(function(){
 if($(this).val()!=''){
 var select=$(this).val();
 var _token=$('input[name="_token"]').val();
 $.ajax({
         url:"{{route('admin.selectbudget')}}",
         method:"GET",
         data:{select:select,_token:_token},
         success:function(result){
            $('.date_budget').html(result);
            datepick();
         }
 })
// console.log(select);
 }        
});

function chkmunny(ele){
var vchar = String.fromCharCode(event.keyCode);
if ((vchar<'0' || vchar>'9' )&& (vchar != '.')) return false;
ele.onKeyPress=vchar;
}
</script>

@endsection
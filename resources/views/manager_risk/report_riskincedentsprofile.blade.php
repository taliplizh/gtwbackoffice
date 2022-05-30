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
                        }

            .text-font {
        font-size: 13px;
                    }   

                    .text-pedding {
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 10px;
            
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
<!-- Advanced Tables -->
<br>
<br>
<center>    
    <div class="block" style="width: 95%;">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title" style="font-family: 'Kanit', sans-serif;"><B>รายงานการบริหารจัดการความเสี่ยงขององค์กร/หน่วยงาน(Risk Incidents Profile)</B></h3>   
                <div align="right ">
                    <a href="{{ url('manager_risk/report_riskincedentsprofile_excel')}}"  class="btn btn-success btn-lg" style="font-family: 'Kanit', sans-serif; font-size: 13px;font-weight:normal;" ><li class="fa fa-file-excel"></li>&nbsp;Excel</a>
                    <a class="btn btn-success" href="{{url('manager_risk/report')}}" style="font-family:'Kanit',sans-serif;font-size:14px;font-weight:normal;"><i class="fas fa-chevron-circle-left text-white-70" style="font-size:18px;"></i>&nbsp;&nbsp;ย้อนกลับ</a> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;         
                  </div>
                </div>
                {{-- <div class="block-content block-content-full">  
                <div class="row push">
                    <div class="col-sm-1 text-right" >
                        <label style="font-family:'Kanit',sans-serif;font-size:14px;">รายงานโดยใช้ :</label>
                    </div> 
                    <div class="col-lg-2 ">
                        <select name="SETUP_INCEDENCE_REPORTHEADER_NAME" id="SETUP_INCEDENCE_REPORTHEADER_NAME" class="form-control input-lg " style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;" >
                        <option value="" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">--เลือก--</option>
                            @foreach($reportheaders as $reportheader)
                                <option value="{{ $reportheader-> SETUP_INCEDENCE_REPORTHEADER_ID}}" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">{{ $reportheader-> SETUP_INCEDENCE_REPORTHEADER_NAME}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-0.5 text-left">
                        <label >ตั้งแต่</label>  
                    </div>
                    <div class="col-lg-2 ">
                        <select name="MONTH_NAME_1" id="MONTH_NAME_1" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                        <option value="" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">--เลือก--</option>
                            @foreach($mounts as $mount)
                                <option value="{{ $mount-> MONTH_ID}}" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">{{ $mount-> MONTH_NAME}}</option>
                            @endforeach
                        </select>                                                      
                    </div>    
                    <div class="col-sm-0.5 text-left">
                        <label for="validationServer01">ปี</label> 
                    </div> 
                    <div class="col-lg-2 ">
                    <select name="LEAVE_YEAR_ID_1" id="LEAVE_YEAR_ID_1" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                        <option value="" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">--เลือก--</option>
                            @foreach($budgets as $budget)
                                <option value="{{ $budget-> LEAVE_YEAR_ID}}" selected style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">{{ $budget-> LEAVE_YEAR_ID}}</option>
                            @endforeach
                        </select>                                                    
                    </div>                                                  
                    <div class="col-sm-0.5 text-left">
                        <label for="validationServer01">เดือน</label>  
                    </div> 
                    <div class="col-lg-2 ">                                  
                    <select name="MONTH_NAME_2" id="MONTH_NAME_2" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                        <option value="" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">--เลือก--</option>
                            @foreach($mounts as $mount)
                                <option value="{{ $mount-> MONTH_ID}}" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">{{ $mount-> MONTH_NAME}}</option>
                            @endforeach
                        </select>  
                    </div>
                    <div class="col-sm-0.5 text-left">
                        <label for="validationServer01">ปี</label> 
                    </div> 
                    <div class="col-lg-2 "> 
                    <select name="LEAVE_YEAR_ID_2" id="LEAVE_YEAR_ID_2" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                    <option value="" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">--เลือก--</option>
                            @foreach($budgets as $budget)
                                <option value="{{ $budget-> LEAVE_YEAR_ID}}" selected>{{ $budget-> LEAVE_YEAR_ID}}</option>
                            @endforeach
                        </select>                                                       
                    </div>                              
                </div>
                
 
                    <div class="row push">
                        <div class="col-sm-1 text-right" >
                            <label style="font-family:'Kanit',sans-serif;font-size:14px;">หน่วยงาน :</label>
                        </div> 
                        <div class="col-lg-3 ">
                            <select name="HR_DEPARTMENT_SUB_SUB_NAME" id="HR_DEPARTMENT_SUB_SUB_NAME" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                            <option value="" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">--เลือก--</option>
                                @foreach($departsubs as $departsub)
                                    <option value="{{ $departsub-> HR_DEPARTMENT_SUB_SUB_ID}}" >{{ $departsub-> HR_DEPARTMENT_SUB_SUB_NAME}}</option>
                                @endforeach
                            </select>  
                        </div>
                  
                        <div class="col-sm-2 text-right" >
                            <label style="font-family:'Kanit',sans-serif;font-size:14px;">ประเภทหน่วยงาน :</label>
                        </div> 
                        <div class="col-lg-2 ">
                            <select name="CATEGORY_DEPARTMENT_NAME" id="CATEGORY_DEPARTMENT_NAME" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                            <option value="" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">--เลือก--</option>
                                    @foreach($categorydeparts as $categorydepart)
                                        <option value="{{ $categorydepart-> CATEGORY_DEPARTMENT_ID}}" >{{ $categorydepart-> CATEGORY_DEPARTMENT_NAME}}</option>
                                    @endforeach
                            </select> 
                        </div>
                      
                        <div class="col-sm-2 text-right" >
                            <label style="font-family:'Kanit',sans-serif;font-size:14px;">กลุ่มหน่วยงาน :</label>
                        </div> 
                        <div class="col-lg-2 ">
                        <select name="HR_DEPARTMENT_NAME" id="HR_DEPARTMENT_NAME" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                        <option value="" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">--เลือก--</option>
                                    @foreach($departs as $depart)
                                        <option value="{{ $depart-> HR_DEPARTMENT_ID}}" >{{ $depart-> HR_DEPARTMENT_NAME}}</option>
                                    @endforeach
                            </select>  
                        </div>                                                    
                    </div>
                                  
                    
                    <div class="row push"> 
                        <div class="col-sm-2" >
                            <label style="font-family:'Kanit',sans-serif;font-size:14px;">การรายงานอุบัติการณ์ :</label>
                        </div> 
                        <div class="col-md-2 ">
                            <select name="SETUP_INCEDENCE_REPORT_NAME" id="SETUP_INCEDENCE_REPORT_NAME" class="form-control input-lg " style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;" >
                            <option value="">--เลือก--</option>
                                @foreach($incidence_reports as $incidence_report)
                                    <option value="{{ $incidence_report-> SETUP_INCEDENCE_REPORT_ID}}" >{{ $incidence_report-> SETUP_INCEDENCE_REPORT_NAME}}</option>
                                @endforeach                                                                 
                            </select>
                        </div> 
                       
                        <div class="col-sm-2" >
                            <label style="font-family:'Kanit',sans-serif;font-size:14px;">ระดับความรุนแรง :</label>
                        </div> 
                        <div class="col-md-2 ">
                            <select name="INCIDENCE_LEVEL_NAME" id="INCIDENCE_LEVEL_NAME" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                            <option value="">--เลือก--</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level-> INCIDENCE_LEVEL_ID}}" >{{ $level-> INCIDENCE_LEVEL_NAME}}</option>
                                @endforeach
                            </select> 
                        </div> 
                        <div class="col-sm-2" >
                            <label style="font-family:'Kanit',sans-serif;font-size:14px;">การแก้ไขอุบัติการณ์ :</label>
                        </div> 
                        <div class="col-md-2 ">
                            <select name="SETUP_INCEDENCE_MODIFY_NAME" id="SETUP_INCEDENCE_MODIFY_NAME" class="form-control input-lg " style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;" >
                            <option value="">--เลือก--</option>
                                @foreach($incidence_modifys as $incidence_modify)
                                    <option value="{{ $incidence_modify-> SETUP_INCEDENCE_MODIFY_ID}}" >{{ $incidence_modify-> SETUP_INCEDENCE_MODIFY_NAME}}</option>
                                @endforeach                                                                    
                            </select>
                        </div> 
                        </div> 
                                
                                                
                       
                                <div class="row push"> 
                                    <div class="col-sm-2" >
                                        <label style="font-family:'Kanit',sans-serif;font-size:14px;">ประเภทสถานที่ :</label>
                                    </div> 
                                    <div class="col-md-2 ">
                                    <select name="SETUP_GROUPLOCATION_NAME" id="SETUP_GROUPLOCATION_NAME" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                                        <option value="">--เลือก--</option>
                                            @foreach($grouplocations as $grouplocation)
                                                <option value="{{ $grouplocation-> SETUP_GROUPLOCATION_ID}}" >{{ $grouplocation-> SETUP_GROUPLOCATION_NAME}}</option>
                                            @endforeach
                                        </select> 
                                    </div> 
                                
                                    <div class="col-sm-2" >
                                        <label style="font-family:'Kanit',sans-serif;font-size:14px;">ชนิดสถานที่ :</label>
                                    </div> 
                                    <div class="col-md-2 ">
                                        <select name="SETUP_TYPELOCATION_NAME" id="SETUP_TYPELOCATION_NAME" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                                        <option value="">--เลือก--</option>
                                            @foreach($typelocations as $typelocation)
                                                <option value="{{ $typelocation-> SETUP_TYPELOCATION_ID}}" >{{ $typelocation-> SETUP_TYPELOCATION_NAME}}</option>
                                            @endforeach
                                        </select> 
                                    </div> 
                                   
                                    <div class="col-sm-2" >
                                        <label style="font-family:'Kanit',sans-serif;font-size:14px;">สถานที่เกิดเหตุ :</label>
                                    </div> 
                                    <div class="col-md-2 ">
                                    <select name="INCIDENCE_ORIGIN_NAME" id="INCIDENCE_ORIGIN_NAME" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                                        <option value="">--เลือก--</option>
                                            @foreach($origins as $origin)
                                                <option value="{{ $origin-> INCIDENCE_ORIGIN_ID}}" >{{ $origin-> INCIDENCE_ORIGIN_NAME}}</option>
                                            @endforeach
                                        </select> 
                                    </div> 
                                    </div> 
                                <div class="row push"> 
                                <div class="col-sm-2" >
                                        <label style="font-family:'Kanit',sans-serif;font-size:14px;">ผู้ได้รับผลกระทบ :</label>
                                    </div> 
                                    <div class="col-md-2 ">
                                    <!-- <input value="{{$id_user}}" name="DONATE_PERSON_PROVINCE" id="PROVIDONATE_PERSON_PROVINCECE_NAME" class="form-control input-lg " style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;" readonly> -->
                                    <select name="INTERNALCONTROL_HEAD_WORK" id="INTERNALCONTROL_HEAD_WORK" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                                    <option value="">--เลือก--</option>
                                            @foreach($infopers as $infoper)
                                                <option value="{{ $infoper-> ID}}" >{{ $infoper-> HR_FNAME}}&nbsp;&nbsp; {{ $infoper-> HR_FNAME}}</option>
                                            @endforeach
                                        </select> 
                                    </div> 
                                    <div class="col-sm-2" >
                                        <label style="font-family:'Kanit',sans-serif;font-size:14px;">แหล่งที่มา/วิธีการค้นพบ :</label>
                                    </div> 
                                    <div class="col-md-2 ">
                                    <select name="INCIDENCE_LOCATION_NAME" id="INCIDENCE_LOCATION_NAME" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                                        <option value="">--เลือก--</option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location-> INCIDENCE_LOCATION_ID}}" >{{ $location-> INCIDENCE_LOCATION_NAME}}</option>
                                            @endforeach
                                        </select> 
                                    </div> 
                                 
                                    <div class="col-sm-2" >
                                        <label style="font-family:'Kanit',sans-serif;font-size:14px;">ช่วงเวลาหรือเวร :</label>
                                    </div> 
                                    <div class="col-md-2 ">
                                    <select name="WORKING_TIME_NAME" id="WORKING_TIME_NAME" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                                        <option value="">--เลือก--</option>
                                            @foreach($worktimes as $worktime)
                                                <option value="{{ $worktime-> WORKING_TIME_ID}}" >{{ $worktime-> WORKING_TIME_NAME}}</option>
                                            @endforeach
                                        </select> 
                                    </div> 
                                </div> 


                                <div class="row push"> 
                                   
                                    <div class="col-sm-2" >
                                        <label style="font-family:'Kanit',sans-serif;font-size:14px;">สถานะผู้แก้ไขรายงานความเสี่ยง :</label>
                                    </div> 
                                    <div class="col-md-2 ">
                                    <select name="SETUP_INCEDENCE_STATUS_NAME_TH" id="SETUP_INCEDENCE_STATUS_NAME_TH" class="form-control input-sm" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;">
                                        <option value="">--เลือก--</option>
                                            @foreach($statuss as $status)
                                                <option value="{{ $status-> SETUP_INCEDENCE_STATUS_ID}}" >{{ $status-> SETUP_INCEDENCE_STATUS_NAME_TH}}</option>
                                            @endforeach
                                        </select> 
                                    </div> 
                                    <div class="col-sm-1" >
                                    <button class="btn btn-info text-white" type="submit" style="font-family:'Kanit',sans-serif;font-size:14px;font-weight:normal;"><i class="fa fa-search fa-sm text-white" style="font-size:15px "></i>&nbsp;&nbsp;ค้นหา </button> 
                                    </div> 
                                    <div class="col-sm-5" >
                                   
                                    </div> 
                                    <div class="col-sm-2" >
                            <div align="right ">
                                <button class="btn btn-success" style="font-family:'Kanit',sans-serif;font-size:13px;font-weight:normal;"><i class="fas fa-file-excel text-white" style="font-size:17px"></i>&nbsp;&nbsp;Export Excel</button> 
                                       
                            </div>
                            </div>  --}}

                           
                            <form action="{{ route('mrisk.report_riskincedentsprofile_search') }}" method="post">
                                @csrf
            
                                <div class="row">
                                    <div class="col-sm-0.5">
                                        &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; ปีงบ &nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="col-sm-1.5">
                                        <span>
                                            <select name="BUDGET_YEAR" id="BUDGET_YEAR" class="form-control input-lg budget"
                                                style=" font-family: 'Kanit', sans-serif;">
                                                @foreach ($budgets as $budget)
                                                    @if ($budget->LEAVE_YEAR_ID == $year_id)
                                                        <option value="{{ $budget->LEAVE_YEAR_ID }}" selected>
                                                            {{ $budget->LEAVE_YEAR_ID }}</option>
                                                    @else
                                                        <option value="{{ $budget->LEAVE_YEAR_ID }}">{{ $budget->LEAVE_YEAR_ID }}
                                                        </option>
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
            
                                                <input name="DATE_BIGIN" id="DATE_BIGIN" class="form-control input-lg datepicker"
                                                    data-date-format="mm/dd/yyyy" value="{{ formate($displaydate_bigen) }}" readonly>
            
                                            </div>
                                            <div class="col-sm">
                                                ถึง
                                            </div>
                                            <div class="col-md-4">
            
                                                <input name="DATE_END" id="DATE_END" class="form-control input-lg datepicker"
                                                    data-date-format="mm/dd/yyyy" value="{{ formate($displaydate_end) }}" readonly>
            
                                            </div>
                                        </div>
            
                                    </div>
                                    <div class="col-sm-0.5">
                                               &nbsp;สถานะ &nbsp;
                                           </div>                                
                                           <div class="col-sm-2">
                                               <span>                                
                                               <select name="STATUS_CODE" id="STATUS_CODE" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;">
                                               <option value="">--ทั้งหมด--</option>                       
                                                    @foreach ($statuss as $status)
                                                               @if ($status->RISK_STATUS_NAME == $status_check)
                                                                   <option value="{{ $status->RISK_STATUS_NAME  }}" selected>{{ $status->RISK_STATUS_NAME_TH}}</option>
                                                               @else
                                                                   <option value="{{ $status->RISK_STATUS_NAME  }}" >{{ $status->RISK_STATUS_NAME_TH}}</option>
                                                               @endif
                                                           @endforeach     
                                               </select>
                                               </span>
                                           </div>
                                    <div class="col-sm-0.5">
                                        &nbsp;คำค้นหา &nbsp;
                                    </div>
                                    <div class="col-sm-2">
                                        <span>
                                            <input type="search" name="search" class="form-control"
                                                style="font-family: 'Kanit', sans-serif;font-weight:normal;"
                                                value="{{ $search }}">
                                        </span>
                                    </div>
                                    <div class="col-sm-30">
                                        &nbsp;
                                    </div>
                                    <div class="col-sm-1.5">
                                        <span>
                                            <button type="submit" class="btn btn-hero-sm btn-hero-info" style="font-family: 'Kanit', sans-serif;font-weight:normal;"><i
                                                    class="fas fa-search mr-2"></i>ค้นหา</button>
                                        </span>
                                    </div>
                                </div>
            
            
            
                            </form>
        <div class="block-content">  
            <div class="table-responsive"> 
                <table class="gwt-table table-striped table-vcenter js-dataTable-simple" style="width: 100%;">
                    <thead style="background-color: #FFEBCD;">
                        <tr height="40">

                            <th class="text-font" style="text-align: center;" width="4">ลำดับ</th>
                            <th class="text-font" style="text-align: center;" width="8%">สถานะ</th>
                            {{-- <th class="text-font" style="text-align: center;" width="9%">ทบทวน</th> --}}
                            <th class="text-font" style="text-align: center;" width="9%">ความรุนแรง</th>
                            <th class="text-font" style="text-align: center;" width="10%">วันที่บันทึก</th>
                            <th class="text-font" style="text-align: center;" width="13%">ที่มา</th>
                            {{-- <th  class="text-font" style="text-align: center;"  width="15%">เรื่อง</th> --}}
                            <th class="text-font" style="text-align: center;">รายละเอียดเหตุการณ์</th>
                            <th class="text-font" style="text-align: center;" width="20%">การจัดการเบื้องต้น</th>
                            {{-- <th  class="text-font" style="text-align: center;" width="10%">วันที่รายงาน</th> --}}


                        </tr>
                    </thead>
                    <tbody>
                        <?php $number = 0; ?>
                        @foreach ($rigreps as $rigrep)
                            <?php
                            $number++;
                            $status = $rigrep->RISKREP_STATUS;

                            if ($status === 'CONFIRM') {
                            $statuscol = 'badge badge-primary';
                            } elseif ($status === 'REPORT') {
                            $statuscol = 'badge badge-warning';
                            } elseif ($status === 'ACCEPT') {
                            $statuscol = 'badge badge-danger';
                            } elseif ($status === 'CHECK') {
                            $statuscol = 'badge badge-info';
                            } elseif ($status === 'SUCCESS') {
                            $statuscol = 'badge badge-success';
                            } else {
                            $statuscol = 'badge badge-secondary';
                            }

                            $level = $rigrep->RISKREP_LEVEL;

                            if ($level === '1') {
                                    $statuslevel = 'badge badge-success';
                                    } elseif ($level === '2') {
                                    $statuslevel = 'badge badge-success';
                                    } elseif ($level === '3') {
                                    $statuslevel = 'badge badge-info';
                                    } elseif ($level === '4') {
                                    $statuslevel = 'badge badge-info';
                                    } elseif ($level === '5') {
                                    $statuslevel = 'badge badge-warning';
                                    } elseif ($level === '6') {
                                    $statuslevel = 'badge badge-warning';                                  
                                    } elseif ($level === '7') {
                                    $statuslevel = 'badge badge-danger';
                                    } elseif ($level === '8') {
                                    $statuslevel = 'badge badge-danger';
                                    } elseif ($level === '9') {
                                    $statuslevel = 'badge badge-danger';
                                    } elseif ($level === '10') {
                                    $statuslevel = 'badge badge-danger';
                                    } elseif ($level === '11') {
                                    $statuslevel = 'badge badge-danger';
                                    } elseif ($level === '12') {
                                    $statuslevel = 'badge badge-danger';
                                    } elseif ($level === '13') {
                                    $statuslevel = 'badge badge-danger';
                                    } elseif ($level === '14') {
                                    $statuslevel = 'badge badge-danger';
                                    } else {
                                    $statuslevel = '';
                                    }

                            
                            ?>

                            <tr height="20">
                                <td class="text-font text-pedding" align="center" width="4%">{{ $number }}</td>

                                <td align="center" style="vertical-align: top;" class="text-pedding"> <span class="{{ $statuscol }}"
                                        width="8%">{{ $rigrep->RISK_STATUS_NAME_TH }}</span></td>
{{--                                
                                <td align="center" width="9%"><a href="" class="btn btn-hero-sm btn-hero-warning"><i class="fa fa-file-signature"></i></a>
                                </td> --}}
                                <td align="center" style="vertical-align: top;" class="text-pedding"> <span class="{{ $statuslevel }}" width="8%" style=" font-size: 20px;">{{ $rigrep->RISK_REP_LEVEL_NAME }}</span></td>
                                <td class="text-font text-pedding" style="text-align: center;vertical-align: top;"  width="10%">
                                    {{ DateThai($rigrep->RISKREP_DATESAVE) }}</td>
                                <td class="text-font text-pedding" style="text-align: left;vertical-align: top;"width="13%">
                                    {{ $rigrep->INCIDENCE_LOCATION_NAME }}</td>
                                <td class="text-font text-pedding">{!! $rigrep->RISKREP_DETAILRISK !!}</td>
                                <td class="text-font text-pedding" style="text-align: left;">{!! $rigrep->RISKREP_BASICMANAGE !!}
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
</script>

@endsection
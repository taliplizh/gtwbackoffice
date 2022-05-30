@extends('layouts.meet')
<link href="{{ asset('select2/select2.min.css') }}" rel="stylesheet" />
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

    input::-webkit-calendar-picker-indicator {

        font-family: 'Kanit', sans-serif;
        font-size: 14px;
    }
</style>
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



use App\Http\Controllers\ManagermeetController;
$checkver = ManagermeetController::checkver($user_id);
$countver = ManagermeetController::countver($user_id);


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
    date_default_timezone_set("Asia/Bangkok");
    $date = date('Y-m-d');    
?>
<link rel="stylesheet" type="text/css" href="{{ asset('css/1.10.24.css.jquery.dataTables.css') }}">
<style>
    .hoverwhite:hover {
        color: white !important;
    }

    table.dataTable td {
        padding: 0px !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
        vertical-align: middle;
    }

    table.dataTable th {
        vertical-align: middle;
    }

</style>

<center>
    <div class="block" style="width: 95%;">

        <!-- Dynamic Table Simple -->
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
              

                <div class="col-12"><br>
                    <div class="row ">
                        <div class="col-sm-4 offset-md-4">
                            <h1 class="block-title" style="font-family: 'Kanit', sans-serif;font-size:25px;">
                                <B>ข้อมูลการจองห้องประชุม{{$type->ROOM_NAME}}</B>
                            </h1>
                        </div>
                        <div class="col-4"> <a class="btn btn-hero btn-success " style="float: right;  font-family: 'Kanit', sans-serif;
                            font-size: 14px;"
                                href="{{ url('manager_meet/dashboard_meet') }}"><i
                                    class="far fa-arrow-alt-circle-left  fa-1x">&nbsp;&nbsp;</i>ย้อนกลับ</a></div>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">

            

                <div class="table-responsive">
                    <!-- DataTables init on table by adding .js-dataTable-simple class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                        <table id="NormalWork_table" class="gwt-table table-striped table-vcenter js-dataTable-simple" style="width: 100%;">
                        <thead style="background-color: #FFEBCD;">
                            <tr height="40">
                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;" width="5%">
                                    ลำดับ</th>

                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;" width="7%">
                                    สถานะ</th>
                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                    width="15%">ห้อง</th>
                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">เรื่อง</th>

                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                    width="10%">วันที่จอง</th>
                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;" width="7%">
                                    เวลา</th>
                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                    width="10%">ถึงวันที่</th>
                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;" width="7%">
                                    เวลา</th>
                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                    width="13%">ผู้ขอจอง</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 0; ?>
                            @foreach ($type_detile as $row)
                            <?php $number++;  
                                
                                $status =  $row -> STATUS;
                                if( $status === 'REQUEST'){
                                    $statuscol =  "badge badge-warning";

                                }else if($status === 'SUCCESS'){
                                    $statuscol =  "badge badge-info";
                                }else if($status === 'LASTAPP'){
                                    $statuscol =  "badge badge-success";
                                }else if($status === 'NOTSUCCESS'){
                                    $statuscol =  "badge badge-danger";
                                }else if($status === 'INFORM'){
                                    $statuscol =  "badge badge-dark";
                                }else{
                                    $statuscol =  "badge badge-secondary";
                                }
                                
                                
                                
                                ?>

                            <tr height="40">
                                <td class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;" width="5%">
                                    {{$number}}</td>

                                <td style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"><span
                                        class="{{$statuscol}}">{{ $row->STATUS_NAME}}</span></td>

                                <td class="text-font text-pedding"
                                    style="border-color:#F0FFFF;text-align: left;border: 1px solid black;">
                                    {{ $row->ROOM_NAME}}</td>
                                <td class="text-font text-pedding"
                                    style="border-color:#F0FFFF;text-align: left;border: 1px solid black;">
                                    {{ $row->SERVICE_STORY}}</td>
                                <td class="text-font text-pedding"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                    {{ DateThai($row->DATE_BEGIN)}}</td>
                                <td class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                    {{ date("H:i",strtotime("$row->TIME_BEGIN")) }}</td>
                                <td class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                    {{ DateThai($row->DATE_END)}}</td>
                                <td class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                    {{ date("H:i",strtotime("$row->TIME_END")) }}</td>
                                <td class="text-font text-pedding"
                                    style="border-color:#F0FFFF;text-align: left;border: 1px solid black;">
                                    {{ $row->PERSON_REQUEST_NAME}}</td>



                                


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

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#NormalWork_table').DataTable({
                info: false,
                // "bPaginate": false,
                // "bLengthChange": false,
                "iDisplayLength": 25,
                // "bFilter": false,    
                // "bInfo": false,
                // "bAutoWidth": false
                // "paging": false,
            });
        });
    </script>
    @endsection
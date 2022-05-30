@extends('layouts.guesthouse')
@section('css_before')
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
  function RemovegetAge($birthday) {
    $then = strtotime($birthday);
    return(floor((time()-$then)/31556926));
}
?>
<style>
    body * {
        font-family: 'Kanit', sans-serif;
    }
    p {
        word-wrap: break-word;
    }
    .text {
        font-family: 'Kanit', sans-serif;
    }

    #example_filter{
        float:right;
    }
    #example_paginate{
        float:right;
    }



</style>

@endsection
@section('content')
<br>
<br>
<div class="block mb-4" style="width: 95%;margin:auto">
    <div class="block-content">
        <div class="block-header block-header-default">
            <h3 class="block-title text-center fs-24">ข้อมูลบ้านพัก</h3>
        </div>
        <hr>
        <form action="{{ route('mguesthouse.dashboardsearch') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-2 d-flex justify-content-center align-items-center">
                    &nbsp;ข้อมูลประจำปี : &nbsp;
                </div>
                <div class="col-md-2">
                    <span>
                        <select name="STATUS_CODE" id="STATUS_CODE" class="form-control input-lg"
                            style=" font-family: 'Kanit', sans-serif;">
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
                <div class="col-md-1 text-center">
                    <span>
                        <button type="submit" class="btn btn-info fw-5 f-kanit">แสดง</button>
                    </span>
                </div>
            </div>
        </form>
        <div class="block-content my-3 shadow">
            <div class="row">
                <div class="col-sm-2"></div> 
                <div class="col-sm-6 col-md-4 invisible" data-toggle="appear">
                            <div class="block block-rounded text-center">
                                <div class="block-content block-content-full">
                                    <!-- Pie Chart Container -->
                                    
                                    <div class="js-pie-chart pie-chart" data-percent="{{($checkcount_flat_person/($checkcount_flat_person + $checkcount_flat))*100}}" data-line-width="5" data-size="100" data-bar-color="#11bda1" data-track-color="#e9e9e9">
                                        <span>
                                            <img class="img-avatar" src="{{asset('asset/media/avatars/avatar11.jpg') }}" alt="">
                                        </span>
                                    </div>
                                </div>
                                <div class="block-content bg-xinspire-lighter">
                                <a href="" data-toggle="modal" data-target="#checkcount_flat_person">    
                                    <p class="text-black-50 text-uppercase font-size-sm font-w700">
                                        จำนวนผู้เข้าพักแฟลต &nbsp; &nbsp; 
                                        <span style="font-size: 1.75rem;" class="">{{$checkcount_flat_person}} / {{$checkcount_flat_person + $checkcount_flat}}</span>
                                        &nbsp; ห้อง
                                    </p>
                                </a> 
                                </div>
                            </div>
                        </div>
                         
                        <div class="col-sm-6 col-md-4 invisible" data-toggle="appear">
                            <div class="block block-rounded text-center">
                                <div class="block-content block-content-full">
                                    <!-- Pie Chart Container -->
                                    <div class="js-pie-chart pie-chart" data-percent="{{($checkcount_house_person/($checkcount_house_person+$checkcount_house))*100}}" data-line-width="5" data-size="100" data-bar-color="#11bda1" data-track-color="#e9e9e9">
                                        <span>
                                            <img class="img-avatar" src="{{asset('asset/media/avatars/avatar11.jpg') }}" alt="">
                                        </span>
                                    </div>
                                </div>
                                <div class="block-content bg-xinspire-lighter">
                                <a href="" data-toggle="modal" data-target="#checkcount_house_person">
                                    <p class="text-black-50 text-uppercase font-size-sm font-w700 " >
                                        จำนวนผู้เข้าพักบ้านพัก &nbsp; &nbsp; 
                                        <span style="font-size: 1.75rem;" class="">{{$checkcount_house_person}} / {{$checkcount_house_person+$checkcount_house}}</span>
                                        &nbsp; ห้อง
                                    </p> 
                                </a>
                                </div>
                            </div>
                        </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl-b2">
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                จำนวนห้องพักในแฟลตที่ว่าง
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    <!-- {{$amount_1}} <span class="fs-13">{{-- หน่วยนับ --}}</span> -->
                                   {{$checkcount_flat}}
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-building fs-30 text-white"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl2-g3" >
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                จำนวนบ้านพักที่ว่าง
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    <!-- {{$amount_1}} <span class="fs-13">{{-- หน่วยนับ --}}</span> -->
                                    {{$checkcount_house}}
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-home fs-30 text-white"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl2-y3">
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                    จำนวนรายการขอ
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    <!-- {{$amount_2}} <span class="fs-13">{{-- หน่วยนับ --}}</span> -->
                                    {{$count_request}}
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-paper-plane fs-30 text-white"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl2-r3" >
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                    จำนวนการแจ้งปัญหา
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    <!-- {{$amount_1 + $amount_2}} <span class="fs-13">{{-- หน่วยนับ --}}</span> -->
                                    {{$count_problem}}
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-cogs fs-30 text-white"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
</div>
            <div class="block-content my-3 shadow">
            <h3 class="fs-18 fw-5">ข้อมูลรายการร้องขอ</h3>
            <div class="row">
                <div class="col-md-6 col-xl-4">
                            <a class="block block-rounded block-link-pop bg-sl-gb2" href="{{ url('manager_guesthouse/guesthouserequest') }}">
                                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fa fa-2x fa-paper-plane text-white"></i>
                                    </div>
                                    <div class="ml-3 text-right text-white">
                                        <p class=" mb-0 fs-16">
                                            ขอย้ายออก
                                        </p>
                                        <p class="font-size-h3 font-w300 mb-0">
                                            {{$count_request_3}}
                                        </p>
                                        
                                    </div>
                                </div>
                            </a>
                        </div> <div class="col-md-6 col-xl-4">
                            <a class="block block-rounded block-link-pop bg-sl-gb1" href="{{ url('manager_guesthouse/guesthouserequest') }}">
                                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fa fa-2x fa-paper-plane text-white"></i>
                                    </div>
                                    <div class="ml-3 text-righ text-white">
                                        <p class=" mb-0 fs-16">
                                            ขอเข้าพัก
                                        </p>
                                        <p class="font-size-h3 font-w300 mb-0">
                                            {{$count_request_1}}
                                        </p>
                                   </div>
                                </div>
                            </a>
                        </div> <div class="col-md-6 col-xl-4">
                            <a class="block block-rounded block-link-pop bg-sl-b3" href="{{ url('manager_guesthouse/guesthouserequest') }}">
                                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fa fa-2x fa-paper-plane text-white"></i>
                                    </div>
                                    <div class="ml-3 text-right text-white">
                                        <p class=" mb-0 fs-16">
                                        ขอเปลี่ยนแปลง
                                        </p>
                                        <p class="font-size-h3 font-w300 mb-0">
                                            {{$count_request_2}}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
            <!-- row -->
            </div>
            </div>
            <div class="block-content my-3 shadow">
            <h3 class="fs-18 fw-5">ข้อมูลการเเจ้งปัญหา</h3>
            <div class="row">
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-link-pop bg-sl2-p1" href="{{ url('manager_guesthouse/guesthouseproblem') }}">
                                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fa fa-2x fa-cogs text-white"></i>
                                    </div>
                                    <div class="ml-3 text-right text-white">
                                        <p class=" mb-0 fs-16">
                                            เเจ้งปัญหา
                                        </p>
                                        <p class="font-size-h3 font-w300 mb-0">
                                            {{$count_problem_1}}
                                        </p>
                                        
                                    </div>
                                </div>
                            </a>
                        </div> 
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-link-pop bg-sl2-p2" href="{{ url('manager_guesthouse/guesthouseproblem') }}">
                                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fa fa-2x fa-cogs text-white"></i>
                                    </div>
                                    <div class="ml-3 text-right text-white">
                                        <p class=" mb-0 fs-16">
                                            แก้ปัญหาเรียบร้อย
                                        </p>
                                        <p class="font-size-h3 font-w300 mb-0">
                                            {{$count_problem_2}}
                                        </p>
                                   </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-xl-3">
                                    <a class="block block-rounded block-link-pop bg-sl2-p4" href="{{ url('manager_guesthouse/guesthouseproblem') }}">
                                        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fa fa-2x fa-cogs text-white"></i>
                                            </div>
                                            <div class="ml-3 text-right text-white">
                                                <p class=" mb-0 fs-16">
                                                    ยกเลิกปัญหา
                                                </p>
                                                <p class="font-size-h3 font-w300 mb-0">
                                                    {{$count_problem_3}}
                                                </p>
                                                
                                            </div>
                                        </div>
                                    </a>
                                </div> 
                                <div class="col-md-6 col-xl-3">
                                    <a class="block block-rounded block-link-pop bg-sl-p2" href="{{ url('manager_guesthouse/guesthouseproblem') }}">
                                        <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fa fa-2x fa-cogs text-white"></i>
                                            </div>
                                            <div class="ml-3 text-right text-white">
                                                <p class=" mb-0 fs-16">
                                                    แก้ไม่ได้
                                                </p>
                                                <p class="font-size-h3 font-w300 mb-0">
                                                    {{$count_problem_4}}
                                                </p>
                                        </div>
                                        </div>
                                    </a>
                                </div>
                <!-- row -->
            </div>
        </div>
  
        <div class="block block-content my-3 shadow">
            <h3 class="fs-18 fw-5">ข้อมูลแผนภูมิข้อมูลบ้านพัก</h3>
            <div class="row mb-2">
                <div class="col-md-12 mb-2">
                    <div class="panel p-1" style="background:#80858a">
                        <div class="pane-heading py-2 px-3 text-white" style="text-align:left">
                        จำนวนผู้เข้าพัก
                        </div>
                        <div class="pane-body bg-white d-flex justify-content-center" style="overflow-y:hidden">
                            <div id="columnchart_car"
                                style="font-family: 'Kanit', sans-serif;width: 100%; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-content my-3 shadow">
            <h3 class="fs-18 fw-5">ข้อมูลแผนภูมิข้อมูลแฟลตเเละบ้านพัก</h3>
            <div class="row mb-2">
                <div class="col-md-12 mb-2">
                    <div class="panel p-1" style="background:#80858a">
                        <div class="pane-heading py-2 px-3 text-white" style="text-align:left">
                        จำนวนผู้เข้าพักแฟลตเเละบ้านพัก
                        </div>
                        <div class="pane-body bg-white d-flex justify-content-center" style="overflow-y:hidden">
                            <div id="columnchart_material"
                                style="font-family: 'Kanit', sans-serif;width: 90%; height: 500px; margin:40px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-content my-3 shadow">
            <h3 class="fs-18 fw-5">ข้อมูลแผนภูมิข้อมูลแฟลตเเละบ้านพัก</h3>
            <div class="row mb-2">
                <div class="col-md-12 mb-2">
                    <div class="panel p-1" style="background:#80858a">
                        <div class="pane-heading py-2 px-3 text-white" style="text-align:left">
                        จำนวนผู้เข้าพักแฟลตเเละบ้านพัก
                        </div>
                        <div class="pane-body bg-white d-flex justify-content-center " style="overflow-y:hidden">
                            <div id="barchart_material"
                                style="font-family: 'Kanit', sans-serif;width: 90%; height: 500px; margin:20px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="block block-content my-3 shadow">
            <h3 class="fs-18 fw-5">ข้อมูลแผนภูมิข้อมูลแฟลตเเละบ้านพัก</h3>
            <div class="row mb-2">
                <div class="col-md-12 mb-2">
                    <div class="panel p-1" style="background:#80858a">
                        <div class="pane-heading py-2 px-3 text-white" style="text-align:left">
                        จำนวนผู้เข้าพักแฟลตเเละบ้านพัก
                        </div>
                        <div class="pane-body bg-white d-flex justify-content-center" style="overflow-y:hidden">
                            <div id="curve_chart"
                                style="font-family: 'Kanit', sans-serif;width: 100%; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-content my-3 shadow">
            <h3 class="fs-18 fw-5">ข้อมูลแผนภูมิข้อมูลแฟลตเเละบ้านพัก</h3>
            <div class="row mb-2">
                <div class="col-md-12 mb-2">
                    <div class="panel p-1" style="background:#80858a">
                        <div class="pane-heading py-2 px-3 text-white" style="text-align:left">
                        จำนวนผู้เข้าพักแฟลตเเละบ้านพัก
                        </div>
                        <div class="pane-body bg-white d-flex justify-content-center" style="overflow-y:hidden">
                            <div id="piechart"
                                style="font-family: 'Kanit', sans-serif;width: 100%; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-content my-3 shadow">
            <h3 class="fs-18 fw-5">ข้อมูลแผนภูมิข้อมูลแฟลตเเละบ้านพัก</h3>
            <div class="row mb-2">
                <div class="col-md-12 mb-2">
                    <div class="panel p-1" style="background:#80858a">
                        <div class="pane-heading py-2 px-3 text-white" style="text-align:left">
                        จำนวนผู้เข้าพักแฟลตเเละบ้านพัก
                        </div>
                        <div class="pane-body bg-white d-flex justify-content-center" style="overflow-y:hidden">
                            <div id="donutchart"
                                style="font-family: 'Kanit', sans-serif;width: 100%; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection
@section('footer')
<script src="{{ asset('datepicker/bootstrap-3.3.7-dist/js/bootstrap.js') }}"></script>
<script src="{{ asset('datepicker/bootstrap-3.3.7-dist/js/bootstrap.js') }}"></script>
<script src="{{ asset('google/Charts.js') }}"></script>


<script type="text/javascript">
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['เดือน','บ้านพัก'],
          ['ม.ค', <?php echo $m1_1; ?>],
          ['ก.พ', <?php echo $m2_1; ?>],
          ['มี.ค', <?php echo $m3_1;?>],
          ['เม.ย', <?php echo $m4_1;?>],
          ['พ.ค', <?php echo $m5_1;?>],
          ['มิ.ย', <?php echo $m6_1;?>],
          ['ก.ค', <?php echo $m7_1;?>],
          ['ส.ค', <?php echo $m8_1;?>],
          ['ก.ย', <?php echo $m9_1;?>],
          ['ต.ค', <?php echo $m10_1;?>],
          ['พ.ย', <?php echo $m11_1; ?>],
          ['ธ.ค', <?php echo  $m12_1;?>]
        ]);
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            }
        ]);
        var options = {
            fontName: 'Kanit',
            fontSize: 16,
            width: "100%",
            height: '100%',
            legend: {
                position: 'center'
            },
            bar: {
                groupWidth: "80%"
            },
            vAxis: {
                title: 'จำนวน',
                titleTextStyle: {
                    italic: false
                }
            },
            hAxis: {
                title: 'เดือน',
                fontName: 'Kanit',
                titleTextStyle: {
                    italic: false
                }
            }
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_car'));
        chart.draw(view, options);
    }
//Column Chart
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart_columnchart);

      function drawChart_columnchart() {
        var data_columnchart = google.visualization.arrayToDataTable([
            ['Year', 'บ้านพัก', 'แฟลต', ],
            ['2561',  1, 2],
            ['2562',  2, 1],
            ['2563',  1, 2],
            ['2564',  3, <?php echo $year_falt[3]?>],
            ['2565',  <?php echo $year_house[4]?>, <?php echo $year_falt[4]?>]
        ]);

        var view = new google.visualization.DataView(data_columnchart);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            }
        ]);
       var options = {
            fontName: 'Kanit',
            fontSize: 16,
            width: "100%",
            height: '100%',
            legend: {
                position: 'center'
            },
            bar: {
                groupWidth: "50%"
            },
            vAxis: {
                title: 'จำนวน',
                titleTextStyle: {
                    italic: false
                }
            },
            hAxis: {
                title: 'ปี',
                fontName: 'Kanit',
                titleTextStyle: {
                    italic: false
                }
            },
            colors: ['#EEEBDD','#D8B6A4' ]
        };
        var chart_columnchart = new google.charts.Bar(document.getElementById('columnchart_material'));
        chart_columnchart.draw(data_columnchart, google.charts.Bar.convertOptions(options));
      }
// barchart
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart_barchart);
    function drawChart_barchart() {
          var data_barchart = google.visualization.arrayToDataTable([
            ['Year', 'บ้านพัก', 'แฟลต', ],
            ['2561',  1, 2],
            ['2562',  2, 1],
            ['2563',  1, 2],
            ['2564',  3, <?php echo $year_falt[3]?>],
            ['2565',  <?php echo $year_house[4]?>, <?php echo $year_falt[4]?>]

         ]);
         
        var options_barchart = {
            fontName: 'Kanit',
            fontSize: 16,
            width: "80%",
            height: '100%',
            legend: {
                position: 'center'
            },
            vAxis: {
                title: 'ปี',
                titleTextStyle: {
                    italic: false
                }
            },
            hAxis: {
                title: 'คน',
                fontName: 'Kanit',
                titleTextStyle: {
                    italic: false
                }
            },
            
           colors: ['#BEAEE2', '#F7DBF0'],
           bars: 'horizontal' // Required for Material Bar Charts.
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data_barchart, google.charts.Bar.convertOptions(options_barchart));
      }
// LineChart
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart_line);

      function drawChart_line() {
        var data__line = google.visualization.arrayToDataTable([
            ['Year', 'บ้านพัก', 'แฟลต', ],
            ['2561',  1, 2],
            ['2562',  2, 1],
            ['2563',  1, 2],
            ['2564',  3, <?php echo $year_falt[3]?>],
            ['2565',  <?php echo $year_house[4]?>, <?php echo $year_falt[4]?>]

        ]);

        var options = {
            fontName: 'Kanit',
            fontSize: 16,
            width: "100%",
            height: '100%',
            legend: {
                position: 'center'
            },
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['#FBD148', '#B2EA70']
        };

        var chart_line = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart_line.draw(data__line, options);
      }
// PieChart
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart_piechart);

      function drawChart_piechart() {

        var data_piechart = google.visualization.arrayToDataTable([
          ['ประเภท', 'จำนวนคน'],
          ['แฟลต',    <?php echo $amount_1?>],
          ['บ้านพัก',   3]

        ]);

        var options = {
            fontName: 'Kanit',
            fontSize: 16,
            width: "100%",
            height: '100%',
            legend: {
                position: 'center'
            },
            slices: {
            0: { color: '#E98580' },
            1: { color: '#DF5E5E' },

          },
          title: 'จำนวนผู้เข้าพักแฟลตเเละบ้านพัก ปี <?php echo $year_id; ?>'
        };

        var chart_piechart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart_piechart.draw(data_piechart, options);
      }
// donutchart.
google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart_donutchart);
      function drawChart_donutchart() {
        var data = google.visualization.arrayToDataTable([
          ['ประเภท', 'จำนวนคน'],
          ['แฟลต',    <?php echo $amount_1?>],
          ['บ้านพัก',   3]
        ]);

        var options = {
          title: 'จำนวนผู้เข้าพักแฟลตเเละบ้านพัก ปี <?php echo $year_id; ?>',
          fontName: 'Kanit',
            fontSize: 16,
            width: "100%",
            height: '100%',
            legend: {
                position: 'center'
            },
          pieHole: 0.4,
          slices: {
            0: { color: '#7C83FD' },
            1: { color: '#96BAFF' },

          }
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>

<!-- The Modal request-->
<div class="modal" id="checkcount_flat_person">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">จำนวนผู้เข้าพักแฟลต</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

      <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead class=" table-primary">
                                    <tr>
                                        <th class="text-center" style="width: 100px;"><i class="far fa-user"></i></th>
                                        <th>ชื่อ- สกุล ผู้เข้าพัก</th>
                                        <th>ชื่อแฟลต</th>
                                        <th>หมายเลขห้อง</th>
                                        <th>บุคคลร่วม</th>
                                        <th class="d-none d-sm-table-cell" style="width: 15%;">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach ($infomations_flat as $row)
                                    <tr>
                                        <td class="text-center">
                                            <img class="img-avatar img-avatar48" src="{{ asset('asset/media/avatars/avatar7.jpg') }}" alt="">
                                        </td>
                                        <td class="font-w600">{{$row->HR_FNAME}} &nbsp;{{$row->HR_LNAME}} </td>
                                        <td class="d-none d-sm-table-cell">{{$row->INFMATION_NAME}}</td>
                                        <td class="d-none d-sm-table-cell">{{$row->LEVEL_ROOM_NAME}}</td>
                                        <td class="d-none d-sm-table-cell">{{$row->INFMATION_OUTSIDER_NAME}}</td>
                                        <td class="d-none d-md-table-cell" ><h5><span class="badge badge-success">ปกติ</span></h5></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div>

               
            <!-- row -->
            </div>
        </div>  
    </div>
  </div>
</div>

<!-- The Modal problem-->
<div class="modal" id="checkcount_house_person">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">จำนวนผู้เข้าพักบ้านพัก</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

      <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                <!-- <table class="table table-bordered table-striped table-vcenter text-center" id="example1"> -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead class=" table-success">
                                    <tr>
                                        <th class="text-center" style="width: 100px;"><i class="far fa-user"></i></th>
                                        <th>ชื่อ- สกุล ผู้เข้าพัก</th>
                                        <th>ชื่อบ้านพัก</th>
                                        <th>บุคคลร่วม</th>
                                        <th class="d-none d-sm-table-cell" style="width: 15%;">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                @foreach ($infomations_house as $row)
                                    <tr>
                                        <td class="text-center">
                                            <img class="img-avatar img-avatar48" src="{{ asset('asset/media/avatars/avatar7.jpg') }}" alt="">
                                        </td>
                                        <td class="font-w600">{{$row->HR_FNAME}} &nbsp;{{$row->HR_LNAME}} </td>
                                        <td class="d-none d-sm-table-cell">{{$row->INFMATION_NAME}}</td>
                                        <td class="d-none d-sm-table-cell">{{$row->INFMATION_OUTSIDER_NAME}}</td>
                                        <td class="d-none d-md-table-cell" ><h5><span class="badge badge-success">ปกติ</span></h5></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div>
            <!-- row -->
            </div>
        </div> 
    </div>
  </div>
</div>



<!--css , js, jQuery easy-pie-chart-->
    <script src="{{ asset('asset/js/dashmix.core.min.js') }}"></script>
    <script src="{{ asset('asset/js/dashmix.app.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script>jQuery(function(){ Dashmix.helpers(['easy-pie-chart', 'sparkline']); });</script>

<!-- css, js dataTables --> 
    <script src="{{ asset('asset/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('asset/js/plugins/datatables/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('asset/js/pages/be_tables_datatables.min.js') }}"></script>

  


@endsection


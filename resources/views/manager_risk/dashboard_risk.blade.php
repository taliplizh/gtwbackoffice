@extends('layouts.risk')
@section('css_before')
<?php
$status = Auth::user()->status; 
$id_user = Auth::user()->PERSON_ID; 
$url = Request::url();
$pos = strrpos($url, '/') + 1;
$user_id = substr($url, $pos); 
?>

<style>
    body *{
        font-family: 'Kanit', sans-serif;        
        }
        p {
            word-wrap:break-word;
            }
        .text{
            font-family: 'Kanit', sans-serif;                    
            }
        .ex1 {
            margin-top: 1000px;
    }
</style>
@endsection
@section('content')
<br>
<br>

<div class="block mb-4" style="width: 95%;margin:auto">
    <div class="block-content">
        <div class="block-header block-header-default">
            <h3 class="block-title text-center fs-24">ข้อมูลการควบคุมภายในและความเสี่ยง</h3>
        </div>
        <hr>
        <form  action="{{ route('mrisk.dashboard') }}" method="get">
            <div class="row">
                <div class="col-md-2 d-flex justify-content-center align-items-center">
                    &nbsp;ข้อมูลประจำปีงบประมาณ : &nbsp;
                </div>
                <div class="col-md-2">
                    <span>
                        <select name="budgetyear" id="budgetyear" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;"> 
                            @foreach ($budgetyear_dropdown as $budget)
                                @if($budget === (int) $budgetyear)
                                    <option value="{{ $budget }}" selected>{{ $budget}}</option>
                                @else
                                    <option value="{{ $budget }}">{{ $budget}}</option>
                                @endif                                 
                            @endforeach  
                        </select>
                    </span>
                </div>
                <div class="col-md-1">
                    <span>
                        <button type="submit" class="f-kanit btn btn-info">แสดง</button>
                    </span>
                </div>
            </div>
        </form>
        <div class="block-content my-3 shadow">
            <h3 class="fs-18 fw-5">ภาพรวมความเสี่ยง</h3>
            <div class="row mb-2">
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl2-sb4">
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                    รายงานทั้งหมด
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    {{$sum_rep_accdel}} <span class="fs-16"> ครั้ง </span>
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-book fs-30 text-white"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl2-y4">
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                    บัญชีความเสี่ยง
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    {{$riskaccount_total}} <span class="fs-16"> ครั้ง </span>
                                    <span class="fs-18">({{number_format($riskaccount_percent,2)}}%)</span>
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-paper-plane fs-30 text-white"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl2-r4">
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                    อุบัติการณ์ความเสี่ยง
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    {{$riskrep_total}} <span class="fs-16"> ครั้ง </span>
                                    <span class="fs-18">({{number_format($riskrep_percent,2)}}%)</span>
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-inbox fs-30 text-white"></i> 
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl2-p3">
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                    จำนวนคนใช้งานอุบัติการณ์ความเสี่ยง
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    {{$person_use_risk_report}} <span class="fs-16"> คน </span>
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-user-friends fs-30 text-white"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <h3 class="fs-18 fw-5">อุบัติการณ์ความเสี่ยงประจำวัน และช่วงสถานะการดำเนินการ</h3>
            <div class="row mb-2">
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl2-s4">
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                    อุบัติการณ์ความเสี่ยง วันนี้
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    {{$risk_rep_today}} <span class="fs-16"> ครั้ง </span>
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-book fs-30 text-white"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl2-y4">
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                    อุบัติการณ์ความเสี่ยง รายงาน
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    {{$risk_rep_report}} <span class="fs-16"> ครั้ง </span>
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-paper-plane fs-30 text-white"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl2-y5">
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                    อุบัติการณ์ความเสี่ยง รอยืนยัน
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    {{$risk_rep_confirm}} <span class="fs-16"> ครั้ง </span>
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-inbox fs-30 text-white"></i> 
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-xl-3">
                    <a class="block block-rounded block-link-pop bg-sl2-g4">
                        <div class="block-content block-content-full d-flex justify-content-between">
                            <div class="ml-2 left">
                                <p class="text-white mb-0 fs-16">
                                    อุบัติการณ์ความเสี่ยง ยืนยันความเสี่ยง
                                </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    {{$risk_rep_checkok}} <span class="fs-16"> คน </span>
                                </p>
                            </div>
                            <div class="mr-2 right d-flex justify-content-center align-items-top">
                                <i class="fa fa-user-friends fs-30 text-white"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="block-content my-3 shadow">
            <h3 class="fs-18 fw-4">ข้อมูลแผนภูมิการควบคุมภายในและความเสี่ยง</h3>
            <div class="row mb-2">
                <div class="col-md-8 mb-2">
                    <div class="panel p-1 bg-sl2-p3 mb-2">
                        <div class="pane-heading py-2 px-3 text-white" style="text-align:left">
                            จำนวนความเสี่ยงในแต่ละระดับ
                        </div>
                        <div class="pane-body bg-white d-flex justify-content-center" style="overflow-y:hidden">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0" id="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="15%">ระดับความเสี่ยง</th>
                                            <th class="text-center">รายละเอียดระดับความเสี่ยง</th>
                                            <th class="text-center" width="10%">จำนวน</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($countLevelRisk as $row)
                                        <tr>
                                            <td class="text-center fs-22 fw-8">{{$row['RISK_REP_LEVEL_NAME']}}</td>
                                            <td>{{$row['RISK_REP_LEVEL_DETAIL']}}</td>
                                            <td class="text-center">
                                                <div class="text-center px-2 fs-18">{{$row['risk_level_amount']}}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="panel p-1 bg-sl2-p3 mb-2">
                        <div class="pane-heading py-2 px-3 text-white" style="text-align:left">
                            อัตราการเกิดความเสี่ยง ระดับความรุนแรงทั้งหมด
                        </div>
                        <div class="pane-body bg-white d-flex justify-content-center" style="overflow-y:hidden">
                            <div id="piechart_levelAI" style="width: 100%; height: 500px;"></div>
                        </div>
                    </div>
                    <div class="panel p-1 bg-sl2-p3 mb-2">
                        <div class="pane-heading py-2 px-3 text-white" style="text-align:left">
                            อัตราการเกิดความเสี่ยง ระดับความรุนแรง C,D,E,H</div>
                        <div class="pane-body bg-white d-flex justify-content-center" style="overflow-y:hidden">
                            <div id="piechart_levAI" style="width: 100%; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-12 mb-2">
                    <div class="panel p-1 bg-sl2-p3">
                        <div class="pane-heading py-2 px-3 text-white" style="text-align:left">
                            ช่วงเวลาที่เกิดอุบัติการณ์ความเสี่ยง
                        </div>
                        <div class="pane-body bg-white d-flex justify-content-center" style="overflow-y:hidden">
                            <div id="columnchart_car" style="font-family: 'Kanit', sans-serif;width: 100%; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
<script src="{{ asset('google/Charts.js') }}"></script>

<script type="text/javascript">
    google.load("visualization", "1", {
        packages: ["corechart"]
    });
    google.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['เดือน','จำนวน (ครั้ง)'],
          ['ต.ค', <?php echo $time_incidence_M[10];?>],
          ['พ.ย', <?php echo $time_incidence_M[11]; ?>],
          ['ธ.ค', <?php echo  $time_incidence_M[12];?>],
          ['ม.ค', <?php echo $time_incidence_M[1]; ?>],
          ['ก.พ', <?php echo $time_incidence_M[5]; ?>],
          ['มี.ค', <?php echo $time_incidence_M[3];?>],
          ['เม.ย', <?php echo $time_incidence_M[4];?>],
          ['พ.ค', <?php echo $time_incidence_M[5];?>],
          ['มิ.ย', <?php echo $time_incidence_M[6];?>],
          ['ก.ค', <?php echo $time_incidence_M[7];?>],
          ['ส.ค', <?php echo $time_incidence_M[8];?>],
          ['ก.ย', <?php echo $time_incidence_M[9];?>],
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
</script>

<?php $data_levelAI[] = array('อัตราการเกิดความเสี่ยง ','ระดับความรุนแรง'); ?>
@foreach ($countLevelRisk as $level)
<?php $data_levelAI[] = array($level['RISK_REP_LEVEL_NAME'],$level['risk_level_amount']); ?>
@endforeach

<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php
            echo json_encode($data_levelAI);
            ?>);
        var options = {
            height: '500',
            fontSize: 16,
            legend: {
                position: "top",
                alignment: "center"
            },
            fontName: 'Kanit',
            pieHole: 0.4,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart_levelAI'));
        chart.draw(data, options);
    }
</script>

<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['C',     <?php echo $lev_C; ?>],
          ['D',     <?php echo $lev_D; ?>],
          ['E',     <?php echo $lev_E; ?>],
          ['H',     <?php echo $lev_H; ?>],
          ]);
        var options = {
            fontSize: 16,
            legend: {
                position: "top",
                alignment: "center"
            },
            fontName: 'Kanit',
            pieHole: 0.4,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart_levAI'));
        chart.draw(data, options);
    }
</script>
@endsection
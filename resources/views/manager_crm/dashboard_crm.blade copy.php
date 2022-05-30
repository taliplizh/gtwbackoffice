@extends('layouts.crm')



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

  function RemovegetAge($birthday) {
    $then = strtotime($birthday);
    return(floor((time()-$then)/31556926));
}
?>

<style>
        body {
            font-family: 'Kanit', sans-serif;
           
            }

            p {
	
                word-wrap:break-word;
                }
                .text{
                    font-family: 'Kanit', sans-serif;
                     
                }
</style>


<br>
<br>
<center>    
    <div class="block" style="width: 95%;">
        <div class="block-content">
            <div class="block-header block-header-default">
                <h3 class="block-title" style="font-family: 'Kanit', sans-serif;"><B>ข้อมูลการบริจาค
</B></h3>
                 
            </div>
            <div class="block-content">
            <form action="{{ route('mcrm.dashboardsearch') }}" method="post">
                    @csrf 
            <div class="row" >
        
           
            <div class="col-md-2">
                &nbsp;ข้อมูลประจำปี : &nbsp;
            </div>
            <div class="col-md-2">
            <span>
                             <select name="STATUS_CODE" id="STATUS_CODE" class="form-control input-lg" style=" font-family: 'Kanit', sans-serif;">   
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
                <div class="col-md-1">
                            <span>
                                <button type="submit" class="btn btn-info" style="font-family: 'Kanit', sans-serif; font-size: 10px;font-size: 1.0rem;font-weight:normal;">แสดง</button>
                            </span> 
                        </div>
                </div>

             </form>     
            <br>
                    <div class="row">
                    <div class="col-md-4 col-xl-4">
                            <a class="block block-rounded block-link-pop bg-xinspire" href="javascript:void(0)">
                                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                    <div class="item">
                                        <i class="fa fa-2x fa fa-book text-white"></i>
                                    </div>
                                    <div class="ml-3 text-right" >
                                    <p class="text-white mb-0">
                                        จำนวนผู้บริจาค 
                                        </p>
                                        <p class="text-white mb-0" style="font-size: 2.25rem;">
                                         {{$amount_1}}
                                        </p>
                                 
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-xl-4">
                            <a class="block block-rounded block-link-pop bg-danger" href="javascript:void(0)">
                                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                    <div class="item">
                                        <i class="fa fa-2x fa fa-paper-plane text-white"></i>
                                    </div>
                                    <div class="ml-3 text-right">
                                    <p class="text-white mb-0">
                                        มูลค่าทั้งหมด (บาท)
                                        </p>
                                    <p class="text-white mb-0" style="font-size: 2.25rem;">
                                    {{number_format($amount_2,2)}}
                                        </p>
                                   
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-xl-4">
                            <a class="block block-rounded block-link-pop bg-warning" href="javascript:void(0)">
                                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                <div class="item">
                                        <i class="fa fa-2x fa fa-inbox text-white"></i>
                                    </div>
                                <div class="ml-3 text-right">
                                <p class="text-white mb-0">
                                        จำนวนครั้งบริจาค (ครั้ง)
                                        </p>
                                <p class="text-white mb-0" style="font-size: 2.25rem;">
                                {{$amount_3}}
                                        </p>
                                    
                                    </div>
                                    
                                </div>
                            </a>
                        </div>
                    
            </div>

            </div> 
            </div> 
            </div> 

            <div style="width: 95%;">
           <div class="block block-content">
            <div id="columnchart_car" style="font-family: 'Kanit', sans-serif;width: 100%; height: 500px;"></div>
            </div>
            </div>
            <br>
 </body>
@endsection

@section('footer')

<script src="{{ asset('datepicker/bootstrap-3.3.7-dist/js/bootstrap.js') }}"></script>



<script src="{{ asset('datepicker/bootstrap-3.3.7-dist/js/bootstrap.js') }}"></script>

<script src="{{ asset('google/Charts.js') }}"></script>

    
<script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['เดือน','มูลค่า (บาท)'],
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


        var options = {
            fontName: 'Kanit',
            hAxis: { slantedText: true, 
                      slantedTextAngle: 45
            },
          chart: {
            title: 'มูลค่าการบริจาค',
          }
        
        };

     
        var chart = new google.charts.Bar(document.getElementById('columnchart_car'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }

</script>


@endsection
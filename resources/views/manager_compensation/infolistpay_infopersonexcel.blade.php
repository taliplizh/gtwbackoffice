<?php
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="INFOMATION_PAYPERSON.xls"');
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
                        }

            .text-font {
        font-size: 13px;
                    }   
    }      
</style>

<script>
    function checklogin(){
    window.location.href = '{{route("index")}}'; 
    }
</script>
<?php

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
        
                <div align="left">
                <h3 class="block-title" style="font-family: 'Kanit', sans-serif;"><B>รายการรายจ่ายบุคคล >> {{$infolist->HR_PAY_NAME}}</B></h3>
                </div>
         

            </div>
       
            <div class="block-content block-content-full">
          
             <div class="table-responsive"> 
                <!-- DataTables init on table by adding .js-dataTable-simple class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                <table class="gwt-table table-striped table-vcenter js-dataTable-full" width="100%">
                    <thead style="background-color: #FFEBCD;">
                        <tr height="40">
                            <th  class="text-font" style="border-color:#F0FFFF;text-align: center;" width="5%">ลำดับ</th>
                            <th  class="text-font" style="border-color:#F0FFFF;text-align: center;" >ชื่อนามสกุล</th>
                            <th  class="text-font" style="border-color:#F0FFFF;text-align: center;" >ตำแหน่ง</th>
                            <th  class="text-font" style="border-color:#F0FFFF;text-align: center;" >หน่วยงาน</th>
                            <th  class="text-font" style="border-color:#F0FFFF;text-align: center;" >ประเภทข้าราชการ</th>
                            <th  class="text-font" style="border-color:#F0FFFF;text-align: center;" >สถานะการทำงาน</th>
                            @if($infolist->HR_PAY_CAL == 'use')
                            <th  class="text-font" style="border-color:#F0FFFF;text-align: center;" >เงินเดือน</th>
                            @endif
                            <th  class="text-font" style="border-color:#F0FFFF;text-align: center;" width="20%">จำนวนเงิน</th>
                           
                        </tr >
                    </thead>

                    <tbody>
                    <?php $count=1;?>
                     @foreach ($infolistpersons as $infolistperson)

                    
                        <tr height="20">
                            <td class="text-font" align="center">{{$count}}</td>
                            <td class="text-font text-pedding" >{{$infolistperson->HR_PREFIX_NAME}}{{$infolistperson->HR_FNAME}} {{$infolistperson->HR_LNAME}}</td> 
                            <td class="text-font text-pedding" >{{$infolistperson->POSITION_IN_WORK}}</td> 
                            <td class="text-font text-pedding" >{{$infolistperson->HR_DEPARTMENT_SUB_SUB_NAME}}</td> 
                            <td class="text-font text-pedding" >{{$infolistperson->HR_PERSON_TYPE_NAME}}</td> 
                            <td class="text-font text-pedding" >{{$infolistperson->HR_STATUS_NAME}}</td> 
                            @if($infolist->HR_PAY_CAL == 'use')
                            <td class="text-font text-pedding" >{{number_format($infolistperson->HR_SALARY,2)}}</td> 
                            @endif
                            <td class="text-font text-pedding" >
     
                            {{$infolistperson->AMOUNT}}
                            </td> 
                          
                            
  
                          
                             
                           
                        </tr>

                        <?php  $count++;?>
                   

                        @endforeach 

                    </tbody>
                    </table>
                    </div>
                    </div>
                    </div>    
                    </div>


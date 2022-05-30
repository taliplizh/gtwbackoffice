
<link  href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" />
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: 'THSarabunNew';  
            font-style: normal;
            font-weight: normal;  
            src: url("{{ asset('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';  
            font-style: normal;
            font-weight: normal; 
            src: url("{{ asset('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ asset('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
             font-weight: normal;
            src: url("{{ asset('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
        }
 
        body {
            font-family: "THSarabunNew";
            font-size: 17px;
            line-height: 1;
            padding: 10.3465pt 7.1732pt 7.1732pt 10.6929pt;
       
        }

     
        .text-pedding{
    padding-left:10px;
    padding-right:10px;
                        }

     
</style>

        
    </style>
    <?php

    function RemovethainumDigit($num){
        return str_replace(array( '0' , '1' , '2' , '3' , '4' , '5' , '6' ,'7' , '8' , '9' ),array( "o" , "๑" , "๒" , "๓" , "๔" , "๕" , "๖" , "๗" , "๘" , "๙" ),$num);
    }

    function Removeconvert($number){ 
        $txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
        $txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
        $number = str_replace(",","",$number); 
        $number = str_replace(" ","",$number); 
        $number = str_replace("บาท","",$number); 
        $number = explode(".",$number); 
        if(sizeof($number)>2){ 
        return ''; 
        exit; 
        } 
        $strlen = strlen($number[0]); 
        $convert = ''; 
        for($i=0;$i<$strlen;$i++){ 
            $n = substr($number[0], $i,1); 
            if($n!=0){ 
                if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; } 
                elseif($i==($strlen-2) AND $n==2){  $convert .= 'ยี่'; } 
                elseif($i==($strlen-2) AND $n==1){ $convert .= ''; } 
                else{ $convert .= $txtnum1[$n]; } 
                $convert .= $txtnum2[$strlen-$i-1]; 
            } 
        } 
        
        $convert .= 'บาท'; 
        if($number[1]=='0' OR $number[1]=='00' OR 
        $number[1]==''){ 
        $convert .= 'ถ้วน'; 
        }else{ 
        $strlen = strlen($number[1]); 
        for($i=0;$i<$strlen;$i++){ 
        $n = substr($number[1], $i,1); 
            if($n!=0){ 
            if($i==($strlen-1) AND $n==1){$convert 
            .= 'เอ็ด';} 
            elseif($i==($strlen-2) AND 
            $n==2){$convert .= 'ยี่';} 
            elseif($i==($strlen-2) AND 
            $n==1){$convert .= '';} 
            else{ $convert .= $txtnum1[$n];} 
            $convert .= $txtnum2[$strlen-$i-1]; 
            } 
        } 
        $convert .= 'สตางค์'; 
        } 
        return $convert; 
    } 


        function DateThaifrom($strDate)
    {
    $strYear = date("Y",strtotime($strDate))+543;
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("j",strtotime($strDate));

    $strMonthCut = Array("","01","02","03","04","05","06","07","08","09","10","11","12");
    $strMonthThai=$strMonthCut[$strMonth];
    return $strDay.'/'.$strMonthThai.'/'.$strYear;
    }


    function MonthThaifrom($strM)
    {
   

    $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม");
    $strMonthThai=$strMonthCut[$strM];
    return $strMonthThai;
    }


  $date = date("d/m/Y");
    ?>
</head>

<body>
<center>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<br>
<b style="font-size: 17px;">ใบแสดงรายละเอียดการจ่ายเงินเดือน/ค่าจ้าง/เจ้าหน้าที่ {{$infoorg->ORG_NAME}}</b>                                                                                  
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ประจำเดือน&nbsp;&nbsp;{{MonthThaifrom($infosalary->SALARYALL_MONTH_ID)}}&nbsp;&nbsp;พ.ศ.&nbsp;&nbsp; {{$infosalary->SALARYALL_YEAR_ID}}
</center>
<br>
เลขที่ตำแหน่ง : {{$infosalary->HR_POSITION_NUM}} 
<br>
ชื่อ-สกุล : {{$infosalary->SALARYALL_PERSON_NAME}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
เลขที่บัตรประชาชน : {{$infosalary->HR_CID}}
<br>
ตำแหน่ง : {{$infosalary->POSITION_IN_WORK}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
เลขที่บัญชี : {{$infosalary->SALARYALL_BOOK_NUM}}
<br><br>
                <table class="gwt-table table-striped table-vcenter js-dataTable-full" width="100%">
                    <thead >
                        <tr height="40">
                            <th  class="text-font" style="text-align: center;font-size:14px" >รายละเอียด รายรับ</th>
                            <th  class="text-font" style="text-align: center;font-size:14px" width="30%">หมายเหตุ</th>                          
                            <th  class="text-font" style="text-align: center;font-size:14px" width="15%">รายรับ</th>
                                                   
                        </tr >
                    </thead>
                    <tbody>
                    
                    @foreach ($inforeceives as $inforeceive)
                    @if($inforeceive->SALARYALL_RECEIVE_AMOUNT !== null && $inforeceive->SALARYALL_RECEIVE_AMOUNT !== '')
                        <tr>
                            <td class="text-pedding">{{$inforeceive->SALARYALL_RECEIVE_LISTNAME}}</td>
                            <td></td>
                          
                            <td class="text-pedding" style="text-align: right;">{{number_format($inforeceive->SALARYALL_RECEIVE_AMOUNT,2)}}</td>
                         
                           
                                                   
                        </tr>
                        @endif
                        @endforeach  
                    </tbody>
                </table><br>

                <table class="gwt-table table-striped table-vcenter js-dataTable-full" width="100%">
                    <thead >
                        <tr height="40">
                            <th  class="text-font" style="text-align: center;font-size:14px" >รายละเอียด รายจ่าย</th>
                            <th  class="text-font" style="text-align: center;font-size:14px" width="30%">หมายเหตุ</th>                          
                            <th  class="text-font" style="text-align: center;font-size:14px" width="15%">รายจ่าย</th>
                                                   
                        </tr >
                    </thead>
                    <tbody>

                    
                    @foreach ($infopays as $infopay)
                    @if($infopay->SALARYALL_PAY_AMOUNT !== null && $infopay->SALARYALL_PAY_AMOUNT!== '')
                        <tr>
                            <td class="text-pedding">{{$infopay->SALARYALL_PAY_LISTNAME}}</td>
                            <td></td>
                          
                            <td class="text-pedding" style="text-align: right;">{{number_format($infopay->SALARYALL_PAY_AMOUNT,2)}}</td>
                           
                    
                         
                                                   
                        </tr>

                        @endif
                        @endforeach 
                    </tbody>
                </table>

<br>
<div style="text-align: right;">
รวมรับทั้งเดือน {{number_format($suminforeceive,2)}}   บาท<br>
รวมจ่ายทั้งเดือน {{number_format($suminfopay,2)}} บาท<br>
รับสุทธิ {{number_format($infosalary->SALARYALL_TOTAL,2)}} บาท<br>


จำนวนยอดสุทธิ &nbsp;(ตัวอักษร)&nbsp;: &nbsp;{{convert(number_format($infosalary->SALARYALL_TOTAL,2))}}
</div>
<br>
<br>
            
<table  width="100%">      
    <tr>  
        <td width="10%"> </td>    
        <td width="90%" >
           
            ลงชื่อ......................................................เจ้าหน้าที่การเงิน
        </td>
    </tr>
    <tr>     
        <td width="60%"> </td>    
        <td width="40%">           
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             ( {{ DateThaifrom($infosalary->created_at) }} )            
        </td>
    </tr>               
</table> 

</body>
</html>
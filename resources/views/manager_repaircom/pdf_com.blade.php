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
        body {
            font-family: "THSarabunNew";
            font-size: 20px;
            line-height: 0.8;   
        }    
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

    $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];
    return thainumDigit($strDay.' '.$strMonthThai.'  พ.ศ. '.$strYear);
  }


    ?>
</head>
<body>

    <B style="font-size: 25px;">ใบแจ้งซ่อมอุปกรณ์คอมพิวเตอร์ </B>
    
    <br><br><br><br>

<table style="line-height: 1;"> <!-- only added this -->
    <tr>
        <td style="font-size: 22px;width:300px"><b></b></td>
        <td style="width:100px"></td>
        <td style="font-size: 22px;width:200px"><b>ใบแจ้งซ่อมเลขที่</b> {{$informcomrepair->REPAIR_ID}}</td>
      </tr> 
      <tr>
        <td style="font-size: 22px;width: 300px"><b> </b></td>
        <td style="font-size: 22px;width: 250px"><b> </b> </td>
        <td style="font-size: 22px;width: 100px"><b>วันเวลาที่แจ้ง</b> {{DateThai($informcomrepair->DATE_SAVE)}}</td>
      </tr> 
      <tr>
        <td style="font-size: 22px;width:300px" colspan="3"><b>เรียน หัวหน้าศูนย์คอมพิวเตอร์ </b></td>
       
      </tr> 
</table>

<br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ด้วยหน่วยงาน  {{$informcomrepair->HR_DEPARTMENT_SUB_SUB_NAME}}<br><br>
        มีความประสงค์ส่งซ่อมอุปกรณ์  {{$informcomrepair->REPAIR_NAME}}<br><br>
        ครุภัณฑ์เลขที่ {{$informcomrepair->ARTICLE_NUM}}<br><br>
        ชื่อครุภัณฑ์ {{$informcomrepair->ARTICLE_NAME}}<br><br>
        <b> สาเหตุ / หรืออาการ</b> {{$informcomrepair->SYMPTOM}}


<table style="line-height: 2;">
    <tr>
        <td style="font-size: 22px;width:300px"><b></b></td>
        <td style="font-size: 20px;width:250px">ลงชื่อ</td>
        <td style="font-size: 20px;width:50px">ผู้ส่งซ่อม</td>
      </tr> 
      <tr>
        <td style="font-size: 22px;width:300px"><b></b></td>
        <td style="font-size: 20px;width:250px">ลงชื่อ</td>
        <td style="font-size: 20px;width:50px">ผู้รับซ่อม</td>
      </tr>      
</table>

<hr>
<br>
<b>หมายเหตุ</b><br>
[&nbsp;&nbsp;&nbsp;&nbsp;] ส่งซ่อมปกติ<br><br>
[&nbsp;&nbsp;&nbsp;&nbsp;] ส่งซ่อมย้อนหลัง ช่างดำเนินการซ่อมไห้ก่อน<br><br><hr>

<b>บันทึกงานซ่อมคอมพิวเตอร์</b><br><br>
@if($informrepairindex->OUTSIDE_COMMENT <> '')
[&nbsp;&nbsp;&nbsp;&nbsp;] ซ่อมได้  &nbsp;&nbsp;&nbsp;&nbsp; [&nbsp;/&nbsp;] ส่งซ่อมภายนอก   &nbsp;&nbsp;&nbsp;&nbsp;  [&nbsp;&nbsp;&nbsp;&nbsp;] ชำรุดขอจำหน่าย
<br>เหตุผลที่ส่งซ่อม : {{$informrepairindex->OUTSIDE_COMMENT}}
<br>อุปกรณ์ที่ติดไป : {{$informrepairindex->OUTSIDE_TOOL}}
<br>ส่งซ่อมที่ร้าน : {{$informrepairindex->OUTSIDE_SHOP}}
<br>ผู้รับสิ่งของ : {{$informrepairindex->OUTSIDE_EMP}}
<br>มูลค่า : .......................................
<hr>
@else
[&nbsp;&nbsp;&nbsp;&nbsp;] ซ่อมได้  &nbsp;&nbsp;&nbsp;&nbsp; [&nbsp;&nbsp;&nbsp;&nbsp;] ส่งซ่อมภายนอก   &nbsp;&nbsp;&nbsp;&nbsp;  [&nbsp;&nbsp;&nbsp;&nbsp;] ชำรุดขอจำหน่าย <br><br>
@endif


ปัญหาที่พบในระหว่างการซ่อม.....................................................................<br>
กระบวนการ / แนวทางแก้ไข.....................................................................<br>

<hr>

<b>บันทึกฝ่ายบริหารทั่วไป</b><br><br>
[&nbsp;&nbsp;&nbsp;&nbsp;] อนุมัติ    
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
ลงชื่อ  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
ผู้ตรวจเช็ค<br><br>
[&nbsp;&nbsp;&nbsp;&nbsp;] ไม่อนุมัติ 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
วันที่ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
เวลา
<br><br>

<table style="line-height: 2;">
    <tr>
        <td style="font-size: 22px;width:350px"><b></b></td>
        <td style="font-size: 24px;width:100px">ลงชื่อ</td>
     
      </tr> 
      <tr>
        <td style="font-size: 22px;width:270px"><b></b></td>
        <td style="font-size: 20px;width:50px"></td>
        <td style="font-size: 24px;width:350px">หัวหน้าพัสดุ</td>
      </tr>      
</table>

</body>
</html>
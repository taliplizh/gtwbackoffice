
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

 
        body {
            font-family: "THSarabunNew";
            font-size: 18px;
            line-height: 1;
            padding: 28.3465pt 7.1732pt 7.1732pt 56.6929pt;
       
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
<center>
<img src="image/Garuda.png" width="100" height="100"><BR>
<B style="font-size: 20px;">คำสั่ง{{$infoorg->ORG_NAME}}<BR>ที่&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/{{thainumDigit(date('Y')+543)}}</B><BR>

เรื่อง แต่งตั้งคณะกรรมการกำหนดรายละเอียดคุณลักษณะเฉพาะ<br>
และหลักเกณฑ์การพิจารณาคัดเลือกข้อเสนอของ{{$inforcon->SUP_TYPE_NAME}}<br>
--------------------------------------------------------------------------</center>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ด้วย{{$infoorg->ORG_NAME}} มีความประสงค์จะดำเนินการซื้อ
{{$inforcon->SUP_TYPE_NAME}} และเพื่อให้เป็นระเบียบกระทรวงสาธารณสุข ว่าด้วยวิธีปฏิบัติเกี่ยวกับการจัดซื้อจัดจ้างและพัสดุโดยใช้เงินบริจาค
ของหน่วยบริการในสังกัดกระทรวงสาธารณสุข พ.ศ.๒๕๖๑ จึงขอแต่งตั้งรายชื่อต่อไปนี้เป็นคณะกรรมการกำหนด รายละเอียดคุณลักษณะเฉพาะและหลักเกณฑ์การพิจารณาคัดเลือกข้อเสนอของ
 {{$inforcon->SUP_TYPE_NAME}} ประกอบด้วย
<br>
<br>
<table>
@foreach ($conboards as $conboard)
<tr>
<td>{{$conboard->BOARD_HR_NAME}}</td>
<td>{{$conboard->BOARD_HR_POSITION}}</td>
<td>{{$conboard->POSITION_NAME}}</td>
</tr>
@endforeach 
</table>

 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ให้คณกรรมการฯ ตามรายนามข้างต้น กำหนดคุณลักษณะเฉพาะของพัสดุ รวมทั้งกำหนด
 หลักเกณฑ์การพิจารณาคัดเลือกข้อเสนอ ดำเนินการตามแนวทางระเบียบของราชการ แล้วรายงานให้ทราบเพื่อพิจารณาเห็นชอบต่อไป<br><br><br><br>    

 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สั่ง ณ วันที่<br><br><br><br>     
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;({{$infoorg->HR_PREFIX_NAME}}{{$infoorg->HR_FNAME}} {{$infoorg->HR_LNAME}})<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้อำนวยการ{{$infoorg->ORG_NAME}}<BR>


</body>
</html>
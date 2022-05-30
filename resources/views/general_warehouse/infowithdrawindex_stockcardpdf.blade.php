
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
            font-size: 18px;
            line-height: 1;
            /* padding: 15.3465pt 15.1732pt 15.1732pt 15.6929pt; */
            right: 15px;
        }

      
    table, th, td {
    border: 1px solid black;
    }

    .text-pedding{
        padding-left:10px;
        padding-right:10px;
                            }

                .text-font {
            font-size: 18px;
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

        $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return thainumDigit($strDay.' '.$strMonthThai.'  พ.ศ. '.$strYear);
        }


    ?>

</head>

<body>

<center><B style="font-size: 24px;">บัญชีวัสดุ</B></center><BR>
<br>
<label for="">  &nbsp;&nbsp; </label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
@foreach ($info_orgs as $info_org)
<label for="">ชื่อหน่วยงาน :&nbsp;&nbsp; {{$info_org-> ORG_NAME}}</label>
@endforeach
<br>
<label for=""> ประเภท &nbsp;&nbsp; </label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label for="">ชื่อวัสดุ : &nbsp;&nbsp; </label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label for="">รหัสวัสดุ : &nbsp;&nbsp; </label>
<br>
<label for=""> หน่วย &nbsp;&nbsp; </label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label for="">หน่วยตัดสต็อค : &nbsp;&nbsp; </label>

<table style="width: 700px"> 
    <tr>
        <th style="width: 40px" rowspan="2"><center>วันที่</center></th>
        <th style="width: 80px" rowspan="2"><center>รายการ(รับจาก-จ่ายให้)</center></th>
        <th style="width: 40px" rowspan="2"><center>เลขที่เอกสาร</center></th>
        <th style="width: 20px" rowspan="2"><center>ราคาต่อ <br> หน่วย</center></th>  

        <th style="width: 40px" colspan="2"><center>รับ</center></th>  

        <th style="width: 40px" colspan="2"><center>จ่าย</center></th>  

        <th style="width: 40px" colspan="2"><center>คงเหลือ</center></th>

        <th style="width: 40px" rowspan="2"><center>หมายเหตุ</center></th>
    </tr>
    <tr>
        <th style="width: 20px"><center>จำนวน</center></th>
        <th style="width: 20px"><center>ราคา</center></th>     
        <th style="width: 20px"><center>จำนวน</center></th>
        <th style="width: 20px"><center>ราคา</center></th> 
        <th style="width: 20px"><center>จำนวน</center></th>
        <th style="width: 20px"><center>ราคา</center></th>     
    </tr>
  
    
            {{-- @foreach ($inforwarehouserequests as $inforwarehouserequest)   --}}
              

    <tr>
        <td style="width: 40px; word-break:break-all; word-wrap:break-word;" class="text-font text-pedding"><center></center></td>
        <td style="width: 80px; word-break:break-all; word-wrap:break-word;" class="text-font text-pedding">  </td>
        <td style="width: 40px; word-break:break-all; word-wrap:break-word;" class="text-font text-pedding">  </td>
        <td style="width: 20px; word-break:break-all; word-wrap:break-word;" class="text-font text-pedding">  </td>

        <td style="width: 20px; word-break:break-all; word-wrap:break-word;text-align: right" class="text-font text-pedding"> </td>           
        <td style="width: 20px; word-break:break-all; word-wrap:break-word;" class="text-font text-pedding">   </td>
       
        <td style="width: 20px; word-break:break-all; word-wrap:break-word;text-align: right" class="text-font text-pedding"></td>
        <td style="width: 20px; word-break:break-all; word-wrap:break-word;text-align: right" class="text-font text-pedding"></td>
        <td style="width: 20px; word-break:break-all; word-wrap:break-word;text-align: right" class="text-font text-pedding"></td>

        <td style="width: 20px; word-break:break-all; word-wrap:break-word;text-align: right" class="text-font text-pedding"></td>
        <td style="width: 20px; word-break:break-all; word-wrap:break-word;text-align: right" class="text-font text-pedding"></td>
       
      {{-- @endforeach  --}}
     {{-- {{thainumDigit(number_format($infocon->SUP_TOTAL))}} --}}
      {{-- {{thainumDigit(number_format($infocon->PRICE_SUM,2))}} --}}  {{-- {{thainumDigit(number_format($infocon->PRICE_PER_UNIT,2))}} --}}
    </tr>
 
</table>

<br>
<br>
</body>
</html>
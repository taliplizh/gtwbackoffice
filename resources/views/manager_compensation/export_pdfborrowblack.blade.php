
<link  href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" />
<html>
<head>

<style>
        @font-face {
            font-family: 'THSarabunNew';  
            font-style: normal;
            font-weight: normal;  
            src: url("{{ asset('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

      
 
        body {
            font-family: "THSarabunNew";
            font-size: 18px;
            line-height: 0.8;
            /* padding: 28.3465pt 7.1732pt 7.1732pt 56.6929pt; */
            padding: 7.00pt 7.1732pt 7.1732pt 7.00pt;  
       
        }


        .text-pedding{
    padding-left:10px;
    padding-right:10px;
                        }
                        B {
        font-size: 11px;
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
    return $strDay.' '.$strMonthThai.'  พ.ศ. '.$strYear;
    }

  $date = date("d/m/Y");

  

  function DateThaifrom2($strDate)
  {
  $strYear = date("Y",strtotime($strDate))+543;
  $strMonth= date("n",strtotime($strDate));
  $strDay= date("j",strtotime($strDate));

  $strMonthCut = Array("","01","02","03","04","05","06","07","08","09","10","11","12");
  $strMonthThai=$strMonthCut[$strMonth];
  return $strDay.'/'.$strMonthThai.'/'.$strYear;
  }

?>
</head>

<body>
<center>
<B style=" font-size: 14px;">รายการส่งใช้เงินยืม</B></center>
<br>

        <table  border="1" style="width: 100%;">
                 <tr>
                    <td class="text-pedding"  rowspan="2">ครั้งที่</td>
                    <td class="text-pedding"  rowspan="2" width="100px">วัน เดือน ปี</td>
                    <td class="text-pedding"  colspan="3">รายการส่งใช้</td>
                    <td class="text-pedding"  rowspan="2" colspan="2">คงค้าง</td>
                    <td class="text-pedding"  rowspan="2">ลายมือชื่อผู้รับ</td>
                    <td class="text-pedding"  rowspan="2">ใบรับเลขที่</td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding"   width="60px">เงินสดหรือใบสำคัญ</td>
                    <td class="text-pedding"   width="60px"  colspan="2">จำนวนเงิน</td>
              
                                         
                </tr>

                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                <tr>
                    <td class="text-pedding" ><br><br></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" width="50px"></td>
                    <td class="text-pedding" width="10px"></td>
                    <td class="text-pedding" ></td>
                    <td class="text-pedding" ></td>
                                         
                </tr>
                
         
                
            </tbody>
        </table>
  
        <B style=" font-size: 12px;">หมายเหตุ</B><br>
        (๑) ยื่นต่อ ผู้อำนวยการกองคลัง หัวหน้ากองคลัง หัวหน้าแผนกคลัง หรือตำแหน่งอื่นใดที่ปฎิบัติงาน เช่นเดียวกันแล้วแต่กรณี <br>
        (๒) ให้ระบุชื่อส่วนราชการที่จ่ายเงินยืม<br>
        (๓) ระบุวัตถุประสงค์ที่จะนำเงินยืมไปใช้จ่าย<br>
        (๔) เสนอต่อผู้มีอำนาจอนุมัติ


</div>


            

<script src="{{ asset('assets/js/jquery.metisMenu.js') }}"></script>
    <!-- Morris Chart Js -->
    <script src="{{ asset('assets/js/morris/raphael-2.1.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/morris/morris.js') }}"></script>
	
	
	<script src="{{ asset('assets/js/easypiechart.js') }}"></script>
	<script src="{{ asset('assets/js/easypiechart-data.js') }}"></script>
	
	 <script src="{{ asset('assets/js/Lightweight-Chart/jquery.chart.js') }}"></script>
	
    <!-- Custom Js -->
    <script src="{{ asset('assets/js/custom-scripts.js') }}"></script>
</body>
</html>
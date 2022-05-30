<?php
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="INFOMATION_PERSON.xls"');//ชื่อไฟล์

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

use Illuminate\Support\Facades\DB;
use App\Person;
use App\Incidence;
use App\Risk_internalcontrol;
use App\Risk_internalcontrol_sub;
use App\Risk_internalcontrol_subsub;
use App\Riskrep;
use App\Risk_setupincidence_level;
?>

ข้อมูลบุคลากรจำนวน   คน
      

<table  width="100%">
    <thead >        
        <tr style="background-color: #FFEBCD;" height="40">  
            <th class="text-font" style="text-align: center;">ภารกิจตามกฎหมายที่จัดตั้งหน่วยงานของรัฐ <br>  หรือภารกิจตามแผนการดำเนินการหรือภารกิจอื่น  <br> ที่สำคัญของหน่วยงานของรัฐ/วัตถุประสงค์</th>
            <th class="text-font" style="text-align: center;">การควบคุมภายในที่มีอยู่</th> 
            <th class="text-font" style="text-align: center;">ความเสี่ยงที่ยังมีอยู่</th> 
            <th class="text-font" style="text-align: center;">การปรับปรุงการควบคุมภายใน</th> 
            <th class="text-font" style="text-align: center;">หน่วยงานที่รับผิดชอบ</th> 
            <th class="text-font" style="text-align: center;">สถานะดำเนินการ</th>
            <th class="text-font" style="text-align: center;">วิธีติดตาม และ <br>สรุปผลการประเมินข้อคิดเห็น</th>
        </tr>
    </thead>
    <tbody>       
        <tr height="40">    
            <td class="text-font">ทดสอบ </td>
            <td class="text-font">ทดสอบ</td>
            <td class="text-font">ทดสอบ </td>
            <td class="text-font">ทดสอบ</td>
            <td class="text-font">ทดสอบ </td>
            <td class="text-font">ทดสอบ</td>
            <td class="text-font">ทดสอบ</td>
        </tr>
    </tbody>
</table>

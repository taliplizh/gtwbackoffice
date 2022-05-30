<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
    @font-face {
                font-family: 'THSarabunNew';
                src: url('fonts/thsarabunnew-webfont.eot');
                src: url('fonts/thsarabunnew-webfont.eot?#iefix') format('embedded-opentype'),
                    url('fonts/thsarabunnew-webfont.woff') format('woff'),
                    url('fonts/thsarabunnew-webfont.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
    
            }
            
            @font-face {
                font-family: 'THSarabunNew';
                src: url('fonts/thsarabunnew_bolditalic-webfont.eot');
                src: url('fonts/thsarabunnew_bolditalic-webfont.eot?#iefix') format('embedded-opentype'),
                    url('fonts/thsarabunnew_bolditalic-webfont.woff') format('woff'),
                    url('fonts/thsarabunnew_bolditalic-webfont.ttf') format('truetype');
                font-weight: bold;
                font-style: italic;
    
            }
    
            @font-face {
                font-family: 'THSarabunNew';
                src: url('fonts/thsarabunnew_italic-webfont.eot');
                src: url('fonts/thsarabunnew_italic-webfont.eot?#iefix') format('embedded-opentype'),
                    url('fonts/thsarabunnew_italic-webfont.woff') format('woff'),
                    url('fonts/thsarabunnew_italic-webfont.ttf') format('truetype');
                font-weight: normal;
                font-style: italic;
    
            }
    
            @font-face {
                font-family: 'THSarabunNew';
                src: url('fonts/thsarabunnew_bold-webfont.eot');
                src: url('fonts/thsarabunnew_bold-webfont.eot?#iefix') format('embedded-opentype'),
                    url('fonts/thsarabunnew_bold-webfont.woff') format('woff'),
                    url('fonts/thsarabunnew_bold-webfont.ttf') format('truetype');
                font-weight: bold;
                font-style: normal;
    
            }
            body {
                font-family: 'THSarabunNew', sans-serif;
                font-size: 13.5px;
                line-height: 1;
              
           
            }
    
    
    .text-pedding{
        padding-left:10px;
        padding-right:10px;
    }   
    /* table, th, td {
         border: 1px solid #000;
    }
    .non-table{
         border: px solid #000;
    } */
    table{
         border-collapse: collapse;  //กรอบด้านในหายไป
    }
            
        </style>
        
    <?php
    
    
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
    <B style="font-size: 22px;">ใบสั่งซื้อ/สั่งจ้าง</B><BR><BR></center>
    
    <table >
      <tr>
            <td style="width: 350px">ผู้ขาย/ผู้รับจ้าง  {{isset($inforconquotation->VENDOR_NAME) ? $inforconquotation->VENDOR_NAME : ''}}</td>
            <td style="width: 350px">  ใบสั่งซื้อ/สั่งจ้างเลขที่ {{thainumDigit($inforcon->PO_NUM)}}</td>
      </tr>
    </table>
    
    <table style="border: 1px solid black; width: 100%;"> <!-- only added this -->
        <tr>
            <th style="width: 50px; border: 1px solid black;text-align: center;" colspan="6">ใบเบิก</th>
            <th style="width: 50px; border: 1px solid black;text-align: left;" colspan="4">แผ่นที่:  &nbsp;&nbsp;&nbsp;ในจำนวน: </th>
        </tr>
        <tr>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: left; width: 10px;"  rowspan="2">จาก</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: left; width: 50px;"  colspan="2" rowspan="2">จ่ายหน่วย:</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: left; width: 30px;"  colspan="3" rowspan="2">ที่:</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: left; width: 50px;"  colspan="4">สายงานที่ควบคุม:</th>
        </tr>
        <tr>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: left; width: 50px;;" colspan="4">ประเภทสิ่งอุปกรณ์:</th>
        </tr>
        <tr>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: left; width: 10px;" rowspan="3">ถึง</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: left; width: 50px;"  colspan="2" rowspan="3">หน่วยเบิก: </br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<p style="white-space: pre;">เบิกให้:</P></th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: left; width: 45px;"  colspan="3">เบิกใบกรณี:</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: left; width: 50px;"  colspan="4">ประเภทเงิน: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; บาท</th>
        </tr>
        <tr>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 15px;">ขึ้นต้น</th>
            <th style="word-break:break-all; word-wrap:break-word; border: 1px solid black;text-align: center; width: 15px;">ทดแทน</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 15px;">พิเศษ</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;;text-align: left; width: 50px;"  rowspan="2" colspan="4">เลขที่งาน: </th>
        </tr>
        <tr>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 10px;">1</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 10px;">2</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 10px;">3</th>
        </tr>
       <tr>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 10px;">ลำดับ</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center;width: 50px;">หมายเลขสิ่งอุปกรณ์</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 70px;">รายการ</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center;width: 20px;">จำนวน อนุมัติ</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center;width: 20px;">คงคลัง คงค้าง คงจ่าย</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 20px;">หน่วยนับ</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 20px;">จำนวน เบิก</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 20px;">ราคา หน่วยละ</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 20px;">ราคารวม</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 20px;">จ่ายจริง/ ค้างจ่าย</th>
        </tr>
        <tr>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 10px;">1</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center;width: 50px;">...........</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 70px;">..</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center;width: 20px;">..</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center;width: 20px;">..</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 20px;">..</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 20px;">..</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 20px;">..</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 20px;">..</th>
            <th style="word-break:break-all; word-wrap:break-word;border: 1px solid black;text-align: center; width: 20px;">..</th>
        </tr>
    </table>
    <p style="text-transform: lowercase;font-size: 7px;">หลักฐานที่ใช้เบิก: -หนังสือ ด่วนมาก ที่ กห 0401/0352 ลง 1 ก.ย. 63 เรื่องการเบิกรับ สป. สาย ส. รายการเครื่องอ่านข้อมูลบัตรประชาชน Smartcard Reader</p>
<hr>
    <table style="border: none; width:100%;">
        <tr>
            <td style="word-break:break-all; word-wrap:break-word;border: none;text-align: left; width: 100px;">
        
                <p>ตรวจแล้วเห็นว่า......................................................
                ....................................................................................</P>
                <p style="text-align: center;">
                ...................................................................<br>
                (ลงนาม) ผู้ตรวจสอบ</p>
                <p style="text-align: center;">
                วันที่  {{DateThaifrom($inforcon->PO_DATE)}}</p>
            </td>
            <td style="word-break:break-all; word-wrap:break-word;border: none;text-align: left; width: 100px;">
        
                <p  style="text-transform: lowercase;">ขอเบิกสิ่งอุปกรณ์ตามที่ระบุไว้ในช่อง"จำนวนเบิก"และขอมอบให้ จ.ส.อ. มะปราง นิโรจน์,ส.ต. ฐิติวัฒน์ สีขาว เป็นผู้รับแทน</P>

                <p style="text-align: center;">
                ...................................................................<br>
                (ลงนาม) ผู้เบิก</p>
                <p style="text-align: center;">
                วันที่  {{DateThaifrom($inforcon->PO_DATE)}}</p>
            </td>
        </tr>
        <tr>
            <td style="word-break:break-all; word-wrap:break-word;border: none;text-align: left; width: 100px;">
        
                <p style="text-transform: lowercase;">อนุมัติให้จ่ายได้เฉพาะในรายการและจำนวนที่ ผู้ตรวจสอบเสนอ</P>
                <p style="text-align: center;">
                ...................................................................<br>
                (ลงนาม) ผู้สั่งจ่าย</p>
                <p style="text-align: center;">
                วันที่  {{DateThaifrom($inforcon->PO_DATE)}}</p>
            </td>
            <td style="word-break:break-all; word-wrap:break-word;border: none;text-align: left; width: 100px;">
        
                <p  style="text-transform: lowercase;">ได้รับสิ่งอุปกรณ์ตามรายการและจำนวนที่แจ้งไว้</P>

                <p style="text-align: center;">
                ...................................................................<br>
                (ลงนาม) ผู้รับ</p>
                <p style="text-align: center;">
                วันที่  {{DateThaifrom($inforcon->PO_DATE)}}</p>
            </td>
        </tr>
        <tr>
            <td style="word-break:break-all; word-wrap:break-word;border: none;text-align: left; width: 100px;">
        
                <p style="text-transform: lowercase;">ได้จ่ายตามรายการและจำนวนที่แจ้งไว้ใน"จ่ายจริง"แล้ว</P>
                <p style="text-align: center;">
                ...................................................................<br>
                (ลงนาม) ผู้สั่งจ่าย</p>
                <p style="text-align: center;">
                วันที่  {{DateThaifrom($inforcon->PO_DATE)}}</p>
            </td>
            <td style="word-break:break-all; word-wrap:break-word;border: none;text-align: left; width: 100px;">
        
                <p style="text-transform: lowercase;">ทะเบียนหน่วยจ่าย:...............</P>
                    ..................................................
            </td>
        </tr>
    
    </table>
    </body>
    </html>
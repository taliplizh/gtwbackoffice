
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
    
          
        table, th, td {
        border: 1px solid black;
        }
    
    
        .text-pedding{
        padding-left:10px;
        padding-right:10px;
    }   
                  
    table{
         border-collapse: collapse;  //?????????????????????????????????????????????
    }
                
            </style>
    
        <?php
    
          
    
    
            function DateThaimount($strDate)
            {
            $strYear = date("Y",strtotime($strDate))+543;
            $strMonth= date("n",strtotime($strDate));
            $strDay= date("j",strtotime($strDate));
    
            $strMonthCut = Array("","??????????????????","??????????????????????????????","??????????????????","??????????????????","?????????????????????","????????????????????????","?????????????????????","?????????????????????","?????????????????????","??????????????????","???????????????????????????","?????????????????????");
            $strMonthThai=$strMonthCut[$strMonth];
            return thainumDigit($strMonthThai);
            }
            
            function DateThaifrom($strDate)
            {
            $strYear = date("Y",strtotime($strDate))+543;
            $strMonth= date("n",strtotime($strDate));
            $strDay= date("j",strtotime($strDate));
    
            $strMonthCut = Array("","??????????????????","??????????????????????????????","??????????????????","??????????????????","?????????????????????","????????????????????????","?????????????????????","?????????????????????","?????????????????????","??????????????????","???????????????????????????","?????????????????????");
            $strMonthThai=$strMonthCut[$strMonth];
            return thainumDigit($strDay.' '.$strMonthThai.'  ???.???. '.$strYear);
            }
    
            $date = date('Y-m-d');
    
        ?>
    
    </head>
    
    <body>
    
    <center><B style="font-size: 20px;">?????????????????????????????????</B></center><br>
    <label for=""> ???????????????????????????????????? &nbsp;{{$inforwarehouserequests->HR_DEPARTMENT_SUB_SUB_NAME}}</label>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for="">??????????????????????????????????????????????????? :&nbsp;&nbsp; {{$inforwarehouserequests->WAREHOUSE_REQUEST_CODE}}</label>
    <br>    
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for="">?????????????????? : &nbsp;{{DateThai($inforwarehouserequests->WAREHOUSE_DATE_WANT)}} </label>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ???????????? &nbsp;{{$inforwarehouserequests->HR_DEPARTMENT_SUB_SUB_NAME}}&nbsp;&nbsp;&nbsp;
    ?????????????????????????????????????????????????????????????????????????????????&nbsp; ???????????????????????????&nbsp;&nbsp;{{$inforwarehouserequests->WAREHOUSE_REQUEST_BUY_COMMENT}}</label><br>
    <label for=""> ??????????????????????????????????????????????????????????????????????????????????????????????????????????????? ?????????????????????????????? &nbsp;&nbsp; {{DateThai($inforwarehouserequests->WAREHOUSE_DATE_WANT)}} &nbsp;&nbsp; ?????????????????????????????????????????????</label>
    <br>
    
    <br>
    
    <table style="width: 860px"> 
        <tr>
            <th style="width: 15px" rowspan="2"><center>?????????</center></th>
           
            <th style="width: 250px" rowspan="2"><center>??????????????????</center></th>
    
            <th style="width: 110" colspan="3"><center>???????????????</center></th>  
    
            <th style="width: 60" rowspan="2"><center>????????????/ <br> ???????????????</center></th>  
            <th style="width: 60" rowspan="2"><center>?????????????????????</center></th>
            <th style="width: 150px" rowspan="2"><center>????????????????????????</center></th>
        </tr>
        <tr>
            <th style="width: 10px"><center>???????????????</center></th>
            <th style="width: 10px"><center>????????????</center></th>
            <th style="width: 10px"><center>?????????????????????</center></th>
        </tr>
    
           <?php $number = 0; ?>
                @foreach ($warehouserequests as $warehouserequest)  
                    <?php $number++;?>
    
        <tr>
            <td style="width: 7px; word-break:break-all; word-wrap:break-word;" class="text-font text-pedding"><center>{{$number}}</center></td>
            <td style="width: 250px; word-break:break-all; word-wrap:break-word;" class="text-font text-pedding"> {{ $warehouserequest->SUP_NAME }}  </td>
    
            <td style="width: 20px; word-break:break-all; word-wrap:break-word;text-align: center" class="text-font text-pedding">{{$warehouserequest->SUP_UNIT_NAME}}</td>           
            <td style="width: 20px; word-break:break-all; word-wrap:break-word;text-align: right" class="text-font text-pedding">{{ $warehouserequest->WAREHOUSE_REQUEST_SUB_AMOUNT }}  </td>
            <td style="width: 20px; word-break:break-all; word-wrap:break-word;text-align: right" class="text-font text-pedding">{{$warehouserequest->WAREHOUSE_REQUEST_SUB_AMOUNT_PAY }}  </td>
    
            <td style="width: 20px; word-break:break-all; word-wrap:break-word;text-align: right" class="text-font text-pedding">{{ number_format($warehouserequest->WAREHOUSE_REQUEST_SUB_PRICE,2)}}  </td>
            <td style="width: 40px; word-break:break-all; word-wrap:break-word;text-align: right" class="text-font text-pedding">{{number_format( $warehouserequest->WAREHOUSE_REQUEST_SUB_SUM_PRICE,2)}}</td>
            <td style="width: 20px; word-break:break-all; word-wrap:break-word;text-align: right" class="text-font text-pedding"></td>
          @endforeach 
             
        </tr>
        <tr>
            <th style="width: 500px; word-break:break-all; word-wrap:break-word;text-align: right" colspan="6">?????????????????????&nbsp;&nbsp;</th> 
        <th style="width: 20px;word-wrap:break-word;text-align: right" class="text-font text-pedding">{{number_format($warehouserequest_sum,2)}}</th> 
            <th style="width: 20px"></th> 
            
        </tr>
    
    </table>
    
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for=""> ?????????????????? :................................................
        ?????????????????????</label>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for=""> ?????????????????? :................................................????????????????????????????????????</label>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for=""> ( ......................................................)</label>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for=""> (......................................................)</label>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label for="">{{DateThai($inforwarehouserequests->WAREHOUSE_DATE_WANT)}}</label>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="">@if($inforwarehouserequests->WAREHOUSE_PAYDAY <> '' && $inforwarehouserequests->WAREHOUSE_PAYDAY <> null){{DateThai($inforwarehouserequests->WAREHOUSE_PAYDAY)}}@endif</label>
    <br>    
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp; &nbsp;&nbsp;    
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for=""> ?????????????????? :..........................................................
    ?????????????????????????????????</label>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for=""> ?????????????????? :...............................................?????????????????????????????????</label>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for=""> (  ......................................................)</label>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for=""> (  ......................................................)</label>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="">@if($inforwarehouserequests->WAREHOUSE_PAYDAY <> '' && $inforwarehouserequests->WAREHOUSE_PAYDAY <> null){{DateThai($inforwarehouserequests->WAREHOUSE_PAYDAY)}}@endif</label>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label for=""> @if($inforwarehouserequests->WAREHOUSE_PAYDAY <> '' && $inforwarehouserequests->WAREHOUSE_PAYDAY <> null){{DateThai($inforwarehouserequests->WAREHOUSE_PAYDAY)}}@endif</label>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    <br>
    
    <br>
    </body>
    </html>
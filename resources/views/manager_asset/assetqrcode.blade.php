<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> 
    <link href='https://fonts.googleapis.com/css?family=Kanit&subset=thai,latin' rel='stylesheet' type='text/css'>
<style>

    body {
        font-family: 'Kanit', sans-serif;
        font-size: 14px;   
        }

   
</style>
<?php
function Removeformate($strDate)
  {
    $strYear = date("Y",strtotime($strDate))+543;
    $strMonth= date("m",strtotime($strDate));
    $strDay= date("d",strtotime($strDate));
  
    
    return $strDay."/".$strMonth."/".$strYear;
    }

    use SimpleSoftwareIO\QrCode\Facades\QrCode;

    ?>
<body onload="window.print()">
    
    <table> 
        <tr >
            <td> 


                {!! QrCode::size(112)->encoding('UTF-8')
                ->generate(asset('/manager_asset/assetinfomation/'.$infoasset->ARTICLE_ID));
                !!}

                {{-- {!! QrCode::size(112)->encoding('UTF-8')
                ->generate($infoasset->ARTICLE_NUM.'  
'.$infoasset->YEAR_ID.'            
'.$infoasset->ARTICLE_NAME.'                                         
'.$infoasset->ARTICLE_PROP.'
'.$infoasset->SUP_UNIT_NAME.'                                        
'.$infoasset->SERIAL_NO.'
'.$infoasset->BRAND_NAME.'
'.$infoasset->COLOR_NAME.'
'.$infoasset->MODEL_NAME.'
'.$infoasset->SIZE_NAME.'
'.number_format($infoasset->PRICE_PER_UNIT,2).'                     
'.formate($infoasset->RECEIVE_DATE).'                             
'.$infoasset->METHOD_NAME.'
'.$infoasset->BUY_NAME.'
'.$infoasset->BUDGET_NAME.'
'.$infoasset->SUP_TYPE_NAME.'
'.$infoasset->DECLINE_NAME.'
'.$infoasset->VENDOR_NAME.'
'.$infoasset->HR_DEPARTMENT_SUB_SUB_NAME.'
'.$infoasset->LOCATION_NAME.'
'.$infoasset->LOCATION_LEVEL_NAME.'
'.$infoasset->LEVEL_ROOM_NAME.'
'.$infoasset->HR_FNAME.'  '.$infoasset->HR_LNAME.'
'.$infoasset->STATUS_NAME.'
'.asset('/manager_asset/assetinfomation/'.$infoasset->ARTICLE_ID));
                !!} --}}
            </td> 
            <td style="font-family: 'Kanit', sans-serif;font-size: 14px;font-style: nomal;">  
                {{ $infoasset->ARTICLE_NUM }}<br> 
                {{ $infoasset->ARTICLE_NAME }} <br>{{ number_format($infoasset->PRICE_PER_UNIT,2) }} ::  {{ formate($infoasset->RECEIVE_DATE) }}<br> 
                {{ $infoasset->HR_DEPARTMENT_SUB_SUB_NAME }} 
            </td> 
        </tr>
    </table>    
  
</body>
        
                     

   <?php
    header("Content-Type: application/vnd.ms-excel");
    header('Content-Disposition: attachment; filename="INFOMATION_REPORT5.xls"');//ชื่อไฟล์


    $url = Request::url();
    $pos = strrpos($url, '/') + 1;
    $user_id = substr($url, $pos);

    use App\Http\Controllers\LeaveController;
    $checkapp = LeaveController::checkapp($user_id);
    $checkver = LeaveController::checkver($user_id);
    $checkallow = LeaveController::checkallow($user_id);

    $countapp = LeaveController::countapp($user_id);
    $countver = LeaveController::countver($user_id);
    $countallow = LeaveController::countallow($user_id);

    use App\Http\Controllers\RiskController;
    $checkrisknotify = RiskController::checkrisknotify($user_id);
    $countrisknotify = RiskController::countrisknotify($user_id);
    ?>
    <?php
    function RemoveDateThai($strDate)
    {
    $strYear = date('Y', strtotime($strDate)) + 543;
    $strMonth = date('n', strtotime($strDate));
    $strDay = date('j', strtotime($strDate));
    $strMonthCut = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
    }
    function Removeformate($strDate)
    {
    $strYear = date('Y', strtotime($strDate));
    $strMonth = date('m', strtotime($strDate));
    $strDay = date('d', strtotime($strDate));
    return $strDay . '/' . $strMonth . '/' . $strYear;
    }
    function Removeformatetime($strtime)
    {
    $H = substr($strtime, 0, 5);
    return $H;
    }
    date_default_timezone_set('Asia/Bangkok');
    $date = date('Y-m-d');
    ?>



    <div class="content mx-1 ml-3">

        <!-- Dynamic Table Simple -->
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title foo15" style="font-family: 'Kanit', sans-serif;"><B>รายงาน ปค.5 ประเมินผลควบคุมภายใน</B></h3>
               
            </div>
            <div class="block-content">
             
              
                 
                <div class="table-responsive">
                    <table class="gwt-table table-striped table-vcenter js-dataTable-simple" style="width: 100%;">
                        <thead style="background-color: #FFEBCD;">
                            <tr height="40">
                                <th  class="text-font" style="text-align: center;font-size: 13px;" width="5%">ลำดับ</th>
                                <th  class="text-font" style="text-align: center;font-size: 13px;" >ความเสี่ยง</th>
                                <th  class="text-font" style="text-align: center;font-size: 13px;" >การควบคุมภายในที่มี</th> 
                                <th  class="text-font" style="text-align: center;font-size: 13px;" >การประเมินผลควบคุมภายใน</th>
                                <th  class="text-font" style="text-align: center;font-size: 13px;" >ความเสี่ยงที่ยังมีอยู่</th>
                                <th  class="text-font" style="text-align: center;font-size: 13px;" >การปรับปรุงควบคุมภายใน</th>
                                <th  class="text-font" style="text-align: center;font-size: 13px;" >หน่วยงานรับผิดชอบ</th>
                                <th  class="text-font" style="text-align: center;font-size: 13px;" >ระยะเวลา</th>
                                <th  class="text-font" style="text-align: center;font-size: 13px;" >ติดตาม</th>
                             

                            </tr >
                        </thead>
                        <tbody>

                            <?php $number = 0; ?>
                            @foreach ($infomationreport5subs as $infomationreport5sub)
                            <?php
                            $number++;
                      ?>
                   
                                <tr height="20">                       
                                    <td class="text-font" style="text-align: center;font-size: 13px;" align="center">{{$number}}</td>
                                 
                                    <td class="text-font text-pedding" style="font-size: 13px;" >{{$infomationreport5sub->RISK_NOTIFY_RE5_SUB_RISK}}</td>
                                    <td class="text-font text-pedding" style="font-size: 13px;" >{{$infomationreport5sub->RISK_NOTIFY_RE5_SUB_CONTROL}}</td>
                                    <td class="text-font text-pedding" style="font-size: 13px;" >{{$infomationreport5sub->RISK_NOTIFY_RE5_SUB_RATE}}</td>
                                    <td class="text-font text-pedding" style="font-size: 13px;" >{{$infomationreport5sub->RISK_NOTIFY_RE5_SUB_HAVE}}</td>
                                    <td class="text-font text-pedding" style="font-size: 13px;" >{{$infomationreport5sub->RISK_NOTIFY_RE5_SUB_IMPROVE}}</td>
                                    <td class="text-font text-pedding" style="font-size: 13px;" >{{$infomationreport5sub->HR_DEPARTMENT_SUB_SUB_NAME}}</td>
                                    <td class="text-font text-pedding" style="font-size: 13px;" >{{$infomationreport5sub->RISK_NOTIFY_RE5_SUB_TIME}}</td>
                                    <td class="text-font text-pedding" style="font-size: 13px;" >{{$infomationreport5sub->RISK_NOTIFY_RE5_SUB_TAG}}</td>

                                  

                                 
                                </tr>
                             

              @endforeach              
                        </tbody>
                    </table>
         



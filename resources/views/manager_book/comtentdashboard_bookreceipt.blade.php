@extends('layouts.book')
<link href="{{ asset('datepicker/dist/css/bootstrap-datepicker.css') }}" rel="stylesheet" />



@section('content')
    <script>
        function checklogin() {
            window.location.href = '{{ route('index') }}';
        }
    </script>
    <?php
    if (Auth::check()) {
        $status = Auth::user()->status;
        $id_user = Auth::user()->PERSON_ID;
    } else {
        echo "<body onload=\"checklogin()\"></body>";
        exit();
    }
    $url = Request::url();
    $pos = strrpos($url, '/') + 1;
    $user_id = substr($url, $pos);
    
    use App\Http\Controllers\DashboardController;
    $checkbook = DashboardController::checkbook($id_user);
    
    use App\Http\Controllers\ManagerbookController;
    $checkmanagerbooksecret = ManagerbookController::checkmanagerbooksecret($id_user);
    ?>
    <?php
    
    ?>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/1.10.24.css.jquery.dataTables.css') }}">
    <style>
        .hoverwhite:hover {
            color: white !important;
        }

        table.dataTable td {
            padding: 0px !important;
            padding-left: 5px !important;
            padding-right: 5px !important;
            vertical-align: middle;
        }

        table.dataTable th {
            vertical-align: middle;
        }

    </style>

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            font-size: 15px;

        }

        .text-pedding {
            padding-left: 10px;
        }

        .text-font {
            font-size: 13px;
        }

        .form-control {
            font-size: 13px;
        }

        table,
        td,
        th {
            border: 1px solid black;
        }

    </style>

    <center>
        <!-- Dynamic Table Simple -->
        <div class="block" style="width: 95%;">
            <div class="block-header block-header-default">
                <div class="col-12"><br>
                    <div class="row ">
                        <div class="col-sm-4 offset-md-4">
                            <h1 class="block-title" style="font-family: 'Kanit', sans-serif;font-size:25px;">
                                <B>ทะเบียนหนังสือรับ</B>
                            </h1>
                        </div>
                        <div class="col-4"> <a class="btn btn-hero btn-success " style="float: right;"
                                href="{{ url('manager_book/dashboard') }}"><i
                                    class="far fa-arrow-alt-circle-left  fa-1x">&nbsp;&nbsp;</i>ย้อนกลับ</a></div>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">

                <div class="row">
                    <div class="col-md-4">
                        ชั้นความเร็ว ::
                        <p class="fa fa-circle" style="color:#008000;"></p> ปกติ


                        <p class="fa fa-circle" style="color:#87CEFA;"></p> ด่วน


                        <p class="fa fa-circle" style="color:#FFA500;"></p> ด่วนมาก

                        <p class="fa fa-circle" style="color:#FF4500;"></p> ด่วนที่สุด
                    </div>
                </div>
                {{-- <div class="container-fluit w-100 tab-pane fade table-responsive"><br> --}}
                    {{-- <table id="NormalWork_table" class="table-striped gwt-table table-striped table-vcenter" style="width: 100%;"> --}}
                    {{-- <table class="gwt-table table-striped table-vcenter js-dataTable-simple" style="width: 100%;"> --}}


                        <div class="table-responsive" style="height:500px;">
                            <!-- DataTables init on table by adding .js-dataTable-simple class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                            <table id="NormalWork_table" class="gwt-table table-striped table-vcenter " style="width: 100%;">

                                <thead style="background-color: #FFEBCD;">
                                    <tr height="40">
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                            width="5%">
                                            ลำดับ</th>
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                            width="5%">File
                                        </th>
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                            width="5%">
                                            Attach <br>File</th>
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                            width="5%">
                                            ชั้นความเร็ว</th>
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                            width="7%">
                                            สถานะ</th>
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                            width="8%">
                                            เลขรับ</th>
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                            width="10%">
                                            เลขหนังสือ</th>
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                            width="10%">
                                            หน่วยงานที่ส่ง</th>
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                            ชื่อเรื่อง</th>
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                            รายละเอียด</th>
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                            ความเห็น ผอ.
                                        </th>
                                        <th class="text-font"
                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                            width="10%">
                                            วันที่รับ</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $number = 0; ?>
                                    @foreach ($infobookreceipts as $infobookreceipt)
                                        <?php $number++; ?>

                                        @if ($checkmanagerbooksecret != 0)
                                            @if ($infobookreceipt->BOOK_SECRET_ID == 1)
                                                <tr height="20">
                                                    <td class="text-font"
                                                        style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        {{ $number }}</td>
                                                    @if ($infobookreceipt->BOOK_HAVE_FILE == 'True')
                                                        <?php $bookpdf = storage_path('app/public/bookpdf/' . $infobookreceipt->BOOK_FILE_NAME); ?>
                                                        @if (file_exists($bookpdf))
                                                            <td
                                                                style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                                <span class="btn"
                                                                    style="background-color:#FF6347;color:#F0FFFF;"><i
                                                                        class="fa fa-1.5x fa-file-pdf"></i></span>
                                                            </td>
                                                        @else
                                                            <td
                                                                style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                                <span class="btn"
                                                                    style="background-color:#5a5655;color:#F0FFFF;"><i
                                                                        class="fa fa-1.5x fa-file-pdf"></i></span>
                                                            </td>
                                                        @endif
                                                    @else
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        </td>
                                                    @endif

                                                    @if ($infobookreceipt->BOOK_HAVE_FILE_2 == 'True')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="btn fa fa-1.5x"
                                                                style="background-color:#0000FF;color:#F0FFFF;"><i
                                                                    class="fa fa-paperclip"></i></span>
                                                        </td>
                                                    @else
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        </td>
                                                    @endif





                                                    @if ($infobookreceipt->BOOK_URGENT_ID == 1)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#008000;"></span>
                                                        </td>
                                                    @elseif($infobookreceipt->BOOK_URGENT_ID == 2)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#87CEFA;"></span>
                                                        </td>
                                                    @elseif($infobookreceipt->BOOK_URGENT_ID == 3)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#FFA500;"></span>
                                                        </td>
                                                    @elseif($infobookreceipt->BOOK_URGENT_ID == 4)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#FF4500;"></span>
                                                        </td>
                                                    @endif

                                                    @if ($infobookreceipt->SEND_STATUS == '1')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-danger">ลงรับ</span>
                                                        </td>
                                                    @elseif($infobookreceipt->SEND_STATUS == '2')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-warning">ส่งหน่วยงาน</span>
                                                        </td>
                                                    @elseif($infobookreceipt->SEND_STATUS == '3')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-success">ผอ.ลงนาม</span>
                                                        </td>
                                                    @elseif($infobookreceipt->SEND_STATUS == '4')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-info">เสนอ ผอ.</span>
                                                        </td>
                                                    @else
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        </td>
                                                    @endif

                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        valign="top" width="8%">{{ $infobookreceipt->BOOK_NUM_IN }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        valign="top" width="10%">{{ $infobookreceipt->BOOK_NUMBER }}
                                                        {{-- <br>ลงวันที่ : {{DateThai($infobookreceipt->BOOK_DATE)}} --}}
                                                    </td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        width="10%">{{ $infobookreceipt->RECORD_ORG_NAME }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;">
                                                        {{ $infobookreceipt->BOOK_NAME }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        valign="top">{{ $infobookreceipt->BOOK_DETAIL }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        valign="top">{{ $infobookreceipt->TOP_LEADER_AC_CMD }}</td>
                                                    <td class="text-font"
                                                        style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                                        width="10%">{{ DateThai($infobookreceipt->DATE_SAVE) }}</td>


                                                </tr>
                                            @else
                                                <tr height="40">
                                                    <td class="text-font"
                                                        style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        {{ $number }}</td>
                                                    @if ($infobookreceipt->BOOK_HAVE_FILE == 'True')
                                                        <?php $bookpdf = storage_path('app/public/bookpdf/' . $infobookreceipt->BOOK_FILE_NAME); ?>
                                                        @if (file_exists($bookpdf))
                                                            <td
                                                                style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                                <span class="btn"
                                                                    style="background-color:#FF6347;color:#F0FFFF;"><i
                                                                        class="fa fa-1.5x fa-file-pdf"></i></span>
                                                            </td>
                                                        @else
                                                            <td
                                                                style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                                <span class="btn"
                                                                    style="background-color:#5a5655;color:#F0FFFF;"><i
                                                                        class="fa fa-1.5x fa-file-pdf"></i></span>
                                                            </td>
                                                        @endif
                                                    @else
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        </td>
                                                    @endif

                                                    @if ($infobookreceipt->BOOK_HAVE_FILE_2 == 'True')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="btn fa fa-1.5x"
                                                                style="background-color:#0000FF;color:#F0FFFF;"><i
                                                                    class="fa fa-paperclip"></i></span>
                                                        </td>
                                                    @else
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        </td>
                                                    @endif

                                                    @if ($infobookreceipt->BOOK_URGENT_ID == 1)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#008000;"></span>
                                                        </td>
                                                    @elseif($infobookreceipt->BOOK_URGENT_ID == 2)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#87CEFA;"></span>
                                                        </td>
                                                    @elseif($infobookreceipt->BOOK_URGENT_ID == 3)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#FFA500;"></span>
                                                        </td>
                                                    @elseif($infobookreceipt->BOOK_URGENT_ID == 4)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#FF4500;"></span>
                                                        </td>
                                                    @endif

                                                    @if ($infobookreceipt->SEND_STATUS == '1')
                                                        <td align="center"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-danger">ลงรับ</span>
                                                        </td>
                                                    @elseif($infobookreceipt->SEND_STATUS == '2')
                                                        <td align="center"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-warning">ส่งหน่วยงาน</span>
                                                        </td>
                                                    @elseif($infobookreceipt->SEND_STATUS == '3')
                                                        <td align="center"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-success">ผอ.ลงนาม</span>
                                                        </td>
                                                    @elseif($infobookreceipt->SEND_STATUS == '4')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-info">เสนอ ผอ.</span>
                                                        </td>
                                                    @else
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        </td>
                                                    @endif

                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        valign="top" width="8%">{{ $infobookreceipt->BOOK_NUM_IN }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        valign="top" width="10%">{{ $infobookreceipt->BOOK_NUMBER }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        width="10%">{{ $infobookreceipt->RECORD_ORG_NAME }}</td>

                                                    @if ($infobookreceipt->BOOK_SECRET_ID == 2)
                                                        <td class="text-font text-pedding"
                                                            style="color: red;border: 1px solid black;">ลับ ::
                                                            {{ $infobookreceipt->BOOK_NAME }}</td>
                                                    @elseif($infobookreceipt->BOOK_SECRET_ID == 3)
                                                        <td class="text-font text-pedding"
                                                            style="color: red;border: 1px solid black;">ลับมาก ::
                                                            {{ $infobookreceipt->BOOK_NAME }}</td>
                                                    @elseif($infobookreceipt->BOOK_SECRET_ID == 4)
                                                        <td class="text-font text-pedding"
                                                            style="color: red;border: 1px solid black;">ลับที่สุด ::
                                                            {{ $infobookreceipt->BOOK_NAME }}</td>
                                                    @else
                                                        <td class="text-font text-pedding"
                                                            style="color: red;border: 1px solid black;">
                                                            {{ $infobookreceipt->BOOK_NAME }}</td>
                                                    @endif

                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        valign="top">{{ $infobookreceipt->BOOK_DETAIL }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        valign="top">{{ $infobookreceipt->TOP_LEADER_AC_CMD }}</td>
                                                    <td class="text-font"
                                                        style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                                        width="10%">{{ DateThai($infobookreceipt->DATE_SAVE) }}</td>



                                                </tr>
                                            @endif
                                        @else
                                            @if ($infobookreceipt->BOOK_SECRET_ID == 1)
                                                <tr height="40">
                                                    <td class="text-font"
                                                        style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        {{ $number }}</td>
                                                    @if ($infobookreceipt->BOOK_HAVE_FILE == 'True')
                                                        <?php $bookpdf = storage_path('app/public/bookpdf/' . $infobookreceipt->BOOK_FILE_NAME); ?>
                                                        @if (file_exists($bookpdf))
                                                            <td
                                                                style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                                <span class="btn"
                                                                    style="background-color:#FF6347;color:#F0FFFF;"><i
                                                                        class="fa fa-1.5x fa-file-pdf"></i></span>
                                                            </td>
                                                        @else
                                                            <td
                                                                style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                                <span class="btn"
                                                                    style="background-color:#5a5655;color:#F0FFFF;"><i
                                                                        class="fa fa-1.5x fa-file-pdf"></i></span>
                                                            </td>
                                                        @endif
                                                    @else
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        </td>
                                                    @endif

                                                    @if ($infobookreceipt->BOOK_HAVE_FILE_2 == 'True')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="btn fa fa-1.5x"
                                                                style="background-color:#0000FF;color:#F0FFFF;"><i
                                                                    class="fa fa-paperclip"></i></span>
                                                        </td>
                                                    @else
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        </td>
                                                    @endif

                                                    @if ($infobookreceipt->BOOK_URGENT_ID == 1)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#008000;"></span>
                                                        </td>
                                                    @elseif($infobookreceipt->BOOK_URGENT_ID == 2)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#87CEFA;"></span>
                                                        </td>
                                                    @elseif($infobookreceipt->BOOK_URGENT_ID == 3)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#FFA500;"></span>
                                                        </td>
                                                    @elseif($infobookreceipt->BOOK_URGENT_ID == 4)
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="fa fa-2x fa-circle" style="color:#FF4500;"></span>
                                                        </td>
                                                    @endif

                                                    @if ($infobookreceipt->SEND_STATUS == '1')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-danger">ลงรับ</span>
                                                        </td>
                                                    @elseif($infobookreceipt->SEND_STATUS == '2')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-warning">ส่งหน่วยงาน</span>
                                                        </td>
                                                    @elseif($infobookreceipt->SEND_STATUS == '3')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-success">ผอ.ลงนาม</span>
                                                        </td>
                                                    @elseif($infobookreceipt->SEND_STATUS == '4')
                                                        <td
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                            <span class="badge badge-info">เสนอ ผอ.</span>
                                                        </td>
                                                    @else
                                                        <td class="text-font"
                                                            style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                        </td>
                                                    @endif

                                                    <td class="text-font"
                                                        style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                                        valign="top" width="8%">{{ $infobookreceipt->BOOK_NUM_IN }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        valign="top" width="10%">{{ $infobookreceipt->BOOK_NUMBER }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        width="10%">{{ $infobookreceipt->RECORD_ORG_NAME }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;">
                                                        {{ $infobookreceipt->BOOK_NAME }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        valign="top">{{ $infobookreceipt->BOOK_DETAIL }}</td>
                                                    <td class="text-font text-pedding"
                                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;"
                                                        valign="top">{{ $infobookreceipt->TOP_LEADER_AC_CMD }}</td>
                                                    <td class="text-font"
                                                        style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"
                                                        width="10%">{{ DateThai($infobookreceipt->DATE_SAVE) }}</td>





                                                </tr>
                                            @endif
                                        @endif
                                        </body>


                        </div>
                </div>
            </div>
            @endforeach
            </tbody>
            </table>

        </div>
        </div>
        </div>



        </div>







    @endsection

    @section('footer')
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

        <script>
            $(document).ready(function() {
                $('#NormalWork_table').DataTable({
                    info: false,
                    // "bPaginate": false,
                    // "bLengthChange": false,
                    "iDisplayLength": 25,
                    // "bFilter": false,    
                    // "bInfo": false,
                    // "bAutoWidth": false
                    // "paging": false,
                });
            });
        </script>
    @endsection

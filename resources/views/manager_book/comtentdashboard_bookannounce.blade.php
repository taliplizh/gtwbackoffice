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
    
    function RemovegetAge($birthday)
    {
        $then = strtotime($birthday);
        return floor((time() - $then) / 31556926);
    }
    
    function Removeformate($strDate)
    {
        $strYear = date('Y', strtotime($strDate));
        $strMonth = date('m', strtotime($strDate));
        $strDay = date('d', strtotime($strDate));
    
        return $strDay . '/' . $strMonth . '/' . $strYear;
    }
    date_default_timezone_set('Asia/Bangkok');
    $date = date('Y-m-d');
    
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
                                <B>ข้อมูลประกาศ</B>
                            </h1>
                        </div>
                        <div class="col-4"> <a class="btn btn-hero btn-success " style="float: right;"
                                href="{{ url('manager_book/dashboard') }}"><i
                                    class="far fa-arrow-alt-circle-left  fa-1x">&nbsp;&nbsp;</i>ย้อนกลับ</a></div>
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                


                {{-- <div class="table-responsive">
                    <!-- DataTables init on table by adding .js-dataTable-simple class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                    <table class="gwt-table table-striped table-vcenter js-dataTable-simple" style="width: 100%;"> --}}
                        <div class="table-responsive" style="height:500px;">
                            <!-- DataTables init on table by adding .js-dataTable-simple class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
                            <table id="NormalWork_table" class="gwt-table table-striped table-vcenter " style="width: 100%;">
                        <thead style="background-color: #FFEBCD;">
                            <tr height="40">
                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;" width="5%">
                                    ลำดับ</th>

                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;" width="5%">File
                                </th>

                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">ชื่อเรื่อง</th>
                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">รายละเอียด</th>
                                <th class="text-font"
                                    style="border-color:#F0FFFF;text-align: center;border: 1px solid black;" width="10%">
                                    วันที่รับ</th>
                                


                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 0; ?>
                            @foreach ($infobookannounces as $infobookannounce)
                                <?php $number++; ?>

                                <tr height="20">
                                    <td class="text-font"
                                        style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                        {{ $number }}</td>

                                    @if ($infobookannounce->BOOK_HAVE_FILE == 'True')
                                        <?php $bookpdf = storage_path('app/public/bookpdf/' . $infobookannounce->BOOK_FILE_NAME); ?>
                                        @if (file_exists($bookpdf))
                                            <td style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                <span class="btn"
                                                    style="background-color:#FF6347;color:#F0FFFF;"><i
                                                        class="fa fa-1.5x fa-file-pdf"></i></span></td>
                                        @else
                                            <td style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                                <span class="btn"
                                                    style="background-color:#5a5655;color:#F0FFFF;"><i
                                                        class="fa fa-1.5x fa-file-pdf"></i></span></td>
                                        @endif
                                    @else
                                        <td style="border-color:#F0FFFF;text-align: center;border: 1px solid black;"></td>
                                    @endif


                                    <td class="text-font text-pedding"
                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;">
                                        {{ $infobookannounce->BOOK_NAME }}</td>
                                    <td class="text-font text-pedding"
                                        style="border-color:#F0FFFF;text-align: left;border: 1px solid black;">
                                        {{ $infobookannounce->BOOK_DETAIL }}</td>
                                    <td class="text-font"
                                        style="border-color:#F0FFFF;text-align: center;border: 1px solid black;">
                                        {{ DateThai($infobookannounce->DATE_SAVE) }}</td>




                                    

                                </tr>
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

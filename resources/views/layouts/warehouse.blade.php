<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Gotowin</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="gtw-backoffice">
        <meta name="author" content="backoffice">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="gtw-backoffice">
        <meta property="og:site_name" content="gtw">
        <meta property="og:description" content="gtw-backoffice">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

      <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('asset/media/favicons/logo_cir.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('asset/media/favicons/logo_cir.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('asset/media/favicons/apple-touch-icon-180x180.png') }}">


        <!-- Stylesheets -->
        <!-- Fonts and Dashmix framework -->
        
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> 
    <link href='https://fonts.googleapis.com/css?family=Kanit&subset=thai,latin' rel='stylesheet' type='text/css'>
   

        <link rel="stylesheet" id="css-main" href="{{ asset('asset/css/dashmix.css') }}">
        <link rel="stylesheet" id="css-theme" href="{{ asset('asset/css/themes/xplay.css') }}">
        <link rel="stylesheet" href="{{asset('css/stylesl.css')}}">
        {{-- <link rel="stylesheet"  href="{{ asset('css/styledis.css') }}"> --}}
        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/xwork.min.css"> -->
     
        <!-- END Stylesheets -->

        
    
    <!-- END Stylesheets -->
    <style>
        #page-container.main-content-boxed>#page-header .content-header,
        #page-container.main-content-boxed>#page-header .content,
        #page-container.main-content-boxed>#main-container .content,
        #page-container.main-content-boxed>#page-footer .content {
            max-width: 1399px;
        }

        .fix {
            position: fixed;
            width: 100%;
            z-index: 5;
        }

        body *{
             font-family:'Kanit',sans-serif;
         }
         .fix {
                position: fixed;
                width: 100%;
                z-index: 5;
            }
    
            #loader-wrapper {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 1000;
                background-color: #00000042;
            }
    
            #loader {
                display: block;
                position: relative;
                left: 50%;
                top: 50%;
                width: 150px;
                height: 150px;
                margin: -75px 0 0 -75px;
                border-radius: 50%;
                border: 3px solid transparent;
                border-top-color: #3498db;
                -webkit-animation: spin 2s linear infinite;
                /* Chrome, Opera 15+, Safari 5+ */
                animation: spin 2s linear infinite;
                /* Chrome, Firefox 16+, IE 10+, Opera */
            }
    
            #loader:before {
                content: "";
                position: absolute;
                top: 5px;
                left: 5px;
                right: 5px;
                bottom: 5px;
                border-radius: 50%;
                border: 3px solid transparent;
                border-top-color: #e74c3c;
                -webkit-animation: spin 3s linear infinite;
                /* Chrome, Opera 15+, Safari 5+ */
                animation: spin 3s linear infinite;
                /* Chrome, Firefox 16+, IE 10+, Opera */
            }
    
            #loader:after {
                content: "";
                position: absolute;
                top: 15px;
                left: 15px;
                right: 15px;
                bottom: 15px;
                border-radius: 50%;
                border: 3px solid transparent;
                border-top-color: #f9c922;
                -webkit-animation: spin 1.5s linear infinite;
                /* Chrome, Opera 15+, Safari 5+ */
                animation: spin 1.5s linear infinite;
                /* Chrome, Firefox 16+, IE 10+, Opera */
            }
    
            @-webkit-keyframes spin {
                0% {
                    -webkit-transform: rotate(0deg);
                    /* Chrome, Opera 15+, Safari 3.1+ */
                    -ms-transform: rotate(0deg);
                    /* IE 9 */
                    transform: rotate(0deg);
                    /* Firefox 16+, IE 10+, Opera */
                }
    
                100% {
                    -webkit-transform: rotate(360deg);
                    /* Chrome, Opera 15+, Safari 3.1+ */
                    -ms-transform: rotate(360deg);
                    /* IE 9 */
                    transform: rotate(360deg);
                    /* Firefox 16+, IE 10+, Opera */
                }
            }
    
            @keyframes spin {
                0% {
                    -webkit-transform: rotate(0deg);
                    /* Chrome, Opera 15+, Safari 3.1+ */
                    -ms-transform: rotate(0deg);
                    /* IE 9 */
                    transform: rotate(0deg);
                    /* Firefox 16+, IE 10+, Opera */
                }
    
                100% {
                    -webkit-transform: rotate(360deg);
                    /* Chrome, Opera 15+, Safari 3.1+ */
                    -ms-transform: rotate(360deg);
                    /* IE 9 */
                    transform: rotate(360deg);
                    /* Firefox 16+, IE 10+, Opera */
                }
            }


    </style>
    @yield('css_before')


    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/js/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/jquery-1.10.2.js') }}"></script>
    {{-- <link rel="stylesheet" href="{{ asset('assets/js/sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ asset('assets/js/sweetalert2/sweetalert2.all.min.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.13.0/sweetalert2.min.js" integrity="sha512-33a7z5UWvWHAxBi0waVWN71V1WSXylTH1Iier1lEZdKxvE4RdoYkOKWazVr9av5O1GS6aaOcE3nUB3sPQRA7Jg==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/10.13.0/sweetalert2.min.css" integrity="sha512-EeZYT52DgUwGU45iNoywycYyJW/C2irAZhp2RZAA0X4KtgE4XbqUl9zXydANcIlEuF+BXpsooxzkPW081bqoBQ==" crossorigin="anonymous" />


    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href='https://fonts.googleapis.com/css?family=Kanit&subset=thai,latin' rel='stylesheet' type='text/css'>
    

     <style>
         .fix{
            position: fixed;
            width: 100%;
            z-index: 5;
         }
         body *{
            font-family: 'Kanit', sans-serif;
         }
         .btn{
            font-family: 'Kanit', sans-serif !important;
         }
     </style>   
        @yield('css_before')
        <body>
            <!--  loading Screen page  -->
            <div class="loading-page">
                <div id="loader-wrapper">
                    <div id="loader"></div>
                    <!-- <div style="padding-top: 10%; ">
                            <img src="/image/boss.png"  style="width: 30%;display:block;margin: auto;"/>
                        </div>  -->
                </div>
            </div>
        <!-- Page Container -->
        <!--
            Available classes for #page-container:

        GENERIC

            'enable-cookies'                            Remembers active color theme between pages (when set through color theme helper Template._uiHandleTheme())

        SIDEBAR & SIDE OVERLAY

            'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
            'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
            'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
            'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
            'sidebar-dark'                              Dark themed sidebar

            'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
            'side-overlay-o'                            Visible Side Overlay by default

            'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

            'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

        HEADER

            ''                                          Static Header if no class is added
            'page-header-fixed'                         Fixed Header


        Footer

            ''                                          Static Footer if no class is added
            'page-footer-fixed'                         Fixed Footer (please have in mind that the footer has a specific height when is fixed)

        HEADER STYLE

            ''                                          Classic Header style if no class is added
            'page-header-dark'                          Dark themed Header
            'page-header-glass'                         Light themed Header with transparency by default
                                                        (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
            'page-header-glass page-header-dark'         Dark themed Header with transparency by default
                                                        (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

        MAIN CONTENT LAYOUT

            ''                                          Full width Main Content if no class is added
            'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
            'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)
        -->
        <div id="page-container" class="page-header-dark main-content-boxed page-header-fixed">

            <!-- Header -->
            <header id="page-header" style="background-color: #A52A2A;">
                <!-- Header Content -->
                <div class="content-header"  >
                    <!-- Left Section -->
                    <div class="d-flex align-items-center">
                        <!-- Logo -->
                      
                        <a class="link-fx font-size-lg font-w600 text-white" href="">
                        <i class="fa fa-1.5x fa-box-open text-warning"></i>
                            <span class="text-white-50"></span><span class="text-white" style="font-weight: normal;font-family: 'Kanit', sans-serif;">คลังวัสดุ</span>
                        </a>
                        <!-- END Logo -->

                        <!-- Open Search Section -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                   
                        <!-- END Open Search Section -->
                    </div>
                    <!-- END Left Section -->

                    <!-- Right Section -->
                    <div>
                        <!-- User Dropdown -->
                        <div class="dropdown d-inline-block" >
                        <button type="button" class="btn btn-dual" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                <i class="fa fa-fw fa-user d-sm-none"></i>
                                <span class="d-none d-sm-inline-block" style=" font-family: 'Kanit', sans-serif; font-weight: normal;" >{{ Auth::user()->name }} </span>
                                <i class="fa fa-fw fa-angle-down ml-1 d-none d-sm-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="page-header-user-dropdown">
                                <div class="rounded-top font-w600 text-white text-center p-3" style="background-color: #A52A2A;">
                                   User Options
                                </div>
                                <div class="p-2">
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="far fa-fw fa-user mr-1"></i> Profile
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                        <span><i class="far fa-fw fa-envelope mr-1"></i> Inbox</span>
                                        <span class="badge badge-primary">3</span>
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="far fa-fw fa-file-alt mr-1"></i> Invoices
                                    </a>
                                    <div role="separator" class="dropdown-divider"></div>

                                    <!-- Toggle Side Overlay -->
                                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                    <a class="dropdown-item" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_toggle">
                                        <i class="far fa-fw fa-building mr-1"></i> Settings
                                    </a>
                                    <!-- END Side Overlay -->

                                    <div role="separator" class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="far fa-fw fa-arrow-alt-circle-left mr-1"></i> Sign Out
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>

                                

                                </div>
                            </div>
                        </div>
                        <!-- END User Dropdown -->
                    </div>
                    <!-- END Right Section -->
                </div>
                <!-- END Header Content -->

                <!-- Header Search -->
                <div id="page-header-search" class="overlay-header bg-header-dark">
                    <div class="content-header">
                        <form class="w-100" action="be_pages_generic_search.html" method="POST">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                    <button type="button" class="btn btn-primary" data-toggle="layout" data-action="header_search_off">
                                        <i class="fa fa-fw fa-times-circle"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control" placeholder="Search your websites.." id="page-header-search-input" name="page-header-search-input">
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END Header Search -->

                <!-- Header Loader -->
                <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
                <div id="page-header-loader" class="overlay-header bg-primary">
                    <div class="content-header">
                        <div class="w-100 text-center">
                            <i class="fa fa-fw fa-2x fa-spinner fa-spin text-white"></i>
                        </div>
                    </div>
                </div>
                <!-- END Header Loader -->
            </header>
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">

                <!-- Navigation -->
                <div class="bg-white fix">
                    <div >
                        <!-- Toggle Main Navigation -->
                        <div class="d-lg-none push">
                            <!-- Class Toggle, functionality initialized in Helpers.coreToggleClass() -->
                            <button type="button" class="btn btn-block btn-light d-flex justify-content-between align-items-center" data-toggle="class-toggle" data-target="#main-navigation" data-class="d-none">
                                Menu
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>
                        <!-- END Toggle Main Navigation -->

                        <!-- Main Navigation -->
                        <div id="main-navigation" class="d-none d-lg-block ">
                      
                            <ul class="nav-main nav-main-horizontal nav-main-hover ">
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('manager_warehouse/dashboard') ? ' active' : '' }}" href="{{ url('manager_warehouse/dashboard') }}">
                                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                                        <span class="nav-main-link-name loadscreen" style="font-size: 14px;font-family: 'Kanit', sans-serif;">Dashboard</span>
                    
                                    </a>
                                </li>
                                <li class="nav-main-item"> 
                                        <a class="nav-main-link{{ request()->is('manager_warehouse/detail') ? ' active' : '' }}" href="{{ url('manager_warehouse/detail') }}">
                                        <i class="nav-main-link-icon fa fa-calendar-alt"></i>
                                        <span class="nav-main-link-name loadscreen" style="font-size: 14px;font-family: 'Kanit', sans-serif;">ตรวจรับ</span>
                    
                                    </a>
                                </li>
                     
                                <li class="nav-main-item"> 
                                        <a class="nav-main-link{{ request()->is('manager_warehouse/storehouse') ? ' active' : '' }}" href="{{ url('manager_warehouse/storehouse') }}">
                                        <i class="nav-main-link-icon fa fa-calendar-alt"></i>
                                        <span class="nav-main-link-name loadscreen" style="font-size: 14px;font-family: 'Kanit', sans-serif;">Stock card :: คลังหลัก</span>
                    
                                    </a>
                                </li>
                     
                                <li class="nav-main-item"> 
                                        <a class="nav-main-link{{ request()->is('manager_warehouse/treasury') ? ' active' : '' }}" href="{{ url('manager_warehouse/treasury') }}">
                                        <i class="nav-main-link-icon fa fa-calendar-alt"></i>
                                        <span class="nav-main-link-name loadscreen" style="font-size: 14px;font-family: 'Kanit', sans-serif;">Stock card :: คลังย่อย</span>
                    
                                    </a>
                                </li>

                                <li class="nav-main-item"> 
                                        <a class="nav-main-link{{ request()->is('manager_warehouse/disburse') ? ' active' : '' }}" href="{{ url('manager_warehouse/disburse') }}">
                                        <i class="nav-main-link-icon fa fa-calendar-alt"></i>
                                        <span class="nav-main-link-name loadscreen" style="font-size: 14px;font-family: 'Kanit', sans-serif;">เบิกจ่ายวัสดุ</span>
                    
                                    </a>
                                </li>
                                
                         
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                        <i class="nav-main-link-icon fa fa-user-circle"></i>
                                        <span class="nav-main-link-name loadscreen">รายงานมูลค่าวัสดุ</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_warehouse/reportvalue') }}">
                                                <span class="nav-main-link-name loadscreen">สรุปงานวัสดุคงคลัง คลังวัสดุ</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_warehouse/reportvaluestore') }}">
                                                <span class="nav-main-link-name loadscreen">สรุปงานวัสดุคงคลังตามประเภทสิ่งของ คลังหลัก</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_warehouse/reportvaluetreasury') }}">
                                                <span class="nav-main-link-name loadscreen">สรุปงานวัสดุคงคลังตามประเภทสิ่งของ คลังย่อย</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_warehouse/reportquarter') }}">
                                                <span class="nav-main-link-name loadscreen">รายงานไตรมาส</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_warehouse/reportplan') }}">
                                                <span class="nav-main-link-name loadscreen">แผนการจัดซื้อวัสดุ</span>
                                            </a>
                                        </li>

                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_warehouse/reportrandom') }}">
                                                <span class="nav-main-link-name loadscreen">รายงานสุ่มรายการคงเหลือ</span>
                                            </a>
                                        </li>

                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_warehouse/reportexp') }}">
                                                <span class="nav-main-link-name loadscreen">รายงานวัสดุที่หมดอายุ</span>
                                            </a>
                                        </li>
                                      
                                      
                                    </ul>
                                </li>
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                        <i class="nav-main-link-icon fa fa-cogs"></i>
                                        <span class="nav-main-link-name loadscreen">ตั้งค่า</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('formpdf/warehouse_function') }}">
                                                <span class="nav-main-link-name loadscreen">ฟังก์ชั่นฟอร์ม</span>
                                            </a>
                                        </li>

                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_warehouse/objectivepay') }}">
                                                <span class="nav-main-link-name loadscreen">กำหนดวัตถุประสงค์การใช้</span>
                                            </a>
                                        </li>
                                                             
                                      
                                    </ul>
                                </li>
                                <!--
                                <li class="nav-main-item"> 
                                <a class="nav-main-link{{ request()->is('manager_medical/suppliesinfo') ? ' active' : '' }}" href="{{ url('manager_medical/suppliesinfo') }}">
                                        <i class="nav-main-link-icon fa fa-calendar-alt"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">ข้อมูลวัสดุยาและเวชภัณฑ์</span>
                    
                                    </a>
                                </li>
                                
                                 <li class="nav-main-item">
                                <a class="nav-main-link{{ request()->is('manager_car/carinforefer') ? ' active' : '' }}" href="">
                                        <i class="nav-main-link-icon fa fa-ambulance"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;"></span>
                    
                                    </a>
                                </li>
                    
                                <li class="nav-main-item">
                                <a class="nav-main-link{{ request()->is('manager_car/infomationcar') ? ' active' : '' }}" href="">
                                        <i class="nav-main-link-icon fa fa-paste"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;"></span>
                    
                                    </a>
                                </li>
                                <li class="nav-main-item">
                                <a class="nav-main-link{{ request()->is('manager_car/') ? ' active' : '' }}" href="">
                                        <i class="nav-main-link-icon fa fa-cogs"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;"></span>
                    
                                    </a>
                                </li>

                                <li class="nav-main-item">
                                <a class="nav-main-link{{ request()->is('manager_car/report') ? ' active' : '' }}" href="">
                                        <i class="nav-main-link-icon fa fa-clipboard-list"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">รายงาน</span>
                    
                                    </a>
                                </li>
                            <!--<li class="nav-main-item">
                                <a class="nav-main-link{{ request()->is('manager_book/bookpurchase') ? ' active' : '' }}" href="{{ url('manager_book/bookpurchase') }}">
                                        <i class="nav-main-link-icon fa fa-money-check-alt"></i>
                                        <span class="nav-main-link-name" style="font-size: 16px;">จัดซื้อจัดจ้าง</span>
                    
                                    </a>
                                </li>-->
                            
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- END Main Navigation -->
                    </div>
                </div>
                <!-- END Navigation -->

                <!-- Page Content -->
                <div style="margin-top:50px">
                    @yield('content')
                </div>
             

            </main>
            <!-- END Main Container -->

            <script src="{{ asset('asset/js/dashmix.app.js') }}"></script>

            <!-- Laravel Scaffolding JS -->
            <script src="{{ asset('asset/js/laravel.app.js') }}"></script>
            <script src="{{ asset('js/globalFunction.js') }}"></script>
            <script src="{{ asset('js/formControl.js') }}"></script>

            @yield('footer')
    </body>
</html>


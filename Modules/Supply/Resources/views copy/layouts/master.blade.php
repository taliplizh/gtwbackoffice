<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Gotowin</title>

        <meta name="description" content="gtw-backoffice">
        <meta name="author" content="backoffice">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="gtw-backoffice">
        <meta property="og:site_name" content="Dashmix">
        <meta property="og:description" content="gtw-backoffice">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{ asset('asset/media/favicons/favicon.png')}}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('asset/media/favicons/favicon-192x192.png')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('asset/media/favicons/apple-touch-icon-180x180.png')}}">
        <!-- END Icons -->

        <link rel="stylesheet" id="css-main" href="{{ asset('asset/css/dashmix.css') }}">
        {{-- <link rel="stylesheet" id="css-main" href="https://demo.pixelcave.com/dashmix/assets/css/dashmix.min-3.1.css"> --}}
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="https://demo.pixelcave.com/dashmix/assets/js/plugins/select2/css/select2.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
        <!-- END Stylesheets -->
    <link href="{{ asset('select2/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('select2/select2.min.js') }}"></script>

    {{-- <script src="{{ asset('js/formControl.js') }}"></script> --}}


    </head>
     <style>
         .fix{
            position: fixed;
            width: 100%;
            z-index: 5;
         }
     </style>   
        @yield('css_before')
    <body>
      
        <div id="page-container" class="page-header-dark main-content-boxed page-header-fixed">

            <!-- Header -->
            <header id="page-header">
                <!-- Header Content -->
                <div class="content-header"  >
                    <!-- Left Section -->
                    <div class="d-flex align-items-center">
                        <!-- Logo -->
                      
                        <a class="link-fx font-size-lg font-w600 text-white" href="">
                        <i class="fa fa-1.5x fa-dolly-flatbed text-warning"></i>
                            <span class="text-white-50"></span><span class="text-white" style="font-weight: normal;font-family: 'Kanit', sans-serif;">งานจ่ายกลาง</span>
                        </a>
                        <!-- END Logo -->
                    </div>
                    <!-- END Left Section -->

                    <!-- Right Section -->
                    <div>
                        <!-- User Dropdown -->
                        <div class="dropdown d-inline-block">
                        <button type="button" class="btn btn-dual" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-fw fa-user d-sm-none"></i>
                                <span class="d-none d-sm-inline-block" style=" font-family: 'Kanit', sans-serif; font-weight: normal;" >{{ Auth::user()->name }} </span>
                                <i class="fa fa-fw fa-angle-down ml-1 d-none d-sm-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="page-header-user-dropdown">
                                <div class="bg-primary-darker rounded-top font-w600 text-white text-center p-3">
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
                                    <button type="button" class="btn btn-primary loadscreen" data-toggle="layout" data-action="header_search_off">
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


                        <div id="main-navigation" class="d-none d-lg-block container-fluid">
                      
                            <ul class="nav-main nav-main-horizontal nav-main-hover ">
                                <li class="nav-main-item"> 
                                        <a class="nav-main-link loadscreen {{ request()->is('manager_mpay/mpay_withdraw') ? ' active' : '' }}" href="{{ url('supply') }}">
                                        <i class="nav-main-link-icon fa fa-calendar-alt"></i></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">รายการขอเบิก</span>
                    
                                    </a>
                                </li>


                                <li class="nav-main-item"> 
                                        <a class="nav-main-link{{ request()->is('manager_mpay/mpay_stickercreate') ? ' active' : '' }}" href="{{ url('manager_mpay/mpay_stickercreate') }}">
                                        <i class="nav-main-link-icon fas fa-qrcode"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">สติ๊กเกอร์</span>
                    
                                    </a>
                                </li>
                           
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                    <i class="nav-main-link-icon fas fa-sync-alt"></i>
                                        <span class="nav-main-link-name loadscreen">บริหารจัดการ</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                    <li class="nav-main-item"> 
                                        <a class="nav-main-link{{ request()->is('manager_mpay/mpay_stickernight') ? ' active' : '' }}" href="{{ url('manager_mpay/mpay_stickernight') }}">
                                        <i class="nav-main-link-icon fas fa-sync-alt"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">คืนสติกเกอร์</span>
                    
                                    </a>
                                </li>
                                                <li class="nav-main-item"> 
                                                    <a class="nav-main-link{{ request()->is('manager_mpay/mpay_pay') ? ' active' : '' }}" href="{{ url('manager_mpay/mpay_pay') }}">
                                                    <i class="nav-main-link-icon fab fa-amazon-pay"></i>
                                                    <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">จ่ายของ</span>
                                
                                                </a>
                                            </li>
                                            <li class="nav-main-item"> 
                                        <a class="nav-main-link{{ request()->is('manager_mpay/mpay_recieve') ? ' active' : '' }}" href="{{ url('manager_mpay/mpay_recieve') }}">
                                        <i class="nav-main-link-icon fas fa-recycle"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">รับคืน</span>
                    
                                        </a>
                                        </li>
                                     <li class="nav-main-item"> 
                                        <a class="nav-main-link{{ request()->is('manager_mpay/mpay_dispose') ? ' active' : '' }}" href="{{ url('manager_mpay/mpay_dispose') }}">
                                        <i class="nav-main-link-icon fas fa-eraser"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">จำหน่ายทิ้ง</span>
                    
                                    </a>
                                </li>                           
                                    </ul>
                                </li>

                             

                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                    <i class="nav-main-link-icon fas fa-sync-alt"></i>
                                        <span class="nav-main-link-name loadscreen">ตั้งค่า</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                            <li class="nav-main-item"> 
                                                    <a class="nav-main-link{{ request()->is('manager_mpay/mpay_setupdetailpiece') ? ' active' : '' }}" href="{{ url('manager_mpay/mpay_setupdetailpieces') }}">
                                                    <i class="nav-main-link-icon fas fa-sync-alt"></i>
                                                    <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">ตั้งค่ารายละเอียดวัสดุปราศจากเชื้อแบบชิ้น</span>
                                                    </a>
                                            </li>

                                            <li class="nav-main-item"> 
                                                    <a class="nav-main-link{{ request()->is('manager_mpay/mpay_setupdetailset') ? ' active' : '' }}" href="{{ url('manager_mpay/mpay_setupdetailset') }}">
                                                    <i class="nav-main-link-icon fas fa-sync-alt"></i>
                                                    <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">ตั้งค่ารายละเอียดวัสดุปราศจากเชื้อแบบเซต</span>
                                                    </a>
                                            </li>

                                    </ul>
                                </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- END Main Navigation -->
                    </div>
                </div>
                <!-- END Navigation -->

                <!-- Page Content -->
                <div class="container-fluid mt-5">
                    <br>
                    <div id="app"></div>
                    <div id="example"></div>
                    @yield('content')
                </div>
            </main>
            <script src="{{ asset('js/app.js') }}"></script>

            <script src="{{ asset('asset/js/dashmix.app.js') }}"></script>
            {{-- <script src="https://demo.pixelcave.com/dashmix/assets/js/plugins/select2/js/select2.full.min.js"></script> --}}
            @yield('footer')
    </body>
</html>


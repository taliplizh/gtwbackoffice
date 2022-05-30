<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>Gotowin</title>
        <meta name="description" content="gtw-backoffice">
        <meta name="author" content="backoffice">
        <meta name="robots" content="noindex, nofollow">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
        <link rel="stylesheet" id="css-theme" href="{{ asset('asset/css/themes/xsmooth.css') }}">
        <link rel="stylesheet"  href="{{ asset('css/styledis.css') }}">


     <style>
         .fix{
            position: fixed;
            width: 100%;
            z-index: 5;
         }
     </style>   
     <link rel="stylesheet" href="{{asset('css/stylesl.css')}}">
        @yield('css_before')
    </head>
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
                        <i class="fa fa-1.5x fa-project-diagram  text-warning"></i>
                            <span class="text-white-50"></span><span class="text-white" style="font-weight: normal;font-family: 'Kanit', sans-serif;">แผนงาน</span>
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
                                    <a class="nav-main-link{{ request()->is('manager_plan/dashboard') ? ' active' : '' }}" href="{{ url('manager_plan/dashboard') }}">
                                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">Dashboard</span>
                    
                                    </a>
                                </li>

                                
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                        <i class="nav-main-link-icon fa fa-user-circle"></i>
                                        <span class="nav-main-link-name">ข้อมูลองค์กร</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                    <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/plan_vision') }}">
                                                <span class="nav-main-link-name">วิสัยทัศน์</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/plan_mission') }}">
                                                <span class="nav-main-link-name">พันธกิจ</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/plan_strategic') }}">
                                                <span class="nav-main-link-name">ยุทธศาสตร์</span>
                                            </a>
                                        </li>
                                     
                                       
                                    </ul>
                                </li>

                                 <!--<li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                        <i class="nav-main-link-icon fa fa-user-circle"></i>
                                        <span class="nav-main-link-name">คำขอปฏิบัติราชการ</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                               
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/plan_setstory') }}">
                                                <span class="nav-main-link-name">กำหนดเรื่อง</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="">
                                                <span class="nav-main-link-name">มิติมุมมอง</span>
                                            </a>
                                        </li>
      
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="">
                                                <span class="nav-main-link-name">วัตถุประสงค์</span>
                                            </a>
                                        </li>
                                       
                                    </ul>
                                </li>-->
                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                        <i class="nav-main-link-icon fa fa-user-circle"></i>
                                        <span class="nav-main-link-name">ตัวชี้วัด</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/plan_kpiadddetail') }}">
                                                <span class="nav-main-link-name">ตัวชี้วัดยุทธศาสตร์</span>
                                            </a>
                                        </li>
                                         <!--<li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/detail4') }}">
                                                <span class="nav-main-link-name">ตัวชี้วัดบุคคล</span>
                                            </a>
                                        </li>-->
                                        
                                       
                                    </ul>
                                </li>

                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('manager_plan/project') ? ' active' : '' }}" href="{{ url('manager_plan/project') }}">
                                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">แผนงานโครงการ</span>
                    
                                    </a>
                                </li>


                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('manager_plan/humandev') ? ' active' : '' }}" href="{{ url('manager_plan/humandev') }}">
                                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">แผนพัฒนาบุคลากร</span>
                    
                                    </a>
                                </li>


                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('manager_plan/durable') ? ' active' : '' }}" href="{{ url('manager_plan/durable') }}">
                                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">แผนจัดซื้อครุภัณฑ์</span>
                    
                                    </a>
                                </li>
                                
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('manager_plan/repair') ? ' active' : '' }}" href="{{ url('manager_plan/repair') }}">
                                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">แผนบำรุงรักษา</span>
                    
                                    </a>
                                </li>



                                <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu"
                                        aria-haspopup="true" aria-expanded="false" href="#">
    
                                        <i class="nav-main-link-icon fas fa-cogs"></i>
                                        <span class="nav-main-link-name">ตั้งค่า</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                        <li class="nav-main-item">
                                            <a class="nav-main-link{{ request()->is('manager_plan/planyear') ? ' active' : '' }}" href="{{ url('manager_plan/planyear') }}">
                                                <!-- <i class="nav-main-link-icon fa fa-chart-pie"></i> -->
                                                <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">ปีงบประมาณ</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link{{ request()->is('manager_plan/plantype') ? ' active' : '' }}" href="{{ url('manager_plan/plantype') }}">
                                                <!-- <i class="nav-main-link-icon fa fa-chart-pie"></i> -->
                                                <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">ประเภทแผนงาน</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link{{ request()->is('manager_plan/plansupplies') ? ' active' : '' }}" href="{{ url('manager_plan/plansupplies') }}">
                                                <!-- <i class="nav-main-link-icon fa fa-chart-pie"></i> -->
                                                <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">แผนพัสดุ</span>
                                            </a>
                                        </li>
    
                                    </ul>
                                </li>

                                {{-- <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('manager_plan/plantype') ? ' active' : '' }}" href="{{ url('manager_plan/plantype') }}">
                                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">ตั้งค่าประเภทแผนงาน</span>
                    
                                    </a>
                                </li>

                                
                                <li class="nav-main-item">
                                    <a class="nav-main-link{{ request()->is('manager_plan/planyear') ? ' active' : '' }}" href="{{ url('manager_plan/planyear') }}">
                                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">ตั้งค่าปีงบประมาณตั้งต้น</span>
                    
                                    </a>
                                </li> --}}

 

                               <!--  <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                        <i class="nav-main-link-icon fa fa-user-circle"></i>
                                        <span class="nav-main-link-name">แผนงานโครงการ</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                    <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/project') }}">
                                                <span class="nav-main-link-name">แผนงานโครงการ</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/humandev') }}">
                                                <span class="nav-main-link-name">แผนพัฒนาบุคลากร</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/durable') }}">
                                                <span class="nav-main-link-name">แผนจัดซื้อครุภัณฑ์</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/employ') }}">
                                                <span class="nav-main-link-name">แผนจัดจ้าง จ้างเหมา ค่าเช่า ปรับปรุง</span>
                                            </a>
                                        </li>

                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/detail4') }}">
                                                <span class="nav-main-link-name">แผนจัดซ่อมบำรุง</span>
                                            </a>
                                        </li>
                                       
  
                            
                                        
                                       
                                    </ul>
                                </li>

                        <li class="nav-main-item"> 
                                        <a class="nav-main-link{{ request()->is('manager_medical/purchase') ? ' active' : '' }}" href="">
                                        <i class="nav-main-link-icon fa fa-calendar-alt"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">พันธกิจ</span>
                    
                                    </a>
                                </li>
                                       <!-- <li class="nav-main-item">
                                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                                        <i class="nav-main-link-icon fa fa-user-circle"></i>
                                        <span class="nav-main-link-name">โครงการตามยุทธศาสตร์</span>
                                    </a>
                                    <ul class="nav-main-submenu">
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/detail5') }}">
                                                <span class="nav-main-link-name">แผนงานยุทธศาสตร์</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url('manager_plan/detail6') }}">
                                                <span class="nav-main-link-name">รายงานจัดสรรและเบิกจ่ายโครงการตามยุทธศาสตร์ </span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="">
                                                <span class="nav-main-link-name">......</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="">
                                                <span class="nav-main-link-name">......</span>
                                            </a>
                                        </li>
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="">
                                                <span class="nav-main-link-name">......</span>
                                            </a>
                                        </li>
                                      
                                    </ul>
                                </li>
                               <li class="nav-main-item"> 
                                        <a class="nav-main-link{{ request()->is('manager_medical/purchase') ? ' active' : '' }}" href="{{ url('manager_medical/purchase') }}">
                                        <i class="nav-main-link-icon fa fa-calendar-alt"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">จัดซื้อยาและเวชภัณฑ์</span>
                    
                                    </a>
                                </li>
                                <li class="nav-main-item"> 
                                <a class="nav-main-link{{ request()->is('manager_medical/suppliesinfo') ? ' active' : '' }}" href="{{ url('manager_medical/suppliesinfo') }}">
                                        <i class="nav-main-link-icon fa fa-calendar-alt"></i>
                                        <span class="nav-main-link-name" style="font-size: 14px;font-family: 'Kanit', sans-serif;">ข้อมูลพัสดุยาและเวชภัณฑ์</span>
                    
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
                
                @yield('content')
             

            </main>
            <!-- END Main Container -->

            <script src="{{ asset('asset/js/dashmix.app.js') }}"></script>

            <!-- Laravel Scaffolding JS -->
            <script src="{{ asset('asset/js/laravel.app.js') }}"></script>

            @yield('footer')
    </body>
</html>


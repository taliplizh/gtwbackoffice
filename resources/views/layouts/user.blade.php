<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>Varsity | Home</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets_user/img/favicon.ico') }}" type="image/x-icon">

    <!-- Font awesome -->
    <link href="{{ asset('assets_user/css/font-awesome.css') }}" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="{{ asset('assets_user/css/bootstrap.css') }}" rel="stylesheet">   
    <!-- Slick slider -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets_user/css/slick.css') }}">          
    <!-- Fancybox slider -->
    <link rel="stylesheet" href="{{ asset('assets_user/css/jquery.fancybox.css') }}" type="text/css" media="screen" /> 
    <!-- Theme color -->
    <link id="switcher" href="{{ asset('assets_user/css/theme-color/default-theme.css') }}" rel="stylesheet">          

    <!-- Main style sheet -->
    <link href="{{ asset('assets_user/css/style.css') }}" rel="stylesheet">    

  

  </head>
  <body> 

  <!--START SCROLL TOP BUTTON -->
    <a class="scrollToTop" href="#">
      <i class="fa fa-angle-up"></i>      
    </a>
  <!-- END SCROLL TOP BUTTON -->

  <!-- Start header  -->
  
  <!-- End header  -->
  <!-- Start menu -->
  <section id="mu-menu">
    <nav class="navbar navbar-default" role="navigation">  
      <div class="container">
        <div class="navbar-header">
          <!-- FOR MOBILE VIEW COLLAPSED BUTTON -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- LOGO -->              
          <!-- TEXT BASED LOGO -->
          <a class="navbar-brand" href="#"><i class="fa fa-hospital"></i><span>BACK OFFICE</span></a>
          <!-- IMG BASED LOGO  -->
          <!-- <a class="navbar-brand" href="index.html"><img src="assets/img/logo.png" alt="logo"></a> -->
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul id="top-menu" class="nav navbar-nav navbar-right main-nav">
            <li class="active"><a href="#">ข้อมูลบุคลากร</a></li>            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">ข้อมูล <span class="fa fa-angle-down"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">ข้อมูล 1</a></li>                
                <li><a href="#">ข้อมูล 2</a></li>                
              </ul>
            </li>           
            <li><a href="#">ข้อมูล</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">ข้อมูล <span class="fa fa-angle-down"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">ข้อมูล</a></li>                
                <li><a href="#">ข้อมูล</a></li>                
              </ul>
            </li>            
          
          </ul>                     
        </div><!--/.nav-collapse -->        
      </div>     
    </nav>
  </section>
  <!-- End menu -->
  <!-- Start search box -->

  <div>
  @yield('content')
</div>		
   
  <!-- End footer -->
  
  <!-- jQuery library -->
  
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="{{ asset('assets_user/js/bootstrap.js') }}"></script>   
  <!-- Slick slider -->
  <script type="text/javascript" src="{{ asset('assets_user/js/slick.js') }}"></script>
  <!-- Counter -->
  <script type="text/javascript" src="{{ asset('assets_user/js/waypoints.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets_user/js/jquery.counterup.js') }}"></script>  
  <!-- Mixit slider -->
  <script type="text/javascript" src="{{ asset('assets_user/js/jquery.mixitup.js') }}"></script>
  <!-- Add fancyBox -->        
  <script type="text/javascript" src="{{ asset('assets_user/js/jquery.fancybox.pack.js') }}"></script>
  
  
  <!-- Custom js -->
  <script src="{{ asset('assets_user/js/custom.js') }}"></script> 

  </body>
</html>
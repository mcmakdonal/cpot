<!doctype html>
<html lang="en">

<head>
    <title>Mculture-Mobile @yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:url" content="{{ url('/') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="/assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/css/themify-icons.css">
    <link rel="stylesheet" href="/assets/css/metisMenu.css">
    <link rel="stylesheet" href="/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/css/slicknav.min.css">
    <!-- srtdash css -->
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
    {{--
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css"> --}} {{--
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css"> --}}
    <!-- style css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="/assets/css/typography.css">
    <link rel="stylesheet" href="/assets/css/default-css.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <!-- modernizr css -->
    <script src="/assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="/"><img src="/assets/images/backend_cover.png" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            @if(\Helper::instance()->check_role(9))
                                <li class="{{ (strpos(url()->current(),'administrator') ) ? 'active' : '' }}"><a href="/administrator"><i class="ti-user"></i> <span>การจัดการผู้ดูแลระบบ</span></a></li>
                            @endif
                            @if(\Helper::instance()->check_role(8))
                                <li class="{{ (strpos(url()->current(),'permission') ) ? 'active' : '' }}"><a href="/permission"><i class="fa fa-users"></i> <span>การจัดสิทธิ์การใช้งาน</span></a></li>
                            @endif
                            @if(\Helper::instance()->check_role(1))
                                <li class="{{ (strpos(url()->current(),'product-match') ) ? 'active' : '' }}"><a href="/product-match"><i class="ti-layers-alt"></i> <span>การจัดการ YouTube และ สินค้า</span></a></li>
                            @endif
                            @if(\Helper::instance()->check_role(2))
                                <li class="{{ (strpos(url()->current(),'evaluation') ) ? 'active' : '' }}"><a href="/evaluation"><i class="ti-comment-alt"></i> <span>การจัดการแบบประเมิน</span></a></li>
                            @endif
                            {{-- <li class="{{ (strpos(url()->current(),'ads') ) ? 'active' : '' }}"><a href="/ads"><i class="ti-blackboard"></i> <span>การจัดการโฆษณา</span></a></li> --}}
                            
                            @if(\Helper::instance()->check_role(3))
                                <li class="{{ (strpos(url()->current(),'background') ) ? 'active' : '' }}"><a href="/background"><i class="ti-panel"></i> <span>การจัดการแบล็คกราว</span></a></li>
                            @endif
                            {{-- <li class="{{ (strpos(url()->current(),'privacy') ) ? 'active' : '' }}"><a href="/privacy"><i class="ti-pencil-alt"></i> <span>การจัดการข้อกำหนด</span></a></li> --}}
                            
                            @if(\Helper::instance()->check_role(4))
                                <li class="{{ (strpos(url()->current(),'entrepreneur') ) ? 'active' : '' }}"><a href="/entrepreneur"><i class="ti-user"></i> <span>การจัดการผู้ประกอบการ</span></a></li>
                            @endif
                            @if(\Helper::instance()->check_role(5))
                                <li class="{{ (strpos(url()->current(),'material') ) ? 'active' : '' }}"><a href="/material"><i class="ti-spray"></i> <span>การจัดการทรัพยากร</span></a></li>
                            @endif
                            @if(\Helper::instance()->check_role(6))
                                <li class="{{ (strpos(url()->current(),'report') ) ? 'active' : '' }}">
                                    <a href="javascript:void(0)" aria-expanded="true"><i class="ti-files"></i><span>รายงาน</span></a>
                                    <ul class="collapse">
                                        <li><a href="/report/user">รายงานการเข้าใช้งาน แอพพลิเคชัน CPOT</a></li>
                                        <li><a href="/report/evaluation">รายงานแบบประเมิน</a></li>
                                        <li><a href="/report/tag">รายงาน Tag ที่นิยม</a></li>
                                        <li><a href="/report/share">รายงานการแบ่งปันองค์ความรู้และทรัพยากรวัตถุดิบ</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if(\Helper::instance()->check_role(7))
                                <li class="{{ (strpos(url()->current(),'/manual-page') ) ? 'active' : '' }}"><a href="/manual-page"><i class="ti-blackboard"></i> <span>คู่มือการใช้งาน</span></a></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <!-- nav and search button -->
                    <div class="col-md-6 col-sm-8 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <!-- profile info & task notification -->
                    <div class="col-md-6 col-sm-4 clearfix">
                    </div>
                </div>
            </div>
            <!-- header area end -->
            @yield('main')
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        <footer>
            <div class="footer-area">
                <p>© Copyright 2018. All right reserved.</p>
            </div>
        </footer>
        <!-- footer area end-->
    </div>
    <!-- page container area end -->
    <!-- jquery latest version -->
    <script src="/assets/js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/owl.carousel.min.js"></script>
    <script src="/assets/js/metisMenu.min.js"></script>
    <script src="/assets/js/jquery.slimscroll.min.js"></script>
    <script src="/assets/js/jquery.slicknav.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Start datatable js -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <!-- others plugins -->
    <script src="/assets/js/loadie.min.js"></script>
    <script src="/assets/js/plugins.js"></script>
    <script src="/assets/js/scripts.js"></script>
    <script src="/assets/js/custom.js"></script>
    <script>
        @yield('script')
    </script>
</body>

</html>
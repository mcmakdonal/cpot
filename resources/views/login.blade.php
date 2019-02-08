<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Mculture - Mobile @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="/assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/css/themify-icons.css">
    <link rel="stylesheet" href="/assets/css/metisMenu.css">
    <link rel="stylesheet" href="/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/css/slicknav.min.css">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
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
    <!-- login area start -->
    <div class="login-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div id="accordion5" class="according accordion-s2 gradiant-bg">
                                <div class="card">
                                    <div class="card-header">
                                        <a class="card-link collapsed" data-toggle="collapse" href="#accordion51" aria-expanded="true">ติดต่อเรา</a>
                                    </div>
                                    <div id="accordion51" class="collapse show" data-parent="#accordion5" style="">
                                        <div class="card-body">
                                            กระทรวงวัฒนธรรม เลขที่ 10 ถนนเทียมร่วมมิตร แขวงห้วยขวาง เขตห้วยขวาง กรุงเทพมหานคร 10310 <br
                                            /> E-mail : e-market@m-culture.go.th Tel : 02 209 3599, 02 209 3555 <br /> Facebook
                                            : <a href="https://www.facebook.com/pg/CulturalProductofThailand" target="_blank">www.facebook.com/pg/CulturalProductofThailand</a>                                            <br />
                                            <iframe class="mt-3" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7750.429575591582!2d100.576194!3d13.765917000000002!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29e880e4a0e63%3A0x46d2ae2c185387dd!2z4Lio4Li54LiZ4Lii4LmM4Lin4Lix4LiS4LiZ4LiY4Lij4Lij4Lih4LmB4Lir4LmI4LiH4Lib4Lij4Liw4LmA4LiX4Lio4LmE4LiX4Lii!5e0!3m2!1sth!2sus!4v1549426265381"
                                                width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="login-box">
                        {!! Form::open(['url' => "/backend-login",'class' => 'form-auth-small', 'method' => 'POST','files' => false]) !!}
                        <div class="login-form-head">
                            <img src="/assets/images/backend_cover.png" alt="logo">
                        </div>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif
                        <div class="login-form-body">
                            <div class="form-gp">
                                <label for="">ชื่อผู้ใช้งาน : </label>
                                <input type="text" name="username">
                                <i class="ti-user"></i>
                            </div>
                            <div class="form-gp">
                                <label for="">รหัสผ่าน : </label>
                                <input type="password" name="password">
                                <i class="ti-lock"></i>
                            </div>
                            <div class="row mb-4 rmber-area">
                                <div class="col-12 text-right">
                                    <a href="return javascript:void(0)" data-toggle="modal" data-target=".forget-password-modal-lg">ลืมรหัสผ่าน ?</a>
                                </div>
                            </div>
                            <div class="submit-btn-area">
                                <button id="form_submit" type="submit">เข้าสู่ระบบ <i class="ti-arrow-right"></i></button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- login area end -->

    <div class="modal fade forget-password-modal-lg" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ลืมรหัสผ่าน</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">อีเมล : </label>
                                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="อีเมล">
                                    <small id="emailHelp" class="form-text text-muted">ระบบจะส่งรหัสผ่านใหม่ไปให้ทาง อีเมล ที่ลงทะเบียนเข้าใช้งาน</small>
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                        <h4 id="msg"></h4>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary" onclick="forget_password()">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jquery latest version -->
    <script src="/assets/js/vendor/jquery-2.2.4.min.js"></script>
    <!-- bootstrap 4 js -->
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/owl.carousel.min.js"></script>
    <script src="/assets/js/metisMenu.min.js"></script>
    <script src="/assets/js/jquery.slimscroll.min.js"></script>
    <script src="/assets/js/jquery.slicknav.min.js"></script>

    <!-- others plugins -->
    <script src="/assets/js/loadie.min.js"></script>
    <script src="/assets/js/plugins.js"></script>
    <script src="/assets/js/scripts.js"></script>
    <script src="/assets/js/login.js"></script>
</body>

</html>
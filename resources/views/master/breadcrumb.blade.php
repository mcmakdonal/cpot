<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left" style="text-transform: capitalize;">{{ ($mtitle)? $mtitle : "" }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="/">หน้าหลัก</a></li>
                    <li><span>{{ ($stitle)? $stitle : "" }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            <div class="user-profile pull-right">
                <img class="avatar user-thumb" src="/assets/images/author/avatar.png" alt="avatar">
                @if (Cookie::get('ad_id') !== null)
                    <h4 class="user-name dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-angle-down"></i> {{ Cookie::get('ad_firstname') }}</h4>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/edit-profile">ข้อมูลส่วนตัว</a>
                        <a class="dropdown-item" href="/change-password">เปลี่ยนรหัสผ่าน</a>
                        <a class="dropdown-item" href="/backend-logout">ออกจากระบบ</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- page title area end -->
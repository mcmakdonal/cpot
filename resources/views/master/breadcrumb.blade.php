<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">{{ ucfirst(str_replace([url('/'),'/'],'',url()->current())) }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="index.html">Home</a></li>
                    <li><span>{{ ucfirst(str_replace([url('/'),'/'],'',url()->current())) }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            <div class="user-profile pull-right">
                <img class="avatar user-thumb" src="/assets/images/author/avatar.png" alt="avatar">
                @if (Cookie::get('ad_id') !== null)
                    <h4 class="user-name dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-angle-down"></i> {{ Cookie::get('ad_firstname') }}</h4>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/backend-logout">Log Out</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- page title area end -->
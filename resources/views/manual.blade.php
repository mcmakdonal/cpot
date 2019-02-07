@extends('master.master') 
@section('main')
    @include('master.breadcrumb', ['mtitle' => 'คู่มือการใช้งาน','stitle' => 'คู่มือการใช้งาน'])
<div class="col-12">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="header-title">คู่มือการใช้งาน</h4>
            <div class="row">
                <div class="col">
                    <ul>
                        <li> <a href="/manual/MCulture-Mobile_คู่มือการบริหารจัดการระบบ_Admin.pdf" target="_blank"> MCulture-Mobile คู่มือการบริหารจัดการระบบ Admin </a> </li>
                        <li> <a href="/manual/MCulture-Mobile_คู่มือการพัฒนา_Mobile_Application_V1.0.pdf" target="_blank"> MCulture-Mobile คู่มือการพัฒนา Mobile Application_V1.0 </a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
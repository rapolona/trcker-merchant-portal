<header class="section page-header">
    <!--RD Navbar-->
    <div class="rd-navbar-wrap">
        <nav class="rd-navbar">
            <div class="rd-navbar-panel">
                <div class="rd-navbar-panel-cell">
                    <button class="btn btn-navbar-panel rd-navbar-sidebar-toggle" data-navigation-switch="data-navigation-switch"><span class="fa-bars"></span></button>
                </div>
                <div class="rd-navbar-panel-cell rd-navbar-panel-cell-fullscreen">
                    <button class="btn btn-navbar-panel" data-fullscreen="html"><span class="fa-arrows-alt"></span></button>
                </div>
                <div class="rd-navbar-panel-cell rd-navbar-panel-cell-search" style="display: none">
                    <div class="rd-navbar-sidebar-search">
                        <input class="form-control" type="text" placeholder="Search"/>
                    </div>
                </div>
                <div class="rd-navbar-panel-center"></div>

                <div class="rd-navbar-panel-cell rd-navbar-panel-cell-user">
                    <div class="btn btn-navbar-panel" data-multi-switch='{"targets":"#subpanel-user","scope":"#subpanel-user","isolate":"[data-multi-switch]"}'>
                        <div class="media align-items-center"><img class="rounded-circle" src="{{ asset('images/users/user-03-50x50.jpg') }}" width="30" height="30" alt=""/>
                            <div class="media-body ml-2">
                                <p>{{ Config::get('gbl_profile')->first_name }} {{ Config::get('gbl_profile')->last_name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="rd-navbar-subpanel" id="subpanel-user">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="group-5 d-flex flex-wrap align-items-center"><img class="rounded mr-2" src="{{ asset('images/users/user-03-50x50.jpg') }}" width="50" height="50" alt=""/>
                                    <div class="panel-title">{{ Config::get('gbl_profile')->first_name }} {{ Config::get('gbl_profile')->last_name }}</div>
                                </div>
                            </div>
                            <div class="panel-body p-0 scroller scroller-vertical">
                                <div class="list-group">
                                    <a class="list-group-item rounded-0" href="{{ url('merchant/view_profile') }}">
                                        <div class="media align-items-center">
                                            <div class="pr-2"><span class="fa-user"></span></div>
                                            <div class="media-body">
                                                <h5>Merchant Information</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            </div>
                            <div class="panel-footer p-2">
                                <div class="d-flex align-items-center justify-content-between"><a class="btn btn-danger btn-sm" href="{{ url('logout') }}">Sing Out</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rd-navbar-panel-cell">
                    <button class="btn btn-navbar-panel" data-multi-switch='{"targets":".sidebar","scope":".sidebar","isolate":"[data-multi-switch]"}'><span class="fa-sliders"></span></button>
                </div>
            </div>
            @include('concrete.layouts.sidebar')
        </nav>
    </div>
</header>
<section class="topbar">
    <!-- Breadcrumbs-->
    <ul class="breadcrumbs">
        <li class="breadcrumbs-item"><a class="breadcrumbs-link" href="index.html"><span class="breadcrumbs-icon fa-home"></span><span>Dashboard</span></a></li>
    </ul>
</section>

@if(isset($formMessage))
<section class="global-message">
<div class="alert alert-dismissible alert-{{ $formMessage['type'] }} alert-sm" role="alert"><span class="alert-icon fa-warning"></span><span>{{ $formMessage['message'] }}</span>
    <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span class="fa-close" aria-hidden="true"></span></button>
</div>
</section>
@endif

@if (session('formMessage'))
    <section class="global-message">
        <div class="alert alert-dismissible alert-{{ session('formMessage')['type'] }} alert-sm" role="alert"><span class="alert-icon fa-warning"></span><span>{{ session('formMessage')['message'] }}</span>
            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span class="fa-close" aria-hidden="true"></span></button>
        </div>
    </section>
@endif

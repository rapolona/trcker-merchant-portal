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
                          <a href="{{ url('merchant/view_profile') }}" class="white">{{ Config::get('gbl_profile')->first_name }} {{ Config::get('gbl_profile')->last_name }}</a>
                        </div>
                      </div>
                    </div>
                </div>

                <div class="rd-navbar-panel-cell">
                    <button class="btn btn-navbar-panel"><a href="{{ url('logout') }}" class="white"><span class="fa-sign-out"></span></a></button>
                </div>

                <div class="rd-navbar-panel-cell">
                    <button class="btn btn-navbar-panel" data-multi-switch='{"targets":".sidebar","scope":".sidebar","isolate":"[data-multi-switch]"}'><span class="fa-info-circle"></span></button>
                </div>
            </div>
            @include('concrete.layouts.sidebar')
        </nav>
    </div>
</header>

@include('concrete.layouts.breadcrumbs')
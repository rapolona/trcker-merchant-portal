<div class="rd-navbar-sidebar scroller scroller-vertical">
    <div class="rd-navbar-sidebar-panel">
        <div>
            <div class="group-15">
                <div class="rd-navbar-brand logo2"><a href="{{ url('dashboard') }}"><img src="{{ asset(config('concreteadmin.logo_img', 'logo_img'))  }}" alt=""></a></div>
                <div class="rd-navbar-sidebar-search">
                    <input class="form-control" type="text" placeholder="Search"/>
                </div>
            </div>
        </div>
        <button class="btn btn-navbar-panel rd-navbar-sidebar-toggle" data-navigation-switch="data-navigation-switch"><span class="fa-bars"></span></button>
    </div>


    <ul class="rd-navbar-nav">
        <li class="rd-navbar-nav-item">
            <div class="rd-navbar-title"><span class="rd-navbar-title-icon"><span class="fa-ellipsis-h"></span></span><span class="rd-navbar-title-text">Menu</span></div>
        </li>
        <li class="rd-navbar-nav-item {{ Request::is('dashboard')? 'active' : '' }}"><a class="rd-navbar-link" href="{{ url('dashboard') }}"><span class="rd-navbar-icon fa-home"></span><span class="rd-navbar-link-text">Dashboard</span></a>
        </li>
        <li class="rd-navbar-nav-item {{ (Request::is('merchant/branch/*') || Request::is('merchant/branch')) ? 'active' : '' }}"><a class="rd-navbar-link" href="{{ url('merchant/branch') }}"><span class="rd-navbar-icon fa-sitemap"></span><span class="rd-navbar-link-text">Branches</span></a>
        </li>
        <li class="rd-navbar-nav-item {{ Request::is('task/*')? 'active' : '' }}"><a class="rd-navbar-link" href="#"><span class="rd-navbar-icon fa-columns"></span><span class="rd-navbar-link-text">Campaign Tasks</span></a>
            <ul class="rd-navbar-dropdown">
                <li class="rd-navbar-dropdown-item {{ Request::is('task/create')? 'active' : '' }}"><a class="rd-navbar-link" href="{{ url('task/create') }}"><span class="rd-navbar-icon mdi-file-presentation-box"></span>Create a task</a>
                </li>
                <li class="rd-navbar-dropdown-item {{ Request::is('task/view')? 'active' : '' }}"><a class="rd-navbar-link" href="{{ url('task/view') }}"><span class="rd-navbar-icon fa-wpforms"></span>View all tasks</a>
                </li>
            </ul>
        </li>
        <li class="rd-navbar-nav-item {{ (Request::is('campaign/*') || Request::is('campaign')) ? 'active' : '' }}"><a class="rd-navbar-link" href="#"><span class="rd-navbar-icon fa-star"></span><span class="rd-navbar-link-text">Campaigns</span></a>
            <ul class="rd-navbar-dropdown">
                <li class="rd-navbar-dropdown-item {{ Request::is('campaign/create')? 'active' : '' }}"><a class="rd-navbar-link" href="{{ url('campaign/create') }}"><span class="rd-navbar-icon fa-book"></span>Create a campaign</a>
                </li>
                <li class="rd-navbar-dropdown-item {{ Request::is('campaign/view')? 'active' : '' }}"><a class="rd-navbar-link" href="{{ url('campaign/view') }}"><span class="rd-navbar-icon fa-window-maximize"></span>View all campaigns</a>
                </li>
            </ul>
        </li>
        <li class="rd-navbar-nav-item {{ (Request::is('ticket/view/*') || Request::is('ticket/view'))? 'active' : '' }}"><a class="rd-navbar-link" href="{{ url('ticket/view/') }}"><span class="rd-navbar-icon fa-server"></span>Ticket Management</a></li>
    </ul>
</div>

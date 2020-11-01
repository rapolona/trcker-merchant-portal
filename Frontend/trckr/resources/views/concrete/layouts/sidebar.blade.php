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
        <li class="rd-navbar-nav-item">
            <div class="rd-navbar-title"><span class="rd-navbar-title-icon"><span class="fa-ellipsis-h"></span></span><span class="rd-navbar-title-text">Merchant</span></div>
        </li>
        <li class="rd-navbar-nav-item {{ Request::is('merchant/product/*')? 'active' : '' }}">
            <a class="rd-navbar-link" href="{{ url('merchant/product') }}">
                <span class="rd-navbar-icon mdi-apps"></span><span class="rd-navbar-link-text">Products</span>
            </a>
        </li>
        <li class="rd-navbar-nav-item {{ Request::is('merchant/branch/*')? 'active' : '' }}">
            <a class="rd-navbar-link" href="{{ url('merchant/branch') }}">
                <span class="rd-navbar-icon mdi-sitemap"></span><span class="rd-navbar-link-text">Branches</span>
            </a>
        </li>
        <li class="rd-navbar-nav-item {{ Request::is('merchant/users/*')? 'active' : '' }}">
            <a class="rd-navbar-link" href="{{ url('merchant/users') }}">
                <span class="rd-navbar-icon mdi-account-multiple"></span><span class="rd-navbar-link-text">Users</span>
            </a>
        </li>
        <li class="rd-navbar-nav-item {{ Request::is('merchant/rewards/*')? 'active' : '' }}">
            <a class="rd-navbar-link" href="{{ url('merchant/rewards') }}">
                <span class="rd-navbar-icon mdi-wallet-giftcard"></span><span class="rd-navbar-link-text">Rewards</span>
            </a>
        </li>
        <li class="rd-navbar-nav-item">
            <div class="rd-navbar-title"><span class="rd-navbar-title-icon"><span class="fa-ellipsis-h"></span></span><span class="rd-navbar-title-text">Campaigns</span></div>
        </li>
        <li class="rd-navbar-nav-item {{ Request::is('campaign/create/*')? 'active' : '' }}">
            <a class="rd-navbar-link" href="{{ url('campaign/create') }}">
                <span class="rd-navbar-icon mdi-table-row-plus-after"></span><span class="rd-navbar-link-text">Campaign Creation</span>
            </a>
        </li>
        <li class="rd-navbar-nav-item {{ Request::is('campaign/view/*')? 'active' : '' }}">
            <a class="rd-navbar-link" href="{{ url('campaign/view') }}">
                <span class="rd-navbar-icon mdi-table-large"></span><span class="rd-navbar-link-text">Campaign List</span>
            </a>
        </li>
        <li class="rd-navbar-nav-item">
            <div class="rd-navbar-title"><span class="rd-navbar-title-icon"><span class="fa-ellipsis-h"></span></span><span class="rd-navbar-title-text">Tasks</span></div>
        </li>
        <li class="rd-navbar-nav-item {{ Request::is('task/create/*')? 'active' : '' }}">
            <a class="rd-navbar-link" href="{{ url('task/create') }}">
                <span class="rd-navbar-icon mdi-playlist-plus"></span><span class="rd-navbar-link-text">Task Creation</span>
            </a>
        </li>
        <li class="rd-navbar-nav-item {{ Request::is('task/view/*')? 'active' : '' }}">
            <a class="rd-navbar-link" href="{{ url('task/view') }}">
                <span class="rd-navbar-icon mdi-format-list-numbers"></span><span class="rd-navbar-link-text">Task List</span>
            </a>
        </li>
        <li class="rd-navbar-nav-item">
            <div class="rd-navbar-title"><span class="rd-navbar-title-icon"><span class="fa-ellipsis-h"></span></span><span class="rd-navbar-title-text">Ticketing</span></div>
        </li>
        <li class="rd-navbar-nav-item {{ Request::is('ticket/view/*')? 'active' : '' }}">
            <a class="rd-navbar-link" href="{{ url('ticket/view') }}">
                <span class="rd-navbar-icon mdi-ticket-account"></span><span class="rd-navbar-link-text">Ticket Management</span>
            </a>
        </li>
        <li class="rd-navbar-nav-item">
            <div class="rd-navbar-title"><span class="rd-navbar-title-icon"><span class="fa-ellipsis-h"></span></span><span class="rd-navbar-title-text">Support</span></div>
        </li>
        <li class="rd-navbar-nav-item">
            <a class="rd-navbar-link" href="#">
                <span class="rd-navbar-icon mdi-message"></span><span class="rd-navbar-link-text">Support</span>
            </a>
        </li>
    </ul>
</div>

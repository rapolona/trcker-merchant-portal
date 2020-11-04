<section class="topbar">
    <div class="container-fluid">
        <div class="row row-15 align-items-center">
            <div class="col-md-5">
                <!-- Breadcrumbs-->
                <ul class="breadcrumbs">
                    @php
                        $uri = explode('/', Request::path());
                        $breadCrumbs = config('concreteadmin.menu');
                        $breadKeyStart = ($uri[0] == 'merchant')? 1 : 0;
                        $list = $breadCrumbs[$uri[0]];
                        $crud = ['add', 'edit'];
                        if($uri[0] == 'merchant'){
                            $list = $list[$uri[1]];
                        }
                    @endphp
                    <li class="breadcrumbs-item"><a class="breadcrumbs-link" href="{{ url($list['url']) }}"><span class="breadcrumbs-icon {{ $list['icon'] }}"></span><span>{{ $list['text'] }}</span></a></li>
                    @for ($i = $breadKeyStart + 1; $i < count($uri); $i++)
                        @if(!in_array($uri[$i-1], $crud))
                        <li class="breadcrumbs-item">{{ ucwords($uri[$i]) }}</li>
                        @endif
                    @endfor
                </ul>
            </div>
            <div class="col-md-7 text-md-right">
                <div class="group-10">
                    @yield('breadcrumbs_pull_right')
                </div>
            </div>
        </div>
    </div>
</section>

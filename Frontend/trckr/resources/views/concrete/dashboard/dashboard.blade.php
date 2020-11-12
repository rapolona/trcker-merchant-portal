@extends('concrete.layouts.main')

@section('content')
    <div class="row row-30">
        <div class="col-sm-6 col-md-4">
            <div class="panel bg-info text-white">
                <div class="panel-body">
                    <h1 class="mt-2">{{ $activecampaigns_count }}</h1>
                    <h2 class="mt-1 text-uppercase">Total Campaigns</h2>
                    <div class="highcharts-container" data-highcharts-options="{&quot;credits&quot;:false,&quot;colors&quot;:[&quot;#fff&quot;],&quot;chart&quot;:{&quot;backgroundColor&quot;:&quot;transparent&quot;,&quot;marginTop&quot;:30,&quot;marginBottom&quot;:0,&quot;type&quot;:&quot;column&quot;,&quot;spacingLeft&quot;:0,&quot;spacingRight&quot;:0},&quot;legend&quot;:{&quot;enabled&quot;:false},&quot;title&quot;:{&quot;text&quot;:null},&quot;xAxis&quot;:{&quot;lineWidth&quot;:0,&quot;tickLength&quot;:0,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false}},&quot;yAxis&quot;:{&quot;max&quot;:200,&quot;lineWidth&quot;:0,&quot;gridLineWidth&quot;:0,&quot;lineColor&quot;:&quot;#EEE&quot;,&quot;gridLineColor&quot;:&quot;#EEE&quot;,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false,&quot;style&quot;:{&quot;fontWeight&quot;:&quot;400&quot;}}},&quot;tooltip&quot;:{&quot;headerFormat&quot;:&quot;&lt;table&gt;&quot;,&quot;pointFormat&quot;:&quot;&lt;tr&gt;&lt;td style=\&quot;padding:0\&quot;&gt;&lt;b&gt;{point.y}&lt;/b&gt;&lt;/td&gt;&lt;/tr&gt;&quot;,&quot;footerFormat&quot;:&quot;&lt;/table&gt;&quot;,&quot;shared&quot;:true,&quot;useHTML&quot;:true},&quot;plotOptions&quot;:{&quot;column&quot;:{&quot;colorByPoint&quot;:true},&quot;series&quot;:{&quot;groupPadding&quot;:0,&quot;pointPadding&quot;:0.2}},&quot;series&quot;:[{&quot;data&quot;:[{{ $activecampaigns_count }}]}]}" style="height:240px;"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="panel bg-warning text-white">
                <div class="panel-body">
                    <h1 class="mt-2">{{ $total_respondents_count }}</h1>
                    <h2 class="mt-1 text-uppercase">Total Participants</h2>
                    <div class="highcharts-container" data-highcharts-options="{&quot;credits&quot;:false,&quot;colors&quot;:[&quot;#fff&quot;],&quot;chart&quot;:{&quot;backgroundColor&quot;:&quot;transparent&quot;,&quot;marginTop&quot;:30,&quot;marginBottom&quot;:0,&quot;type&quot;:&quot;column&quot;,&quot;spacingLeft&quot;:0,&quot;spacingRight&quot;:0},&quot;legend&quot;:{&quot;enabled&quot;:false},&quot;title&quot;:{&quot;text&quot;:null},&quot;xAxis&quot;:{&quot;lineWidth&quot;:0,&quot;tickLength&quot;:0,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false}},&quot;yAxis&quot;:{&quot;max&quot;:200,&quot;lineWidth&quot;:0,&quot;gridLineWidth&quot;:0,&quot;lineColor&quot;:&quot;#EEE&quot;,&quot;gridLineColor&quot;:&quot;#EEE&quot;,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false,&quot;style&quot;:{&quot;fontWeight&quot;:&quot;400&quot;}}},&quot;tooltip&quot;:{&quot;headerFormat&quot;:&quot;&lt;table&gt;&quot;,&quot;pointFormat&quot;:&quot;&lt;tr&gt;&lt;td style=\&quot;padding:0\&quot;&gt;&lt;b&gt;{point.y}&lt;/b&gt;&lt;/td&gt;&lt;/tr&gt;&quot;,&quot;footerFormat&quot;:&quot;&lt;/table&gt;&quot;,&quot;shared&quot;:true,&quot;useHTML&quot;:true},&quot;plotOptions&quot;:{&quot;column&quot;:{&quot;colorByPoint&quot;:true},&quot;series&quot;:{&quot;groupPadding&quot;:0,&quot;pointPadding&quot;:0.2}},&quot;series&quot;:[{&quot;data&quot;:[{{ $total_respondents_count }}]}]}" style="height:240px;"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="panel bg-danger text-white">
                <div class="panel-body">
                    <h1 class="mt-2">0</h1>
                    <h2 class="mt-1 text-uppercase">Total Submissions</h2>
                    <div class="highcharts-container" data-highcharts-options="{&quot;credits&quot;:false,&quot;colors&quot;:[&quot;#fff&quot;],&quot;chart&quot;:{&quot;backgroundColor&quot;:&quot;transparent&quot;,&quot;marginTop&quot;:30,&quot;marginBottom&quot;:0,&quot;type&quot;:&quot;column&quot;,&quot;spacingLeft&quot;:0,&quot;spacingRight&quot;:0},&quot;legend&quot;:{&quot;enabled&quot;:false},&quot;title&quot;:{&quot;text&quot;:null},&quot;xAxis&quot;:{&quot;lineWidth&quot;:0,&quot;tickLength&quot;:0,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false}},&quot;yAxis&quot;:{&quot;max&quot;:100,&quot;lineWidth&quot;:0,&quot;gridLineWidth&quot;:0,&quot;lineColor&quot;:&quot;#EEE&quot;,&quot;gridLineColor&quot;:&quot;#EEE&quot;,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false,&quot;style&quot;:{&quot;fontWeight&quot;:&quot;400&quot;}}},&quot;tooltip&quot;:{&quot;headerFormat&quot;:&quot;&lt;table&gt;&quot;,&quot;pointFormat&quot;:&quot;&lt;tr&gt;&lt;td style=\&quot;padding:0\&quot;&gt;&lt;b&gt;{point.y}&lt;/b&gt;&lt;/td&gt;&lt;/tr&gt;&quot;,&quot;footerFormat&quot;:&quot;&lt;/table&gt;&quot;,&quot;shared&quot;:true,&quot;useHTML&quot;:true},&quot;plotOptions&quot;:{&quot;column&quot;:{&quot;colorByPoint&quot;:true},&quot;series&quot;:{&quot;groupPadding&quot;:0,&quot;pointPadding&quot;:0.2}},&quot;series&quot;:[{&quot;data&quot;:[0]}]}" style="height:240px;"></div>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-12">
            <div class="panel admin-panel admin-panel panel-nav p-0" data-admin-panel='{"panelMenuClass":"px-3"}'>
                <div class="panel-header d-flex align-items-center justify-content-between pr-3">
                    <ul class="nav nav-tabs scroller scroller-horizontal" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#dashboardGraph1" role="tab" aria-controls="dashboardGraph1" aria-selected="true">User activity</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#dashboardGraph2" role="tab" aria-controls="dashboardGraph2" aria-selected="false">Popular items</a></li>
                    </ul>
                    <div class="admin-panel-buttons py-2 px-3">     
                        <button class="admin-panel-button fa" title="collapse" data-panel="collapse"></button>
                        <button class="admin-panel-button fa" title="fullscreen" data-panel="fullscreen"></button>
                        <button class="admin-panel-button fa" title="remove" data-panel="remove"></button>
                    </div>
                    <div class="admin-panel-switch fa-bars"></div>
                </div>
                <div class="panel-body p-0">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="dashboardGraph1" role="tabpanel">
                            <div class="row no-gutters">
                                <div class="col-md-8 col-lg-9">
                                    <div class="highcharts-container highcharts-container-lg" data-highcharts-options="{&quot;credits&quot;:false,&quot;colors&quot;:[&quot;#afc1d9&quot;,&quot;#6e93ba&quot;,&quot;#36506D&quot;,&quot;#6e93ba&quot;],&quot;chart&quot;:{&quot;backgroundColor&quot;:&quot;transparent&quot;,&quot;type&quot;:&quot;areaspline&quot;,&quot;zoomType&quot;:&quot;x&quot;,&quot;panning&quot;:true,&quot;panKey&quot;:&quot;shift&quot;,&quot;marginTop&quot;:45,&quot;marginRight&quot;:0,&quot;spacingBottom&quot;:0,&quot;spacingTop&quot;:20,&quot;spacingLeft&quot;:0,&quot;spacingRight&quot;:0},&quot;title&quot;:{&quot;text&quot;:null},&quot;xAxis&quot;:{&quot;min&quot;:0,&quot;max&quot;:11,&quot;labels&quot;:{&quot;enabled&quot;:false},&quot;gridLineWidth&quot;:0,&quot;lineWidth&quot;:0,&quot;tickWidth&quot;:0,&quot;tickLength&quot;:0},&quot;yAxis&quot;:{&quot;labels&quot;:{&quot;enabled&quot;:false},&quot;gridLineWidth&quot;:0,&quot;lineWidth&quot;:0,&quot;tickWidth&quot;:0,&quot;tickLength&quot;:0,&quot;title&quot;:{&quot;text&quot;:null}},&quot;tooltip&quot;:{&quot;pointFormat&quot;:&quot;{series.name} produced &lt;b&gt;{point.y:,.0f}&lt;/b&gt;&lt;br/&gt;warheads in {point.x}&quot;},&quot;plotOptions&quot;:{&quot;area&quot;:{&quot;pointStart&quot;:1940,&quot;marker&quot;:{&quot;enabled&quot;:false,&quot;symbol&quot;:&quot;circle&quot;,&quot;radius&quot;:2,&quot;states&quot;:{&quot;hover&quot;:{&quot;enabled&quot;:true}}}},&quot;series&quot;:{&quot;marker&quot;:{&quot;enabled&quot;:false}}},&quot;legend&quot;:{&quot;enabled&quot;:true,&quot;floating&quot;:false,&quot;align&quot;:&quot;right&quot;,&quot;verticalAlign&quot;:&quot;top&quot;,&quot;x&quot;:-35,&quot;symbolHeight&quot;:14,&quot;symbolWidth&quot;:14,&quot;symbolRadius&quot;:7,&quot;itemStyle&quot;:{&quot;fontWeight&quot;:400,&quot;fontSize&quot;:&quot;15px&quot;}},&quot;series&quot;:[{&quot;name&quot;:&quot;Task 1&quot;,&quot;data&quot;:[15,19,22.7,29.3,22,17,23.8,19.1,22.1,14.1,11.6,7.5],&quot;lineWidth&quot;:0},{&quot;name&quot;:&quot;Task 2&quot;,&quot;data&quot;:[2.9,3.2,4.7,5.5,8.9,12.2,17,16.6,14.2,10.3,6.6,4.8],&quot;lineWidth&quot;:0},{&quot;name&quot;:&quot;Task 3&quot;,&quot;data&quot;:[0.9,1.9,2.1,3.5,4.5,3.2,1,6.6,9.2,8.1,4.2,1.8],&quot;lineWidth&quot;:0}]}" style="height: 324px"></div>
                                </div>
                                <div class="col-md-4 col-lg-3 py-4 px-3 border-md-left">
                                    <div class="list-block-container list-block-lg">
                                        <div class="list-block-title d-flex justify-content-between align-items-baseline">Top Campaigns</div>
                                        <div class="list-block">
                                            <div class="list-block-item d-flex justify-content-between"><a href="#">Campaign 1</a>
                                                <div>1,926</div>
                                            </div>
                                            <div class="list-block-item d-flex justify-content-between"><a href="#">Campaign 2</a>
                                                <div>1,254</div>
                                            </div>
                                            <div class="list-block-item d-flex justify-content-between"><a href="#">Campaign 3</a>
                                                <div>783</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="dashboardGraph2" role="tabpanel">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-8 col-lg-9 pt-4">
                                        <div class="highcharts-container highcharts-container-lg" data-highcharts-options="{&quot;credits&quot;:false,&quot;colors&quot;:[&quot;#6d92b9&quot;,&quot;#f8c450&quot;,&quot;#6cd1e1&quot;,&quot;#17a2b8&quot;],&quot;chart&quot;:{&quot;type&quot;:&quot;column&quot;,&quot;marginTop&quot;:50},&quot;title&quot;:{&quot;text&quot;:null},&quot;legend&quot;:{&quot;enabled&quot;:true,&quot;floating&quot;:true,&quot;align&quot;:&quot;right&quot;,&quot;verticalAlign&quot;:&quot;top&quot;,&quot;x&quot;:-20},&quot;xAxis&quot;:{&quot;categories&quot;:[&quot;Jan&quot;,&quot;Feb&quot;,&quot;Mar&quot;,&quot;Apr&quot;,&quot;May&quot;,&quot;Jun&quot;,&quot;Jul&quot;,&quot;Aug&quot;,&quot;Sep&quot;,&quot;Oct&quot;,&quot;Nov&quot;,&quot;Dec&quot;],&quot;crosshair&quot;:true},&quot;yAxis&quot;:{&quot;min&quot;:0,&quot;title&quot;:{&quot;text&quot;:&quot;&quot;}},&quot;tooltip&quot;:{&quot;headerFormat&quot;:&quot;&lt;span style=\&quot;font-size:10px\&quot;&gt;{point.key}&lt;/span&gt;&lt;table&gt;&quot;,&quot;pointFormat&quot;:&quot;&lt;tr&gt;&lt;td style=\&quot;color:{series.color};padding:0\&quot;&gt;{series.name}: &lt;/td&gt;&lt;td style=\&quot;padding:0\&quot;&gt;&lt;b&gt;{point.y:.1f}&lt;/b&gt;&lt;/td&gt;&lt;/tr&gt;&quot;,&quot;footerFormat&quot;:&quot;&lt;/table&gt;&quot;,&quot;shared&quot;:true,&quot;useHTML&quot;:true},&quot;plotOptions&quot;:{&quot;column&quot;:{&quot;pointPadding&quot;:0.2,&quot;borderWidth&quot;:0}},&quot;series&quot;:[{&quot;name&quot;:&quot;Approved&quot;,&quot;data&quot;:[5.6,7.8,16.5,22.4,20,14.5,10,5.3,9.2,11.5,20.6,25.3]},{&quot;name&quot;:&quot;Rejected&quot;,&quot;data&quot;:[15.4,13.2,25.5,28.7,24.6,21.5,25.4,20.4,22.6,16.1,11.8,10.1]}]}" style="height: 300px"></div>
                                    </div>
                                    <div class="col-md-4 col-lg-3 py-4 px-3 border-md-left">
                                        <div class="list-block-container list-block-lg">
                                            <div class="list-block-title d-flex justify-content-between align-items-baseline">Top Campaigns</div>
                                            <div class="list-block">
                                                <div class="list-block-item d-flex justify-content-between"><a href="#">Campaign 1</a>
                                                    <div>1,254</div>
                                                </div>
                                                <div class="list-block-item d-flex justify-content-between"><a href="#">Campaign 2</a>
                                                    <div>783</div>
                                                </div>
                                                <div class="list-block-item d-flex justify-content-between"><a href="#">Campaign 3</a>
                                                    <div>1,926</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="col-md-6">
            <div class="panel admin-panel">
                <div class="panel-header d-flex align-items-center">
                    <div class="panel-title flex-grow-1">Calendar widget</div>
                    <div class="admin-panel-buttons">
                        <button class="admin-panel-button fa" title="title" data-panel="title"></button>
                        <!-- <button class="admin-panel-button fa" title="color" data-panel="color"></button> -->
                        <button class="admin-panel-button fa" title="collapse" data-panel="collapse"></button>
                        <button class="admin-panel-button fa" title="fullscreen" data-panel="fullscreen"></button>
                        <button class="admin-panel-button fa" title="remove" data-panel="remove"></button>
                    </div>
                    <div class="admin-panel-switch fa-bars"></div>
                </div>
                <div class="panel-body pt-3">
                    <div class="fullcalendar calendar-widget" data-fullcalendar-header='{"left":"title","center":"","right":"today prev,next"}' data-fullcalendar-event='[{"title":"Sony Meeting","start":"2019-05-06","className":"fc-event-success"},{"title":"Conference","start":"2019-05-14","end":"2019-05-16","className":"fc-event-warning"},{"title":"System Testing","start":"2019-05-26","end":"2019-05-28","className":"fc-event-primary"}]'></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel admin-panel">
                <div class="panel-header d-flex align-items-center">
                    <div class="panel-title flex-grow-1">Crawler list</div>
                    <div class="admin-panel-buttons">
                        <button class="admin-panel-button fa" title="title" data-panel="title"></button>
                        <!-- <button class="admin-panel-button fa" title="color" data-panel="color"></button> -->
                        <button class="admin-panel-button fa" title="collapse" data-panel="collapse"></button>
                        <button class="admin-panel-button fa" title="fullscreen" data-panel="fullscreen"></button>
                        <button class="admin-panel-button fa" title="remove" data-panel="remove"></button>
                    </div>
                    <div class="admin-panel-switch fa-bars"></div>
                </div>
                <div class="panel-body p-0">
                    <div class="highcharts-container" data-highcharts-options="{&quot;colors&quot;:[&quot;#6cd1e1&quot;,&quot;#f8c450&quot;,&quot;#ee6161&quot;,&quot;#6d92b9&quot;],&quot;chart&quot;:{&quot;type&quot;:&quot;bar&quot;},&quot;title&quot;:{&quot;text&quot;:null},&quot;xAxis&quot;:{&quot;labels&quot;:{&quot;style&quot;:{&quot;color&quot;:&quot;#9575cd&quot;}},&quot;tickColor&quot;:&quot;rgba(255, 255, 255, 0)&quot;},&quot;yAxis&quot;:{&quot;min&quot;:0,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;style&quot;:{&quot;color&quot;:&quot;#9575cd&quot;}},&quot;minTickInterval&quot;:20,&quot;lineWidth&quot;:1},&quot;tooltip&quot;:{&quot;headerFormat&quot;:&quot;&lt;table&gt;&quot;,&quot;pointFormat&quot;:&quot;&lt;tr&gt;&lt;td style=\&quot;padding:0\&quot;&gt;{series.name}: &lt;b&gt;{point.y}&lt;/b&gt;&lt;/td&gt;&lt;/tr&gt;&quot;,&quot;footerFormat&quot;:&quot;&lt;/table&gt;&quot;},&quot;plotOptions&quot;:{&quot;series&quot;:{&quot;groupPadding&quot;:0.03,&quot;borderRadius&quot;:18}},&quot;legend&quot;:{&quot;itemStyle&quot;:{&quot;fontWeight&quot;:400,&quot;fontSize&quot;:&quot;14px&quot;,&quot;color&quot;:&quot;#797b7c&quot;}},&quot;credits&quot;:{&quot;enabled&quot;:false},&quot;series&quot;:[{&quot;name&quot;:&quot;Safari&quot;,&quot;data&quot;:[67]},{&quot;name&quot;:&quot;Internet Explorer&quot;,&quot;data&quot;:[33]},{&quot;name&quot;:&quot;Firefox&quot;,&quot;data&quot;:[57]},{&quot;name&quot;:&quot;Chrome&quot;,&quot;data&quot;:[52]}]}" style="height: 300px"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

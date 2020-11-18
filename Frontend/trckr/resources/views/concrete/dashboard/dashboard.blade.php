@extends('concrete.layouts.main')

@section('content')

        <div class="container-fluid">
            <div class="row row-30">
                <div class="col-sm-6 col-md-6">
                    <div class="panel bg-info text-white">
                        <div class="panel-body">
                            <h1 class="mt-2">{{ $activecampaigns_count }}</h1>
                            <h2 class="mt-1 text-uppercase">Total Campaigns</h2>
                            <div class="highcharts-container" data-highcharts-options="{&quot;credits&quot;:false,&quot;colors&quot;:[&quot;#fff&quot;],&quot;chart&quot;:{&quot;backgroundColor&quot;:&quot;transparent&quot;,&quot;marginTop&quot;:30,&quot;marginBottom&quot;:0,&quot;type&quot;:&quot;column&quot;,&quot;spacingLeft&quot;:0,&quot;spacingRight&quot;:0},&quot;legend&quot;:{&quot;enabled&quot;:false},&quot;title&quot;:{&quot;text&quot;:null},&quot;xAxis&quot;:{&quot;lineWidth&quot;:0,&quot;tickLength&quot;:0,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false}},&quot;yAxis&quot;:{&quot;max&quot;:200,&quot;lineWidth&quot;:0,&quot;gridLineWidth&quot;:0,&quot;lineColor&quot;:&quot;#EEE&quot;,&quot;gridLineColor&quot;:&quot;#EEE&quot;,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false,&quot;style&quot;:{&quot;fontWeight&quot;:&quot;400&quot;}}},&quot;tooltip&quot;:{&quot;headerFormat&quot;:&quot;&lt;table&gt;&quot;,&quot;pointFormat&quot;:&quot;&lt;tr&gt;&lt;td style=\&quot;padding:0\&quot;&gt;&lt;b&gt;{point.y}&lt;/b&gt;&lt;/td&gt;&lt;/tr&gt;&quot;,&quot;footerFormat&quot;:&quot;&lt;/table&gt;&quot;,&quot;shared&quot;:true,&quot;useHTML&quot;:true},&quot;plotOptions&quot;:{&quot;column&quot;:{&quot;colorByPoint&quot;:true},&quot;series&quot;:{&quot;groupPadding&quot;:0,&quot;pointPadding&quot;:0.2}},&quot;series&quot;:[{&quot;data&quot;:[{{ $activecampaigns_count }}]}]}" style="height:240px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="panel bg-danger text-white">
                        <div class="panel-body">
                            <h1 class="mt-2">{{ $total_respondents_count  }}</h1>
                            <h2 class="mt-1 text-uppercase">Total Submissions</h2>
                            <div class="highcharts-container" data-highcharts-options="{&quot;credits&quot;:false,&quot;colors&quot;:[&quot;#fff&quot;],&quot;chart&quot;:{&quot;backgroundColor&quot;:&quot;transparent&quot;,&quot;marginTop&quot;:30,&quot;marginBottom&quot;:0,&quot;type&quot;:&quot;column&quot;,&quot;spacingLeft&quot;:0,&quot;spacingRight&quot;:0},&quot;legend&quot;:{&quot;enabled&quot;:false},&quot;title&quot;:{&quot;text&quot;:null},&quot;xAxis&quot;:{&quot;lineWidth&quot;:0,&quot;tickLength&quot;:0,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false}},&quot;yAxis&quot;:{&quot;max&quot;:100,&quot;lineWidth&quot;:0,&quot;gridLineWidth&quot;:0,&quot;lineColor&quot;:&quot;#EEE&quot;,&quot;gridLineColor&quot;:&quot;#EEE&quot;,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false,&quot;style&quot;:{&quot;fontWeight&quot;:&quot;400&quot;}}},&quot;tooltip&quot;:{&quot;headerFormat&quot;:&quot;&lt;table&gt;&quot;,&quot;pointFormat&quot;:&quot;&lt;tr&gt;&lt;td style=\&quot;padding:0\&quot;&gt;&lt;b&gt;{point.y}&lt;/b&gt;&lt;/td&gt;&lt;/tr&gt;&quot;,&quot;footerFormat&quot;:&quot;&lt;/table&gt;&quot;,&quot;shared&quot;:true,&quot;useHTML&quot;:true},&quot;plotOptions&quot;:{&quot;column&quot;:{&quot;colorByPoint&quot;:true},&quot;series&quot;:{&quot;groupPadding&quot;:0,&quot;pointPadding&quot;:0.2}},&quot;series&quot;:[{&quot;data&quot;:[{{ $total_respondents_count }}]}]}" style="height:240px;"></div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-header">
                            <div class="row">
                                <div class="panel-title"><span class="panel-icon fa-ticket"></span> <span>Campaigns</span></div>
                            </div>
                        </div>
                        <div class="panel-body p-0">
                            <div class="table-responsive scroller scroller-horizontal py-3">
                                <table class="table table-striped table-hover data-table" data-table-searching="true" data-table-lengthChange="true" data-page-length="5" >
                                    <thead>
                                    <tr>
                                        <th class="no-sort">#</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($campaign_per_status as $k => $t)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ $t->campaign_name }}</td>
                                            <td>
                                                <div class="badge badge-success">{{ $t->Approved }} - Approved</div>
                                                <div class="badge badge-danger">{{ $t->Rejected }}  - Rejected</div>
                                                <div class="badge badge-warning">{{ $t->Pending }}  - Pending</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="panel admin-panel">
                        <div class="panel-header d-flex align-items-center">
                            <div class="panel-title flex-grow-1">Campaign Status</div>
                            <div class="admin-panel-buttons">
                                <button class="admin-panel-button fa" title="title" data-panel="title"></button>
                                <button class="admin-panel-button fa" title="color" data-panel="color"></button>
                                <button class="admin-panel-button fa" title="collapse" data-panel="collapse"></button>
                                <button class="admin-panel-button fa" title="fullscreen" data-panel="fullscreen"></button>
                                <button class="admin-panel-button fa" title="remove" data-panel="remove"></button>
                            </div>
                            <div class="admin-panel-switch fa-bars"></div>
                        </div>
                        <div class="panel-body p-0">
                            <div class="highcharts-container" data-highcharts-options="{&quot;colors&quot;:[&quot;#6cd1e1&quot;,&quot;#f8c450&quot;,&quot;#ee6161&quot;,&quot;#6d92b9&quot;],&quot;chart&quot;:{&quot;type&quot;:&quot;bar&quot;},&quot;title&quot;:{&quot;text&quot;:null},&quot;xAxis&quot;:{&quot;labels&quot;:{&quot;style&quot;:{&quot;color&quot;:&quot;#9575cd&quot;}},&quot;tickColor&quot;:&quot;rgba(255, 255, 255, 0)&quot;},&quot;yAxis&quot;:{&quot;min&quot;:0,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;style&quot;:{&quot;color&quot;:&quot;#9575cd&quot;}},&quot;minTickInterval&quot;:20,&quot;lineWidth&quot;:1},&quot;tooltip&quot;:{&quot;headerFormat&quot;:&quot;&lt;table&gt;&quot;,&quot;pointFormat&quot;:&quot;&lt;tr&gt;&lt;td style=\&quot;padding:0\&quot;&gt;{series.name}: &lt;b&gt;{point.y}&lt;/b&gt;&lt;/td&gt;&lt;/tr&gt;&quot;,&quot;footerFormat&quot;:&quot;&lt;/table&gt;&quot;},&quot;plotOptions&quot;:{&quot;series&quot;:{&quot;groupPadding&quot;:0.03,&quot;borderRadius&quot;:18}},&quot;legend&quot;:{&quot;itemStyle&quot;:{&quot;fontWeight&quot;:400,&quot;fontSize&quot;:&quot;14px&quot;,&quot;color&quot;:&quot;#797b7c&quot;}},&quot;credits&quot;:{&quot;enabled&quot;:false},&quot;series&quot;:[{&quot;name&quot;:&quot;{{ (isset($count_campaign_status[1]))? $count_campaign_status[1]->status : 'DONE' }}&quot;,&quot;data&quot;:[{{ (isset($count_campaign_status[1]))? $count_campaign_status[1]->campaigns : 0 }}]},{&quot;name&quot;:&quot;{{ (isset($count_campaign_status[2]))? $count_campaign_status[2]->status : 'ONGOING' }}&quot;,&quot;data&quot;:[{{ (isset($count_campaign_status[2]))? $count_campaign_status[2]->campaigns : 0 }}]},{&quot;name&quot;:&quot;{{ (isset($count_campaign_status[0]))? $count_campaign_status[0]->status : 'DISABLED' }}&quot;,&quot;data&quot;:[{{ (isset($count_campaign_status[0]))? $count_campaign_status[0]->campaigns : 0 }}]}]}" style="height: 300px"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-header">
                            <div class="panel-title"><span class="panel-icon fa-pencil"></span> <span>Campaign by Type</span>
                            </div>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                @php
                                $color = ['primary', 'success', 'info', 'danger'];
                                @endphp
                                @foreach($count_campaign_type as $k => $cct)
                                <li class="list-group-item d-flex justify-content-between align-items-center"><span>{{ $cct->campaign_type }}</span>
                                    <div class="badge badge-{{ $color[$k] }} badge-pill">{{ $cct->campaigns }}</div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@endsection

@extends('concrete.layouts.main')

@section('content')
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
        <!--<div class="col-sm-6 col-md-4">
            <div class="panel bg-warning text-white">
                <div class="panel-body">
                    <h1 class="mt-2">{{ $total_respondents_count }}</h1>
                    <h2 class="mt-1 text-uppercase">Total Participants</h2>
                    <div class="highcharts-container" data-highcharts-options="{&quot;credits&quot;:false,&quot;colors&quot;:[&quot;#fff&quot;],&quot;chart&quot;:{&quot;backgroundColor&quot;:&quot;transparent&quot;,&quot;marginTop&quot;:30,&quot;marginBottom&quot;:0,&quot;type&quot;:&quot;column&quot;,&quot;spacingLeft&quot;:0,&quot;spacingRight&quot;:0},&quot;legend&quot;:{&quot;enabled&quot;:false},&quot;title&quot;:{&quot;text&quot;:null},&quot;xAxis&quot;:{&quot;lineWidth&quot;:0,&quot;tickLength&quot;:0,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false}},&quot;yAxis&quot;:{&quot;max&quot;:200,&quot;lineWidth&quot;:0,&quot;gridLineWidth&quot;:0,&quot;lineColor&quot;:&quot;#EEE&quot;,&quot;gridLineColor&quot;:&quot;#EEE&quot;,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false,&quot;style&quot;:{&quot;fontWeight&quot;:&quot;400&quot;}}},&quot;tooltip&quot;:{&quot;headerFormat&quot;:&quot;&lt;table&gt;&quot;,&quot;pointFormat&quot;:&quot;&lt;tr&gt;&lt;td style=\&quot;padding:0\&quot;&gt;&lt;b&gt;{point.y}&lt;/b&gt;&lt;/td&gt;&lt;/tr&gt;&quot;,&quot;footerFormat&quot;:&quot;&lt;/table&gt;&quot;,&quot;shared&quot;:true,&quot;useHTML&quot;:true},&quot;plotOptions&quot;:{&quot;column&quot;:{&quot;colorByPoint&quot;:true},&quot;series&quot;:{&quot;groupPadding&quot;:0,&quot;pointPadding&quot;:0.2}},&quot;series&quot;:[{&quot;data&quot;:[{{ $total_respondents_count }}]}]}" style="height:240px;"></div>
                </div>
            </div>
        </div>-->
        <div class="col-sm-6 col-md-6">
            <div class="panel bg-danger text-white">
                <div class="panel-body">
                    <h1 class="mt-2">{{ $total_respondents_count  }}</h1>
                    <h2 class="mt-1 text-uppercase">Total Submissions</h2>
                    <div class="highcharts-container" data-highcharts-options="{&quot;credits&quot;:false,&quot;colors&quot;:[&quot;#fff&quot;],&quot;chart&quot;:{&quot;backgroundColor&quot;:&quot;transparent&quot;,&quot;marginTop&quot;:30,&quot;marginBottom&quot;:0,&quot;type&quot;:&quot;column&quot;,&quot;spacingLeft&quot;:0,&quot;spacingRight&quot;:0},&quot;legend&quot;:{&quot;enabled&quot;:false},&quot;title&quot;:{&quot;text&quot;:null},&quot;xAxis&quot;:{&quot;lineWidth&quot;:0,&quot;tickLength&quot;:0,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false}},&quot;yAxis&quot;:{&quot;max&quot;:100,&quot;lineWidth&quot;:0,&quot;gridLineWidth&quot;:0,&quot;lineColor&quot;:&quot;#EEE&quot;,&quot;gridLineColor&quot;:&quot;#EEE&quot;,&quot;title&quot;:{&quot;text&quot;:null},&quot;labels&quot;:{&quot;enabled&quot;:false,&quot;style&quot;:{&quot;fontWeight&quot;:&quot;400&quot;}}},&quot;tooltip&quot;:{&quot;headerFormat&quot;:&quot;&lt;table&gt;&quot;,&quot;pointFormat&quot;:&quot;&lt;tr&gt;&lt;td style=\&quot;padding:0\&quot;&gt;&lt;b&gt;{point.y}&lt;/b&gt;&lt;/td&gt;&lt;/tr&gt;&quot;,&quot;footerFormat&quot;:&quot;&lt;/table&gt;&quot;,&quot;shared&quot;:true,&quot;useHTML&quot;:true},&quot;plotOptions&quot;:{&quot;column&quot;:{&quot;colorByPoint&quot;:true},&quot;series&quot;:{&quot;groupPadding&quot;:0,&quot;pointPadding&quot;:0.2}},&quot;series&quot;:[{&quot;data&quot;:[{{ $total_respondents_count }}]}]}" style="height:240px;"></div>
                </div>
            </div>
        </div>

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
          <div class="panel">
            <div class="panel-header">
              <div class="panel-title"><span class="panel-icon fa-pencil"></span> <span>Campaign by Type</span>
              </div>
            </div>
            <div class="panel-body">
              <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center"><span>Merchandising</span>
                  <div class="badge badge-primary badge-pill">14</div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><span>Mystery Shopper</span>
                  <div class="badge badge-success badge-pill">9</div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center"><span>Shopper Insighting</span>
                  <div class="badge badge-info badge-pill">11</div>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
@endsection

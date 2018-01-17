@extends('layouts.layout_admin_lte')

@section('content_header','Dashboard')

@section('content')
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            @php($notif_order=count($user->unreadNotifications->where('type','App\Notifications\NewOrder')))
            <h3>{{$notif_order>99?'99+':$notif_order}}</h3>
            <p>New Orders</p>
          </div>
          <div class="icon">
            <i class="ion ion-cash"></i>
          </div>
          <a href="{{url('/admin/transaction')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>53<sup style="font-size: 20px">%</sup></h3>

            <p>Bounce Rate</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="inner">
            {{-- <h3>{{count($member)>99?'99+':count($member)}}</h3> --}}
            @php($notif_user=count($user->unreadNotifications->where('type','App\Notifications\NewUserRegistration')))
            <h3>{{$notif_user>99?'99+':$notif_user}}</h3>
            <p>User Registrations</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h3>{{count($products)}}</h3>

            <p>Products</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-success">
            <div class="box-header with-border"><h4 class="box-title">Grafik pendapatan Indikraf</h4></div>
            <div class="box-body">
                <div id="chartdiv" style="width:100%; height:400px;"></div>
            </div>
        </div>
      </div>
    </div>
    <!-- /.row (main row) -->

  </section>

@endsection

@section('css')
<link rel="stylesheet" href="{{asset('amcharts/style.css')}}" type="text/css">
@endsection

@section('js')

  <script src="{{asset('amcharts/amcharts.js')}}" type="text/javascript"></script>
  <script src="{{asset('amcharts/serial.js')}}" type="text/javascript"></script>

  <script>
      var chart;

      AmCharts.ready(function () {
          // SERIAL CHART
          chart = new AmCharts.AmSerialChart();

          chart.dataProvider =
          [
            <?php
              foreach($data as $d)
              echo '
                {
                    "date": "'.$d->date.'",
                    "value": '.$d->value.'
                },
              ';
            ?>
          ];
          chart.dataDateFormat = "YYYY-MM-DD";
          chart.categoryField = "date";


          // AXES
          // category
          var categoryAxis = chart.categoryAxis;
          categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
          categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD
          categoryAxis.gridAlpha = 0.1;
          categoryAxis.minorGridAlpha = 0.1;
          categoryAxis.axisAlpha = 0;
          categoryAxis.minorGridEnabled = true;
          categoryAxis.inside = true;

          // value
          var valueAxis = new AmCharts.ValueAxis();
          valueAxis.tickLength = 0;
          valueAxis.axisAlpha = 0;
          valueAxis.showFirstLabel = false;
          valueAxis.showLastLabel = false;
          chart.addValueAxis(valueAxis);

          // GRAPH
          var graph = new AmCharts.AmGraph();
          graph.dashLength = 3;
          graph.lineColor = "#00CC00";
          graph.valueField = "value";
          graph.dashLength = 3;
          graph.bullet = "round";
          graph.balloonText = "[[category]]<br><b><span style='font-size:14px;'>value:[[value]]</span></b>";
          chart.addGraph(graph);

          // CURSOR
          var chartCursor = new AmCharts.ChartCursor();
          chartCursor.valueLineEnabled = true;
          chartCursor.valueLineBalloonEnabled = true;
          chart.addChartCursor(chartCursor);

          // SCROLLBAR
          var chartScrollbar = new AmCharts.ChartScrollbar();
          chart.addChartScrollbar(chartScrollbar);

          // HORIZONTAL GREEN RANGE
          var guide = new AmCharts.Guide();
          guide.value = 10;
          guide.toValue = 20;
          guide.fillColor = "#00CC00";
          guide.inside = true;
          guide.fillAlpha = 0.2;
          guide.lineAlpha = 0;
          valueAxis.addGuide(guide);

          // TREND LINES
          // first trend line
          @php
            $data_f=explode('-',$data[0]->date);
            $data_l=explode('-',$data[count($data)-1]->date);
          @endphp

          // WRITE
          chart.write("chartdiv");
      });
  </script>
@endsection

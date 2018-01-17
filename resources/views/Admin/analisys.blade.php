@extends('layouts.layout_admin_lte')

@section('content_header','Dashboard')

@section('content')
  <!-- Main content -->
  <section class="content">
    <!-- Main row -->
    <div class="row">
      <div class="col-md-12">
        <div class="box box-success">
            <div class="box-header with-border"><h4 class="box-title">Grafik pengunjung Indikraf</h4></div>
            <div class="box-body">
                <div id="chartdiv-visitor" style="width:100%; height:400px;"></div>
            </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-success">
            <div class="box-header with-border"><h4 class="box-title">Grafik pendaftar Indikraf</h4></div>
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

          // WRITE
          chart.write("chartdiv");
      });
  </script>

  <script>
      var chart;

      var chartData = [
        <?php
          foreach($visitor_data as $d)
          echo '
            {
                "date": "'.$d->date.'",
                "visitor": '.$d->value.'
            },
          ';
        ?>
      ];


      AmCharts.ready(function () {
          // SERIAL CHART
          chart = new AmCharts.AmSerialChart();
          chart.dataProvider = chartData;
          chart.categoryField = "date";
          chart.startDuration = 1;

          // AXES
          // category
          var categoryAxis = chart.categoryAxis;
          categoryAxis.labelRotation = 30;
          categoryAxis.gridPosition = "start";

          // value
          // in case you don't want to change default settings of value axis,
          // you don't need to create it, as one value axis is created automatically.

          // GRAPH
          var graph = new AmCharts.AmGraph();
          graph.valueField = "visitor";
          graph.balloonText = "[[category]]: <b>[[value]]</b>";
          graph.type = "column";
          graph.lineAlpha = 0;
          graph.fillAlphas = 0.8;
          chart.addGraph(graph);

          // CURSOR
          var chartCursor = new AmCharts.ChartCursor();
          chartCursor.cursorAlpha = 0;
          chartCursor.zoomable = false;
          chartCursor.categoryBalloonEnabled = false;
          chart.addChartCursor(chartCursor);

          chart.creditsPosition = "top-right";

          // SCROLLBAR
          var chartScrollbar = new AmCharts.ChartScrollbar();
          chart.addChartScrollbar(chartScrollbar);

          chart.write("chartdiv-visitor");
      });
  </script>
@endsection

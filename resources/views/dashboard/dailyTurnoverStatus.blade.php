@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
{{-- <style>
  #container2,#container3, #container4 ,#container5 {
   height: 60%;
   width: 550px;
 }
</style> --}}
<?php ini_set('max_execution_time', 18000);?>
<div role="tabpanel">
  <div>
    <h3 style="text-align: center; margin-top:0px;">Turnover Report</h3>
  </div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs nav-justified" role="tablist">
    <li role="presentation" class="active">
      <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
        Online Report
      </a>
    </li>
    <li role="presentation">
      <a href="#profile" aria-controls="profile" role="tab"  data-toggle="tab">
        Offline Report
      </a>
    </li>
    <li role="presentation">
      <a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">
        Total Status
      </a>
    </li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">

    <div role="tabpanel" class="tab-pane active" id="home">
      <div class="col-md-12 col-xs-12">
        @include('dashboard.dailyTurnoverReport')
      </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="profile">
      <div class="col-md-12 col-xs-12">
        @include('dashboard.dailyTurnoverOffline')
      </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="settings">
      <div class="col-md-12 col-xs-12">
        @include('dashboard.totalTurnoverReport')
      </div>
    </div>

  </div>
</div>

@php

foreach ($data['monthlyTurnover'] as $key=> $value) {
	$xaxis[] = $monthName = date('F', mktime(0, 0, 0, $value['month'], 10));
	$datas[] = round($value['amount'], 0);
  $datadrill[$key]['name'] = $monthName;
  $datadrill[$key]['y'] = round($value['amount'], 0);
  $datadrill[$key]['drilldown'] = $monthName;
 
}

foreach ($data['monthlyTurnoverOffline'] as $value) {
	$xaxis2[] = date('F', mktime(0, 0, 0, $value['month'], 10));
	$datas2[] = round($value['amount'], 0);
}

foreach ($data['monthlyTurnover'] as $value) {
	$month               = $value['month'];
	$datas4[0]['data'][] = round($value['amount'], 0);
	if (isset($data['monthlyTurnoverOffline'][$month])) {

		$value['amount'] += $data['monthlyTurnoverOffline'][$month]['amount'];
		$datas4[1]['data'][] = $data['monthlyTurnoverOffline'][$month]['amount'];
	}

	$xaxis3[] = date('F', mktime(0, 0, 0, $value['month'], 10));
	$datas3[] = round($value['amount'], 0);
}

foreach ($data['monthlyTurnover'] as $value) {
	$month = $value['month'];
	if (isset($data['monthlyTurnoverOffline'][$month])) {

		$value['amount'] += $data['monthlyTurnoverOffline'][$month]['amount'];
	}
	//$datas4[]=round($value['amount'],0);

}
$datas4[0]['name'] = 'Online';
$datas4[1]['name'] = 'Offline';
//$datas4[0]['data'] =  array(11312,11123,3111,2113,11123,31111);
//$datas4[1]['data'] =  array(11131,12113,3111,2311,21133,31111);

//print_r($datas4);
//exit;
@endphp
@endsection

@section('scripts')

<script type="text/javascript" src="{{   asset('js/highcharts/highcharts.js')}}"></script>
<script type="text/javascript" src="{{   asset('js/highcharts/modules/exporting.js')}}"></script>
<script type="text/javascript" src="{{   asset('js/highcharts/modules/export-csv.js')}}"></script>
<script type="text/javascript" src="{{   asset('js/highcharts/modules/drilldown.js')}}"></script>
<script type="text/javascript" src="{{   asset('js/highcharts/modules/highchart-function.js')}}"></script>

<script language="javascript" type="text/javascript">
  var xAxis = <?php echo json_encode($xaxis); ?>;
  var data = <?php echo json_encode($datas); ?>;
  var xAxis2 = <?php echo json_encode($xaxis2); ?>;
  var data2 = <?php echo json_encode($datas2); ?>;
  var xAxis3 = <?php echo json_encode($xaxis3); ?>;
  var data3 = <?php echo json_encode($datas3); ?>;
  var data4  = <?php echo json_encode($datas4); ?>;
  var data5  = <?php echo json_encode($datadrill); ?>;
//console.log (data4);
//create_bar('container2','Online TurnOver',xAxis, data,'Online Turnover');
create_bar('container3','Offline TurnOver',xAxis2, data2, 'Offline Turnover');
create_bar('container4','Total TurnOver',xAxis3, data3, 'Total Turnover');
create_group_bar('container5','(Online & Offline)',xAxis3, data4, 'Total Turnover');
create_drilldown_bar('container2','Online TurnOver','', data5, 'Online Turnover');

/*function create_bar(id,title,xaxis,data,seriesname){
  var options = {
    chart: {
      renderTo: id,
      type: 'column',

    },
    title: {
      text: title
    },
    xAxis: {

      categories:  xaxis

    },

    yAxis: {
      labels: {
        formatter: function () {
         return this.value / 10000000 + 'Cr';
       }
     },
     title: {

      text: 'Amounts'

    }

  },

  series: [{
    name: seriesname,
    data:  data
  }],
  credits: {
    enabled: false
  }
};
var chart = new Highcharts.Chart(options);
chart.reflow();
}*/



function setTarget(state) {

  var inputs = $('#'+state+ ' :input');
  var values = [];
  i=0;
  inputs.each(function() {
    values[i] = $(this).val() + '*'+ this.name;
    i++;
  });
    // console.log(JSON.stringify( values ));
    $.ajax({
      type: "get",
      url: '/setTarget',
      data: { 'formdata': JSON.stringify( values ) , 'state' : state},
      success: function(result) {
              console.log(result);
             console.log("Data Sent");
           }
         })
  };
function setTargetYearly(state){
  var inputs = $('#totaltxt').val();
  //alert(inputs);return false;
    $.ajax({
      type: "get",
      url: '/setTargetYear',
      data: { 'total': inputs , 'state' : state},
      success: function(result) {
              console.log(result);
             console.log("Data Sent");
           }
         })
}

</script>



@endsection

@push('script')

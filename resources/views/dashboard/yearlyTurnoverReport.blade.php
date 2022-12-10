@extends('layouts.app')

@section('page-css')
@endsection 


<style type="text/css">
  .select_div{
    background-color: #e4e4e4;
    padding: 20px 20px;
    border-radius: 10px;
    margin: 0px!important;
}
.select_div h3{
    margin-top: 0; 
    margin-bottom: 5px;
  }
h3{
    text-align: center; 
    color: #b07c83!important;
  }
table tr td{
    width:unset!important;
}
table{
  margin-top: 10px; 
}
#show_record_yearly{
     /* margin-top: 24px;*/
      background: #b07c83!important;
      border-color: #b07c83!important;
}

ul.yearly_report li a {
    font-family: Pluto;
    font-size: 20px;
    color: #000;
}
.nav-tabs.nav-justified>.active>a {
    color: #b07c83!important;
}
.error_msg{
  color:#b07c83;
  font-size: 18px;
}

.pie_graph,#container4,#container5, #container6{
    border: 3px solid #e4e4e4;
    box-shadow: 5px 5px 8px 5px #e4e4e4;
    margin-bottom:10px;
}
  .btn_export{
      background-color: #b07c83;
      color: #fff;
      margin-top: 30px;
    }

</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />


@section('content')
<?php 

$Nyear = '2019';

$onlineRevenue = $data['yearlyRevenueReport'];
$i= 1;

foreach ($onlineRevenue as $month => $monthlyRevenue) {

   foreach ($monthlyRevenue as $year => $Amount) {
     if(isset($monthlyRevenue[$year]) && isset($data['yearlyRevenueReportOffline'][$month][$year])){
      
      
       $totalRevenue[$month][$year]= $monthlyRevenue[$year] + $data['yearlyRevenueReportOffline'][$month][$year];

       $revenvuePie[$year][] = array(
        $month => $data['yearlyRevenueReportOffline'][$month][$year]
       );
     }else{
           if(isset($monthlyRevenue[$year]))
           $totalRevenue[$month][$year]= $monthlyRevenue[$year];
          else 
            $totalRevenue[$month][$year]= $data['yearlyRevenueReportOffline'][$month][$year];
         } 
    }
}

  
function percent_revenue( $base_value = '', $new_value = ''){
  $delta_val = ( ($new_value - $base_value) / $base_value ) * 100;


 /* if( $delta_val > 0 ){
    $delta_val_deficit = $delta_val .'<i class="fa fa-caret-up" style="font-size: 24px; color: green;"></i>';
  }if( $delta_val < 0){
        $delta_val_deficit = $delta_val .'<i class="fa fa-caret-down" style="font-size: 24px; color: red;"></i>';
    
  } */
  return $delta_val;
}

$year_inc = 1;

$cntarr = 2; $base_value = 0; $new_value = 0;


if( isset($data['start_year'] ) && isset($data['end_year'] )  ){

    $start_year = $data['start_year'] . '-04-01';

    $startYear = strtotime($start_year);
    $new_start_date = strtotime('+ 11 month', $startYear);
    $new_start_date = date('Y', $new_start_date);

    $end_year = $data['end_year'] . '-04-01';
    $endYear1 = strtotime($end_year);
    $new_end_date = strtotime('+ 11 month', $endYear1);
    $new_end_date = date('Y', $new_end_date);
    
    $sTotalOnline = round(array_sum(session('TotOnlineRevenue')[$data['start_year']]),2);
    $eTotalOnline = round(array_sum(session('TotOnlineRevenue')[$data['end_year']]),2);

    $sTotalOffline = round(array_sum(session('TotOfflineRevenue')[$data['start_year']]),2);
    $eTotalOffline = round(array_sum(session('TotOfflineRevenue')[$data['end_year']]),2);

    $sTotalCombine = round($sTotalOnline + $sTotalOffline,2);
    $eTotalCombine = round($eTotalOnline + $eTotalOffline,2);

     $st_year = date('Y', $startYear);
     $ed_year = date('Y', $endYear1); 
} 
?>

<div>


</div>
<form action="<?php echo env("APP_URL"); ?>/yearly_record" method="post" class="form_year">
   {{ csrf_field() }}
    <h3>YOY Turnover Report</h3>
  <div class="row select_div">
     
      <div class="col-md-2 col-xs-12">
       <label for="start">Select Year:  </label>

     </div>
      <div class="col-md-2 col-xs-12">
       <select class="form-control" id="start_year" name="start_year">
        <option value="">Select</option>
        <?php
          $firstYear = (int)date('Y');

          for($i=$firstYear;$i>=2017;$i--)
          {
              echo '<option value='.$i.'>'.$i.'</option>';
          }
          ?>
          
        </select>
      
      </div>
      <div class="col-md-2 col-xs-12">
        <label for="start">Compare With:</label></div>
         <div class="col-md-2 col-xs-12">
        <select class="form-control" id="stat" name="end_year">
        </select>

      </div>
      <div class="col-md-4 col-xs-12">
        <input type="submit" name="" class="btn btn-primary" id="show_record_yearly" value="Show"/>
      </div>
  </div>
</form>
@php 
if(isset($data['start_year'] ) or isset($data['end_year'] )  )
  {
@endphp
<hr>
<div role="tabpanel">
  <ul class="nav nav-tabs nav-justified yearly_report" role="tablist">
    <li role="presentation" class="active">
      <a href="#online" aria-controls="online" role="tab" data-toggle="tab">
        Online Report
      </a>
    </li>
    <li role="presentation">
      <a href="#offline" aria-controls="offline" role="tab"  data-toggle="tab">
        Offline Report
      </a>
    </li>
    <li role="presentation">
      <a href="#yearly" aria-controls="yearly" role="tab"  data-toggle="tab">
        Total Revenue Report
      </a>
    </li>
  </ul>
  
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="online">
    @include('dashboard.yearlyTurnoverReportOn')
  </div>

  <div role="tabpanel" class="tab-pane" id="offline">
    @include('dashboard.yearlyTurnoverOffline')
  </div>

  <div role="tabpanel" class="tab-pane" id="yearly">
    @include('dashboard.yearlyTurnoverReportTotal')
  </div>
</div>

</div>
@php
//print_r($data['yearlyRevenueReport']);echo '</br>';
}



foreach ($data['yearlyRevenueReport'] as $key => $value) {

  $xaxis4[] = date('F', mktime(0, 0, 0, $key, 10));
  $i=0;
  foreach ($value as $key1 => $value2){
  $datas4[$i]['data'][] = round($value2, 0);

   $i++;
  }

}

foreach ($data['yearlyRevenueReportOffline'] as $key => $value) {

  $xaxis5[] = date('F', mktime(0, 0, 0, $key, 10));
  $i=0;
  foreach ($value as $key1 => $value2){
    $datas5[$i]['data'][] = round($value2, 0);
   $i++;
  }
  
}


foreach ($totalRevenue as $key => $value) {

  $xaxis6[] = date('F', mktime(0, 0, 0, $key, 10));
  $i=0;
  foreach ($value as $key1 => $value2){
    $datas6[$i]['data'][] = round($value2, 0);
   $i++;
  }
  
}

$j = 0;

if (isset($data['start_year'])) {
  $datas4[0]['name'] = $data['start_year'];
  $datas5[0]['name'] = $data['start_year'];
  $datas6[0]['name'] = $data['start_year'];
}
if (isset($data['end_year'])) {
  $datas4[1]['name'] = $data['end_year'];
  $datas5[1]['name'] = $data['end_year'];
  $datas6[1]['name'] = $data['end_year'];
}

@endphp
@endsection
@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script type="text/javascript" src="{{   asset('js/highcharts/highcharts.js')}}"></script>
<script type="text/javascript" src="{{   asset('js/highcharts/modules/exporting.js')}}"></script>
<script type="text/javascript" src="{{   asset('js/highcharts/modules/export-csv.js')}}"></script>
<script type="text/javascript" src="{{   asset('js/highcharts/modules/drilldown.js')}}"></script>
<script type="text/javascript" src="{{   asset('js/highcharts/modules/highchart-function.js')}}"></script>


<script src="https://nightly.datatables.net/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js" type="text/javascript"></script>
<!--<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> -->


<script language="javascript" type="text/javascript">
  $(document).ready(function() {

  var xAxis4 = <?php echo json_encode($xaxis4); ?>;
  var data4 = <?php echo json_encode($datas4); ?>;
  var xAxis5 = <?php echo json_encode($xaxis5); ?>;
  var data5 = <?php echo json_encode($datas5); ?>;
  var xAxis6 = <?php echo json_encode($xaxis6); ?>;
  var data6 = <?php echo json_encode($datas6); ?>;

  

   $('form.form_year').submit(function(event){
      $('.error_msg').parent('div.errordiv').remove();
        var start_val = $('#start_year').val();
        var end_val = $('#stat').val();
      if( start_val > end_val){
         event.preventDefault();
       
        // $('.form_year').after('<div class="col-md-12 col-xs-12 errordiv" style="margin-bottom: 20px;"><span class="error_msg">Compare year should be greater than previous year.</div>');
      }
       });

    $('#start_year').on('change',function(){
      $('#stat').html('');
      var start_year = $(this).children("option:selected").val();
       $( "#stat" ).wrapInner( '<option value="2020">2020</option><option value="2019">2019</option><option value="2018">2018</option><option value="2017">2017</option>');

       $("#stat > option").each(function() {
        var st_val =$(this).val();
          if( start_year >= st_val ) {
            $("#stat option[value='"+st_val+"']").remove();
           }
        });
    });

    create_group_bar('container4','(Online)',xAxis4, data4, 'Total Turnover');
    create_group_bar('container5','(Offline)',xAxis5, data5, 'Total Turnover');
    create_group_bar('container6','(Total Revenue)',xAxis6, data6, 'Total Turnover');
   

  });
</script>


@endsection
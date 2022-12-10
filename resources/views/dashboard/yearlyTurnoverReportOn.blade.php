@php
$pieArray_next = [];$pieArray = [];

@endphp
<h3>Online Revenue Report</h3>
 @if( isset($data['start_year'] ) && isset($data['end_year'] )  )
 <div class="row">
  <div class="table-responsive col-md-12">
    <div id="container4" class="graph_cont"></div>
  </div>
</div> 


<div class="row">
  <div class="col-md-6">
    <div id="container_pie_on1" class="pie_graph"></div>
  </div>

  <div class="col-md-6">
    <div id="container_pie_on2" class="pie_graph"></div>
  </div>
</div>


  <div class="row">
  <div class="table-responsive col-md-12">
    <table class="table" id="datatable1" class="display table table-striped table-bordered dt-responsive" style="width:100%">
      <thead>
        <tr>
          <th>Month</th>
          <th>{{ $st_year }} - {{ $new_start_date }}</th>
          <th>{{ $ed_year }} - {{ $new_end_date }}</th>
          <th>Percent Deficit </th>
        </tr>
      </thead>
      <tbody>
           @php
      //  echo '<pre>';print_r($data['yearlyRevenueReportOffline'] );
       $month = 4;
        $month_offline =  count($data['yearlyRevenueReport']);

        @endphp

           @foreach ($data['yearlyRevenueReport'] as $key => $value)
            <tr>
            <td>
              @php
                   $previous_value=$new_value =0;
                 echo $monthName = date('F', mktime(0, 0, 0, $month, 10));
               $year_inc = 1;$k=0;
              @endphp
            </td>
            @foreach($data['yearlyRevenueReport'][$month] as $key1 => $value2)
              <td>
                @php
                $monthlyContributionPer = 0;
                $online_val = 0;

                $cnt = count($data['yearlyRevenueReport'][$month]);
                  $monthlyContributionPer = round( deficiet_percent( $sTotalOnline , $value2 ),2);
                if($cnt <2){
                   
                   $online_val = round( $value2, 2);
                    if($key1 == $st_year  ){   
                     $pieArray[] = array(
                    'name' => $monthName,
                    'y' => $monthlyContributionPer
                  );            
                   echo $online_val . " ( ".$monthlyContributionPer. "% )</td>";
                   echo "<td>--";
                  }
                  elseif($key1 == $new_start_date && ($month == 1 || $month ==2 || $month ==3) ){  
                   $pieArray[] = array(
                      'name' => $monthName,
                      'y' => $monthlyContributionPer
                    );             
                   echo $online_val . " ( ".$monthlyContributionPer. "% )</td>";
                   echo "<td>--";
                  }
                  else{

                    $pieArray_next[] = array(
                      'name' => $monthName,
                      'y' => $monthlyContributionPer
                    );
                    echo "-</td><td>";
                   echo $online_val . " ( ".$monthlyContributionPer. "% )";
                  }

                  if( $year_inc%2 != 0){
                   $previous_value =  $online_val;
                  }else{
                     $new_value =  $online_val;
                  }
                }else{
                   if($k<1){
                    $pieArray[] = array(
                      'name' => $monthName,
                      'y' => $monthlyContributionPer
                    );
                  }
                  else
                  {
                    $monthlyContributionPer = round( deficiet_percent( $eTotalOnline , $value2 ),2);
                    $pieArray_next[] = array(
                      'name' => $monthName,
                      'y' => $monthlyContributionPer
                    );
                  }
                $online_val = round( $value2, 2);
               
                echo $online_val . " ( ".$monthlyContributionPer. "% )";

                if( $year_inc%2 != 0){
                   $previous_value =  $online_val;
                }else{
                   $new_value =  $online_val;
                }
                } 
                 $k++;
                @endphp
              </td>
               @php   $year_inc++; 
               @endphp
            @endforeach
           {{--  @php  if($k < 2)
             echo "<td> -</td>";
              @endphp --}}
            <td>
               @php
               //echo 'previous--' . $previous_value . '...  new=>'. $new_value; 
                $percent_val = percent_revenue( $new_value , $previous_value );
                if( $percent_val > 0 ){
                 echo round($percent_val , 2) . '% <i class="fa fa-caret-up" style="font-size: 24px; color: green;"></i>';
                }else if( $percent_val < 0 ){
                   echo round($percent_val , 2) . '% <i class="fa fa-caret-down" style="font-size: 24px; color: red;"></i>';
                }
                @endphp
            </td>
          </tr>
          @php 
    
          $month++;
          if($month == 13)
             $month = 1;
           unset($previous_value,$new_value);
           @endphp

          @endforeach
          <tr>
            <td><b>Total</b> </td>
            <td> 
             <b>{{$sTotalOnline}}</b>
            </td>
            <td> 
              <b>{{$eTotalOnline}}</b>
            </td>
            <td>
              <b>
               @php
                $percent_val = percent_revenue( $sTotalOnline , $eTotalOnline );
                if( $percent_val > 0 ){
                  echo round($percent_val , 2) . '% <i class="fa fa-caret-up" style="font-size: 24px; color: green;"></i>';
                }else if( $percent_val < 0 ){
                   echo round($percent_val , 2) . '% <i class="fa fa-caret-down" style="font-size: 24px; color: red;"></i>';
                }
                @endphp
              </b>
            </td>
          </tr>
       </tbody>
    </table>
  </div>
</div>
@endif

@php  
$piestring = json_encode($pieArray);
$piestringNext = json_encode($pieArray_next);
@endphp
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

<script type="text/javascript">
   //ajax call

  $(document).ready(function(){

    piePrevOff = <?php echo $piestring; ?>;
    piePrevOffNext = <?php echo $piestringNext; ?>;

    st_year = <?php echo $st_year; ?>;
    new_start_date = <?php echo $new_start_date; ?>;

    var strt_yr = st_year + ' - ' + new_start_date;
    ed_year = <?php echo $ed_year; ?>;
    new_end_date = <?php echo $new_end_date; ?>;
    var end_yr = ed_year + ' - ' + new_end_date;

    var subtitle_start = <?php echo $sTotalOnline; ?>;
    var subtitle_end = <?php echo $eTotalOnline;?>;

    create_pie_chart('container_pie_on1', 'Monthly Contribution in '+ strt_yr + '<br><p style="font-size:14px;">Total Revenue ( ' + subtitle_start  + ' Cr )</p>' , piePrevOff,'Monthly Contribution');
    create_pie_chart('container_pie_on2', 'Monthly Contribution in '+ end_yr + '<br><p style="font-size:14px;">Total Revenue ( ' + subtitle_end  + ' Cr )</p>' , piePrevOffNext,'Monthly Contribution');

 
    
});
</script>

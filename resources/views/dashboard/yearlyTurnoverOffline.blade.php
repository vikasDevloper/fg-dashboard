@php
$pieArray_next = [];$pieArray = [];
@endphp
<h3>Offline Revenue Report</h3>
 @if( isset($data['start_year'] ) && isset($data['end_year'] )  )
 <div class="row">
  <div class="table-responsive col-md-12">
    <div id="container5" class="graph_cont"></div>
  </div>
</div> 
<div class="row">
  <div class="col-md-6">
    <div id="container_pie3" class="pie_graph"></div>
  </div>

  <div class="col-md-6">
    <div id="container_pie1" class="pie_graph"></div>
  </div>
</div>

  <div class="row">
  <div class="table-responsive col-md-12">
    <table class="table" id="datatable" class="display table table-striped table-bordered dt-responsive" style="width:100%">
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
        @endphp
          @foreach ($data['yearlyRevenueReportOffline'] as $key => $value)
             <tr>
            <td>
              @php
               echo $monthName = date('F', mktime(0, 0, 0, $key, 10));
               $year_inc = 1;$k=0;
              @endphp
            </td>
            @foreach($value as $key1 => $value2)
              <td>
                @php
                if($k<1){
                $monthlyContributionPer = round(100 + percent_revenue( $sTotalOffline , $value2 ),2);

                $pieArray[] = array(
                  'name' => $monthName,
                  'y' => $monthlyContributionPer
                );
              }
                else
                { 
                $monthlyContributionPer = round(100 + percent_revenue( $eTotalOffline , $value2 ),2);
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

                 $k++;
                @endphp
              </td>
               @php   $year_inc++; @endphp
            @endforeach
            @php  if($k < 2)
             echo "<td> -</td>";
              @endphp
            <td>
               @php
                $percent_val = percent_revenue( $previous_value , $new_value );
                if( $percent_val > 0 ){
                  echo round($percent_val , 2) . '% <i class="fa fa-caret-up" style="font-size: 24px; color: green;"></i>';
                }else if( $percent_val < 0 ){
                   echo round($percent_val , 2) . '% <i class="fa fa-caret-down" style="font-size: 24px; color: red;"></i>';
                }
                @endphp
            </td>
          </tr>
          @endforeach
          <tr>
           <td><b>Total</b> </td>
            <td> 
             <b>{{$sTotalCombine}}</b>
            </td>
            <td> 
              <b>{{$eTotalCombine}}</b>
            </td>
            <td>
              <b>
               @php
                $percent_val = percent_revenue( $sTotalOffline , $eTotalOffline );
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
@else

@endif

@php  
$piestring = json_encode($pieArray);
$piestringNext = json_encode($pieArray_next);
@endphp

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>




<script type="text/javascript">

  $(document).ready(function() {


    piePrevOff = <?php echo $piestring; ?>;
    piePrevOffNext = <?php echo $piestringNext; ?>;
    piePrevOffNext = <?php echo $piestringNext; ?>;

    st_year = <?php echo $st_year; ?>;
    new_start_date = <?php echo $new_start_date; ?>;

    var strt_yr = st_year + ' - ' + new_start_date;
    ed_year = <?php echo $ed_year; ?>;
    new_end_date = <?php  echo $new_end_date; ?>;
    var end_yr = ed_year + ' - ' + new_end_date;

    create_pie_chart('container_pie3', 'Monthly Contribution in '+ strt_yr , piePrevOff,'Monthly Contribution');
    create_pie_chart('container_pie1', 'Monthly Contribution in '+ end_yr, piePrevOffNext,'Monthly Contribution');



    $('#datatable,#datatable1,#datatable2').DataTable({
        "dom": 'Bfrtip',
          "aaSorting": [],
        "bPaginate": false,
        "pageLength": 25,
        "lengthChange": true,
         "lengthMenu": [[10, 20, 30, -1], [10, 20, 30, "All"]],
       buttons: {
            buttons: [
                { extend: 'csv', className: 'btn btn_export' },
                { extend: 'excel', className: 'btn btn_export' },
                { extend: 'pdf', className: 'btn btn_export' }
            ]
        },
    });
    

  });
  </script>
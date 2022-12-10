

<div class="row">
<br>
 <!-- <div class="table-responsive col-md-6">

    <h3>Turover Report</h3>
    @if(count($data['dailyTurnover']['unDeliveredOrder']['total']) > 0)

      <table class="table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Total Revenue </th>
          </tr>
        </thead>
        <tbody>
         
        <tr><td><b>{{$data['dailyTurnover']['startDate']}}</b></td><td><b>{{$data['dailyTurnover']['unDeliveredOrder']['total'] + $data['dailyTurnoverOffline']['amount']}}</b></td></tr>
        </tbody>
      </table>
    @endif

  </div>
-->
<div class="table-responsive col-md-12">
   <div style="display: ruby;">
    <h3>Total Revenue</h3>
     <button type="button" class="btn btn-primary btn-xs" style="float:right; margin-top: 25px;"  data-toggle="modal" data-target="#myModal3">
      Set Yearly Target
     </button>
   </div>
  <div class="modal" id="myModal3">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Set Total Target</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
         <form  action="/daily-turnover-status" id='total'>    

        <!-- Modal body -->
          <div class="modal-body">
             <table class="table">
              <thead>
                <tr>
                  <th>Year</th>
                  <th>Total Target</th>
                  
                </tr>
                </thead>
              <tbody> 
                <?php $sum = 0; ?>
                  @if(isset($data['getTuroverTarget']['total'])) 
                       <tr><td>{{ date('Y') }}</td>
                       <th> <input type="text" id="totaltxt" name={{  date('Y')  }} value= {{ $data['getTuroverTarget']['total']['NA'] }} ></input> </td></tr>
                   @else 
                    <tr>
                      <td>{{ date('Y') }}</td>
                      <td> <input type="text" id="totaltxt" name={{ date('Y') }} ></input> </td>
                    </tr>
                 @endif 
              </tbody>
            </table>
          </div>
        </form>
        <!-- Modal footer -->
        <div class="modal-footer">
           @if(isset($data['getTuroverTarget']['total']['NA'])) 
           <button type="button" class="btn btn-success" data-dismiss="modal" onclick="setTargetYearly('total')">Save</button>
           @endif
           <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>


    @if(count($data['monthlyTurnover']) > 0)

      <table class="table">
        <thead>
          <tr><th>Months</th>
          <th>Total Revenue</th>
          <th>Total Quantity</th>
          <th>Target Revenue</th>
          <th>%Actual Total</th>
          <th>Deficit</th>
          </tr>
        </thead>
        <tbody>
          <?php $sum = 0; $target_sum=0;$totQty = $totalQty= 0;?>
           @foreach ($data['monthlyTurnover'] as $value)
             <?php 
             $month=$value['month'];
             if(!isset($data['monthlyqty'][$month]))
               $data['monthlyqty'][$month] = 0;
                if(isset($data['monthlyTurnoverOffline'][$month])){

                  $value['amount'] += $data['monthlyTurnoverOffline'][$month]['amount'];
                  $sum += $value['amount'];
                  $value['qty'] = $data['monthlyTurnoverOffline'][$month]['qty'] + $data['monthlyqty'][$month];
                	$totQty += $value['qty'];

                }else
                {
                  $sum  += $value['amount'];

                  $value['qty'] = $totQty  += $data['monthlyqty'][$month];

                }
                //echo $sum;
              ?>
              <tr>
                <td>{{ $monthName = date('F', mktime(0, 0, 0, $value['month'], 10)) }}</td>
                <td> &#x20b9; {{ number_format(round($value['amount'], 2), 2) }} </td>
                <td>  {{  $value['qty']  }} </td>
                <td> {{ $target = (isset($data['getTuroverTarget']['online']) && isset($data['getTuroverTarget']['offline'])  ? $data['getTuroverTarget']['online'][$monthName] + $data['getTuroverTarget']['offline'][$monthName] : 0) }}</td>
                <td> {{$actual= ($target==0 ? 0 : round(($value['amount']/$target)*100,2)) }} %</td>
                 <td> {{$actualVal = ($actual<100) ? round((100 - $actual), 2) : round(($actual - 100), 2) }} %  
                  @if($actual<100)
                  <i class='fa fa-caret-down' style='font-size:24px;color:red'></i>
                @else
                  <i class='fa fa-caret-up' style='font-size:24px;color:green'></i>
                @endif
                </td>
              </tr>
           @php 
           $target_sum += $target; 
            
           @endphp
           @endforeach
       
         
            <tr>
              <td><b>Total>></b></td><td><b> &#x20b9; {{ round($sum ,2) }}</b></td>
              <td><b>{{ $totQty}} </b></td> 
              <td><b>{{ $target_sum}} </b></td>
              <td><b> {{ $totalactual = ($target_sum ==0 ? 0 :round(($sum/$target_sum)*100,2)) }}  %</b></td>
                  <td><b>{{  ($totalactual<100)? 100 - $totalactual : $totalactual - 100 }} %
            @if($totalactual<100)
                <i class='fa fa-caret-down' style='font-size:24px;color:red'></i>
              @else
                <i class='fa fa-caret-up' style='font-size:24px;color:green'></i>
              @endif
          </b></td>
            </tr>
        </tbody>
      </table>
      <div class="row">
         <div class="table-responsive col-md-12">
          @if(count($data['dailyTurnover']['unDeliveredOrder']['total']) > 0)
            <table class="table">
              <thead>
                <tr><th>Year</th>
                <th>Total Revenue</th>
                <th>Target Revenue</th>
                <th>%Actual Total</th>
                <th>Deficit</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><b> {{date('Y')}}</b></td>
                  <td><b>{{ round($sum ,2) }}</td>
                  <td><b> &#x20b9; {{$totalTarget = (isset($data['getTuroverTarget']['total']['NA']) ? $data['getTuroverTarget']['total']['NA'] : 0)}} </b></td>
                  <td><b>{{ $totper = ($totalTarget ==0 ? 0 :round(($sum/$totalTarget)*100,2))}}</b></td>
                  <td><b>{{  ($totper<100)? 100 - $totper : $totper - 100 }} %
                  @if($totper<100)
                      <i class='fa fa-caret-down' style='font-size:24px;color:red'></i>
                  @else
                      <i class='fa fa-caret-up' style='font-size:24px;color:green'></i>
                   @endif
          </b></td>
                </tr>
              </tbody>
            </table>
          @endif
         </div>
      </div>
     @endif
  </div>
   <div class="table-responsive col-md-6">
    <div id="container4" style="width: 550px"></div>
  </div>
  <div class="table-responsive col-md-6">
    <div id="container5" style="width: 550px"></div>
  </div>
      
</div>
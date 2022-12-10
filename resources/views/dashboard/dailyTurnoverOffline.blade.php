<div class="row">
  <br>
 <!-- <div class="table-responsive col-md-6">

     @if(count($data['dailyTurnoverOffline']['amount']) >= 0)
     @if($data['dailyTurnoverOffline']['amount']=0)
    <?php $data['dailyTurnoverOffline']['amount'] = 0;?>
    @endif
      <table class="table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Total Revenue </th>
          </tr>
        </thead>
        <tbody>

        <tr><td><b>{{$data['dailyTurnover']['startDate']}}</b></td><td><b>{{$data['dailyTurnoverOffline']['amount'] }}</b></td></tr>
        </tbody>
      </table>
    @endif

  </div>
-->
  <div class="table-responsive col-md-12">
     <div>
      <h3>Monthly Revenue
        <button type="button" class="btn btn-primary btn-xs" style="float:right; margin-top: 25px;"  data-toggle="modal" data-target="#myModal2">
        Set Target
       </button>
     </h3>
   </div>
  <!-- The Modal -->
  <div class="modal" id="myModal2">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title">Set Monthly Target</h4>
           <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
         <form  action="/daily-turnover-status" id='offline'>
         <!-- Modal body -->
          <div class="modal-body">
               <table class="table">
                <thead>
                  <tr>
                    <th>Months</th>
                    <th>Total Revenue</th>
                  </tr>
                  </thead>
                <tbody>
                    <?php $sum = 0;?>
                    @if(isset($data['getTuroverTarget']['offline']))
                     @foreach ($data['getTuroverTarget']['offline'] as $month=> $value)

                         <tr><td>{{ $month }}</td>
                         <td> <input type="text" name={{ $month }} value= {{ $value }} disabled></input> </td></tr>

                     @endforeach
                   @else
                    @foreach ($data['allmonths']  as $value)
                      <tr><td>{{ $value }}</td>
                      <td> <input type="text" name={{ $value }} ></input> </td></tr>
                    @endforeach
                   @endif
                
                </tbody>
              </table>
          </div>
        </form>
        <!-- Modal footer -->
{{--         <div class="modal-footer">
          @if(isset($data['getTuroverTarget']['offline']))
           <button type="button" class="btn btn-success" data-dismiss="modal" onclick="setTarget('offline')">Save</button>
           @endif
           <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div> --}}

      </div>
    </div>
  </div>


    @if(count($data['monthlyTurnoverOffline']) > 0)

   <table class="table">
        <thead>
          <tr>
            <th>Months</th>
            <th>Total Revenue</th>
            <th>Total Quantity</th>
            <th>Target Revenue</th>
            <th>%Actual Offline</th>
            <th>Deficit</th>
          </tr>
         </thead>
        <tbody>
          <?php $sum = $qtyoffline = 0;
          $targetSum = 0;
          ?>
          @foreach ($data['monthlyTurnoverOffline'] as $value)
            @php
              $monthName = date('F', mktime(0, 0, 0, $value['month'], 10));
              $target = (isset($data['getTuroverTarget']['offline']) ? $data['getTuroverTarget']['offline'][$monthName] : 0);
              $targetSum += $target;
            @endphp
            <tr>
              <td>{{ $monthName }}</td>
              <td>&#x20b9; {{ number_format(round($value['amount'], 2), 2) }} </td>
              <td> {{ $value['qty'] }} </td>
              <td>&#x20b9; {{ number_format($target) }} </td>
              <td> {{$actual= ($target==0 ? 0 : round(($value['amount']/$target)*100,2) )}} %</td>
              <td> {{$actualVal = ($actual<100)? 100 - $actual : $actual - 100 }} %  
                @if($actual<100)
                <i class='fa fa-caret-down' style='font-size:24px;color:red'></i>
              @else
                <i class='fa fa-caret-up' style='font-size:24px;color:green'></i>
              @endif
              </td>
            </tr>
            <?php
            $sum += $value['amount'];
            $qtyoffline += $value['qty'];
            ?>
          @endforeach
 
          <tr><td><b>Total>></b></td><td><b>&#x20b9;
           {{ round($sum, 2) }}</b></td><td><b> 
           {{ $qtyoffline }}</b></td><td><b>
           {{ $targetSum }} </b></td>
            <td><b> {{ $totalactual = ($targetSum ==0 ? 0 :round(($sum/$targetSum)*100,2)) }}  %</b></td>
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
     @endif
  </div>
  <div class="row">
    <div class="table-responsive col-md-6">
      <div id="container3" style="width: 100%" ></div>
    </div>
  </div>
</div>
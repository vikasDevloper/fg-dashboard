@extends('layouts.app')

@section('content')   
            
<div class="row"> 
<!--   <h2 class="pull-left" style="width: 65%">Dashboard</h2>   -->
  <form action="{{ route('cx-dashboard') }}" id="filter" name="filter" method="get">
    <button class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 10%">Apply</button> 
    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 30%">
        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        <span></span> <b class="caret"></b>
    </div>
    <input type="hidden" name="start-date" id="start-date"> 
    <input type="hidden" name="end-date" id="end-date"> 

  </form>
</div>

<div class="row table-responsive">          
  
  <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>COD</th>
          <th>Processing</th>
          <th>On Hold</th>
          <th>Ready To Ship</th>
          <th>Shipped</th>
          <th>In Transit</th>
          <th>Delivered</th>
          <th>Pending Payments</th>
          <th>Canceled</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Total Number of Orders : </td>
          <td><?php echo isset($data['unDeliveredOrder']['pending']['orders']) ? $data['unDeliveredOrder']['pending']['orders'] : 0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['processing']['orders']) ? $data['unDeliveredOrder']['processing']['orders'] : 0;?></td>
           <td><?php echo isset($data['unDeliveredOrder']['holded']['orders']) ? $data['unDeliveredOrder']['holded']['orders'] : 0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['readytoship']['orders']) ? $data['unDeliveredOrder']['readytoship']['orders'] : 0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['shipped']['orders']) ? $data['unDeliveredOrder']['shipped']['orders'] : 0;?></td>
           <td><?php echo isset($data['unDeliveredOrder']['it']['orders']) ? $data['unDeliveredOrder']['it']['orders'] : 0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['delivered']['orders']) ? $data['unDeliveredOrder']['delivered']['orders'] : 0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['pending_payment']['orders']) ? $data['unDeliveredOrder']['pending_payment']['orders'] : 0;?></td>
          <td><?php echo isset($data['unDeliveredOrder']['canceled']['orders']) ? $data['unDeliveredOrder']['canceled']['orders'] : 0;?></td>
        </tr>
         <tr>
          <td>Total Revenue of Orders : </td>
          <td><?php echo isset($data['unDeliveredOrder']['pending']['amount']) ? number_format($data['unDeliveredOrder']['pending']['amount'], 2) : 0;?></td>
          
          <td><?php echo isset($data['unDeliveredOrder']['processing']['amount']) ? number_format($data['unDeliveredOrder']['processing']['amount'], 2) : 0;?></td>

           <td><?php echo isset($data['unDeliveredOrder']['holded']['amount']) ? number_format($data['unDeliveredOrder']['holded']['amount'], 2) : 0;?></td>

           <td><?php echo isset($data['unDeliveredOrder']['readytoship']['amount']) ? number_format($data['unDeliveredOrder']['readytoship']['amount'], 2) : 0;?></td>
          
          <td><?php echo isset($data['unDeliveredOrder']['shipped']['amount']) ? number_format($data['unDeliveredOrder']['shipped']['amount'], 2) : 0;?></td>

           <td><?php echo isset($data['unDeliveredOrder']['it']['amount']) ? number_format($data['unDeliveredOrder']['it']['amount'], 2) : 0;?></td>

          <td><?php echo isset($data['unDeliveredOrder']['delivered']['amount']) ? number_format($data['unDeliveredOrder']['delivered']['amount'], 2) : 0;?></td>

          <td><?php echo isset($data['unDeliveredOrder']['pending_payment']['amount']) ? number_format($data['unDeliveredOrder']['pending_payment']['amount'], 2) : 0;?></td>
           <td><?php echo isset($data['unDeliveredOrder']['canceled']['amount']) ? number_format($data['unDeliveredOrder']['canceled']['amount'], 2) : 0;?></td>
        </tr>
         
      </tbody>
  </table>
  
  <div class="row">
    <div class="table-responsive col-md-6"> 
      <table class="table">
          <thead>
            <tr>
              
              <th>#</th>
              <th>Tickets</th>
              <th>Open</th>
              <th>Resolved</th>
              <th>Closed</th>

            </tr>
            <tr>
              <td> </td>
              <td><?php echo $data['ticketsStatus']['tickets']; ?></td>
              <td><?php echo $data['ticketsStatus']['open']; ?></td>
              <td><?php echo $data['ticketsStatus']['resolved'];?></td>
              <td><?php echo $data['ticketsStatus']['closed']; ?></td>
            </tr>
          </thead>
      </table>

      <table class="table">
        <caption> Order cancellation reasons </caption>  
            
            <thead>
              <tr>
                <th>Reasons</th>
                <th>Orders</th>
                <th>Amount</th>
              </tr>
            </thead>

            <tbody>

              <?php foreach ($data['cancelReasons'] as $value) {  if(empty($value['orders'])) continue; ?>

                <tr>
                  <td><?php echo $value['reason'];?></td>
                  <td><?php echo $value['orders'];
                            echo ' (' . number_format(($value['orders']/$data['cancelReasons']['total']) * 100, 2) . '%)'; ?>
                  </td>
                  <td><?php echo $value['amount'];
                            echo ' (' . number_format(($value['amount']/$data['cancelReasons']['totalAmount']) * 100, 2) . '%)'; ?> </td>
                </tr> 

              <?php } ?>  

            </tbody>
      </table> 

      <table class="table">
        <caption> Call logs </caption>  
            
            <thead>
              <tr>
                <th>Total Calls</th>
                <th>Incoming Calls</th>
                <th>Missed Calls</th>
              </tr>
            </thead>

            <tbody>

              <?php //foreach ($data['callLogs'] as $value) {   ?>

                <tr>
                  <td><?php echo $data['callLogs']['total'];?></td>
                  <td><?php echo $data['callLogs']['incoming']; ?></td>
                  <td><?php echo $data['callLogs']['missed']; ?> </td>
                </tr> 

              <?php //} ?>  

            </tbody>
      </table> 
    
    </div>

    <div class="table-responsive col-md-6"> 
      <table class="table">
        <caption> Reasons for Refunded Orders </caption>  
            
          <thead>
            <tr>
              <th>Reasons</th>
              <th>Orders</th>
              <th>Amount</th>
            </tr>
          </thead>

          <tbody>

         <?php foreach ($data['bankRefunds'] as $value) {  if(empty($value['orders'])) continue; ?>

              <tr>
                <td><?php echo $value['reason'];?></td>
                <td><?php echo $value['orders'];
                          echo ' (' . number_format(($value['orders']/$data['bankRefunds']['total']) * 100, 2) . '%)'; ?>
                </td>
                <td><?php echo $value['amount'];
                          echo ' (' . number_format(($value['amount']/$data['bankRefunds']['totalAmount']) * 100, 2) . '%)'; ?> </td>
              </tr> 

            <?php } ?>  

          </tbody>

      </table> 

       <table class="table">
        <caption> Reasons for exchanged Orders </caption>  
            
          <thead>
            <tr>
              <th>Reasons</th>
              <th>Orders</th>

            </tr>
          </thead>

          <tbody>

         <?php foreach ($data['exchanges'] as $value) {  if(empty($value['orders'])) continue; ?>

              <tr>
                <td><?php echo $value['reason'];?></td>
                <td><?php echo $value['orders'];
                          echo ' (' . number_format(($value['orders']/$data['exchanges']['total']) * 100, 2) . '%)'; ?>
                </td>
       
              </tr> 

            <?php } ?>  

          </tbody>

      </table> 
    
    </div>
  </div> 

</div>      

@endsection


@section('scripts')
<script type="text/javascript">
  $(function() {

      var start = moment('<?php echo $data['startDate'] ?>');
      var end = moment('<?php echo $data['endDate'] ?>');
      
      // end1 = end;

      function cb(start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          $('#start-date').val(start.format('YYYY-MM-DD'));
          $('#end-date').val(end.format('YYYY-MM-DD'));
          // if(end1.format('YYYY-M-D') != end.format('YYYY-M-D')){
          //    end1 = end;
          //    $('#filter').submit();
          // }
      }

      $('#reportrange').daterangepicker({
          startDate: start,
          endDate: end,
          ranges: {
             'Today': [moment(), moment()],
             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
             'Last 30 Days': [moment().subtract(29, 'days'), moment()],
             'This Month': [moment().startOf('month'), moment().endOf('month')],
             'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          }
      }, cb);

      cb(start, end);
      
  });
</script>

@endsection

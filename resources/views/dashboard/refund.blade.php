@extends('layouts.app')

@section('content')
<div class="row" style="margin-bottom: 10px;">
	<form action="{{ route('refund-report') }}" id="filter" name="filter" method="get">
    <button class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">Apply</button>
    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; margin-right: 15px;">
        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        <span></span> <b class="caret"></b>
    </div>
    <input type="hidden" name="start-date" id="start-date">
    <input type="hidden" name="end-date" id="end-date">

  	</form>
</div>
<?php
//echo '<pre>';
//print_r($data['RTOReport']);
?>
<div class="row" style="background-color: #f8f8f8;">
  	<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd; height: 440px; overflow: scroll;">
		<h4>RTO Report</h4>
  		@if(!empty($data['RTOReport']))
	  		<table class="table">
	  			<caption>  </caption>  
		      	<thead>
			        <tr>
			          <th>Date</th>
			          <th>Old Customer</th>
			          <th>New Customer</th>
			          <th>RTO</th>
			        </tr>
		        </thead>
		        <tbody>
		        	@foreach( $data['RTOReport'] as $value)
		        		@php
		        		$timestamp     = strtotime($value['Order_updated_at']);                  		
		        		$date          = date("d-M-Y", $timestamp);
              			$totalRto      = isset($value['totalRTO']) ? $value['totalRTO'] : '0';
              			$oldCustomer   = isset($value['OLD']) ? $value['OLD'] : '0';
              			$newCustomer   = isset($value['NEW']) ? $value['NEW'] : '0';
              			@endphp
				        <tr>
				          <td>{{ $date }}</td>
				          <td>{{ $oldCustomer }}</td>
				          <td>{{ $newCustomer }}</td>
				          <td>{{ $totalRto }}</td>
				        </tr>

				    @endforeach  
				      
		        </tbody>
	  		</table>
	  	@endif	
	</div>

	<h4>Refund Report</h4>

  	<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd; height: 400px; overflow: scroll;">
		
  		{{-- @if(!empty($data['not_visible'])) --}}
	  		<table class="table">
	  			<caption>  </caption>  
		      	<thead>
			        <tr>
			          <th>Date</th>
			          <th>Old Customer</th>
			          <th>New Customer</th>
			          <th>Refund</th>
			        </tr>
		        </thead>
		        <tbody>
		        	
			       	<tr>
			          <td> </td>
			          <td> </td>
			          <td>  </td>
			          <td> </td>
			        </tr>
				   
		        </tbody>
	  		</table>
	  	{{-- @endif	 --}}
  	</div>	


</div>
<div class="row" style="background-color: #f8f8f8;">
  	<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd; height: 440px; overflow: scroll;">
		<h4>Exchange Report</h4>
  		{{-- @if(!empty($data['disabled'])) --}}
	  		<table class="table">
	  			<caption>  </caption>  
		      	<thead>
			        <tr>
			          <th>Date</th>
			          <th>Old Customer</th>
			          <th>New Customer</th>
			          <th>RTO</th>
			        </tr>
		        </thead>
		        <tbody>
		        	
				        <tr>
				          <td> </td>
				          <td> </td>
				          <td>  </td>
				          <td> </td>
				        </tr>

				    {{-- @endforeach   --}}
				      
		        </tbody>
	  		</table>
	  	{{-- @endif	 --}}
	</div>

	<h4>Refund</h4>

  	<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd; height: 400px; overflow: scroll;">
		
  		{{-- @if(!empty($data['not_visible'])) --}}
	  		<table class="table">
	  			<caption>  </caption>  
		      	<thead>
			        <tr>
			          <th>Date</th>
			          <th>Old Customer</th>
			          <th>New Customer</th>
			          <th>Refund</th>
			        </tr>
		        </thead>
		        <tbody>
		        	
			       	<tr>
			          <td> </td>
			          <td> </td>
			          <td>  </td>
			          <td> </td>
			        </tr>
				   
		        </tbody>
	  		</table>
	  	{{-- @endif	 --}}
  	</div>	


</div>

@endsection

@section('scripts')
<script type="text/javascript">
   $(function() {

      var start = moment('{{$data['startDate']}}');
      var end = moment('{{$data['endDate']}}');

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





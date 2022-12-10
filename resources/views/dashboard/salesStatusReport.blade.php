@extends('layouts.app')

@section('content')
@php
// echo '<pre>';
// print_r($data['skuWiseSalethrough']);

// echo '</pre>';
//die;
$error = '';
@endphp
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet"> -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css" rel="stylesheet">  -->
<div class="row">
	<h3 class="text-center">Sale Through Report</h3><br><br>
	<div class="col-md-12">
			<div class="col-md-12 text-center">
				@php
				if(!empty($data['error_mesg']))
				 	$error = $data['error_mesg'];				
				@endphp
				<span style="color: #FF0000">@php echo $error; @endphp</span>
				<form class="form-inline" method="GET" action="/sales-status">
					
					<div class="form-group col-md-5">
						<select class="form-control selectpicker" name="select-name" id="select-name" data-live-search="true">
							<option value="">Select Product Name</option>
							@foreach($data['productNameList'] as $productName)
								<option data-tokens="{{trim($productName)}}">{{trim($productName)}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-1"><h6>OR</h6> &nbsp;</div>
					<div class="form-group col-md-5">
						<select class="form-control selectpicker" name="select-style" id="select-style" data-live-search="true">
							<option value="">Select Style Number</option>
							@foreach($data['styleNumberList'] as $styleNumber)
								<option data-tokens="{{trim($styleNumber)}}">{{trim($styleNumber)}}</option>
							@endforeach
						</select>
					</div>
					
					<button type="submit" class="btn btn-default btn-success">Submit</button>
				</form>
			</div>
			
			
			
	</div>
</div>
<br><br>
<div class="row">
  
    <div class="col-md-12">    
    <table class="table table-bordered">
          	<thead>
	            <tr>
	         		<th> Style No. / Product Name</th>
					<th> Online Upload Quanitity </th>
					<th> Online Sale Quanitity / Sale through </th>
					<th> Offline Sale Quantity </th>
				</tr>
          	</thead>
          	@php

          	$uploadTotalQuantity 	= isset($data['upload_total_quantity']) ? $data['upload_total_quantity'] : 0;
          	$totalSaleQuantity   	= isset($data['totalQuantity']) ? $data['totalQuantity'] : '';
	        if(!empty($uploadTotalQuantity) &&  !empty($totalSaleQuantity)) 
	          	$saleThrough		= '('.number_format($totalSaleQuantity/$uploadTotalQuantity*100, 2) .'%)';
	      		else
	      		$saleThrough		=	'';
	        @endphp

	        <tbody>
	          	<tr>
					<td>{{ isset($data['styleNumber']) ? $data['styleNumber'] : 0 }}</td> 
					<td>{{$uploadTotalQuantity}}</td>
					<td>{{$totalSaleQuantity}} {{$saleThrough}}</td> 
					<td>{{ isset($data['offline_total_sale']) ? $data['offline_total_sale'] : 0 }}</td> 
	          	</tr>
	         
	        </tbody>
	</table>
	<br>
<h3 class="text-left">SKU Wise Sale Through Report</h3><br><br>
   <table class="table table-bordered">
          	<thead>
	            <tr>
	         		<th> SKU / Item Name </th>
	         		<th> Size </th>
					<th> Online Upload Quanitity </th>
					<th> Online Sale Quanitity / Sale through </th>
					<th> Offline Sale Quantity </th>
				</tr>
          	</thead>
          	@if(!empty($data['skuWiseSalethrough']))
	        <tbody>
	        	@foreach($data['skuWiseSalethrough'] AS $key => $value)

	        	@php
	        		if($key == 'Not Found') 
	        		continue;
		        	$uploadQuantity 	= isset($value['total']) ? $value['total'] : 0;
		        	$saleQuantity   	= isset($value['SaleQuantity']) ? $value['SaleQuantity'] : '';
		        	$itemName   		= isset($value['item_name']) ? $value['item_name'] : '';
		        	$itemSize   		= isset($value['item_size']) ? $value['item_size'] : '';
		        	if(!empty($uploadQuantity) &&  !empty($saleQuantity)) 
			          	$saleThrough		= '('.number_format($saleQuantity/$uploadQuantity*100, 2) .'%)';
			      		else
			      		$saleThrough		=	0;
		      		$offlineSale 		= isset($value['OfflineQuantity']) ? $value['OfflineQuantity'] : 0;
	        	@endphp
	          	<tr>
					<td style="width:150px;">{{ $key .' / '.$itemName}}</td> 
					<td style="width:100px;">{{$itemSize}}</td> 
					<td>{{$uploadQuantity}}</td>
					<td>{{$saleQuantity}} {{$saleThrough}}</td> 
					<td>{{ $offlineSale }}</td> 
	          	</tr>
	          	@endforeach
	         
	        </tbody>
	        @endif
	</table>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
	$(function() {
	  $('.selectpicker').selectpicker();
	});
</script>
@endsection


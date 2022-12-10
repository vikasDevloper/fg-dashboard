@extends('layouts.app')

@section('content')
<div class="row" style="margin-bottom: 10px;">
<!--   <h2 class="pull-left" style="width: 65%">Dashboard</h2>  -->
  @php
  // echo '<pre>';
  // print_r($data['product_without_related_product']);
  // echo '</pre>';
 // die();
  @endphp
</div>

<div class="row" style="background-color: #f8f8f8;">
  	<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd; height: 400px; overflow: scroll;">
		<h4>Disabled Sku's with Inventory</h4>
  		@if(!empty($data['disabled']))
	  		<table class="table">
	  			<caption>  </caption>  
		      	<thead>
			        <tr>
			          <th>#</th>
			          <th>Product Id</th>
			          <th>Sku</th>
			          <th>Name</th>
			          <th>Qty</th>
			          <th>MRP</th>
			          <th>Total Price</th>
			        </tr>
		        </thead>
		        <tbody>
		        	@php ($i = 1)
		        	@php ($grandtotal = 0)
		        	@foreach($data['disabled'] AS $value)
		        	@php 
		              $grandtotal += $value['total_pricing'] ;
		            @endphp
				        <tr>
				          <td> {{ $i++ }}</td>
				          <td> {{ $value['product_id'] }} </td>
				          <td> {{ $value['sku'] }} </td>
				          <td> {{ $value['name'] }} </td>
				          <td> {{ $value['qty'] }} </td>
				          <td> {{ $value['mrp'] }} </td>
				          <td> {{ number_format($value['total_pricing'] , 2, '.', '') }} </td>
				        </tr>

				    @endforeach  
				    <tr><td> </td><td> </td><td> </td><td> </td><td> </td>
				    	<th style="width: 150px;">Grand Total</th>
				    	<td colspan="7" style="color: green;">{{ number_format($grandtotal, 2, '.', '') }}</td></tr>   
		        </tbody>
	  		</table>
	  	@endif	
  	</div>

<h4>Products That have inventory but are not visible</h4>

  	<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd; height: 400px; overflow: scroll;">
		
  		@if(!empty($data['not_visible']))
	  		<table class="table">
	  			<caption>  </caption>  
		      	<thead>
			        <tr>
			          <th>#</th>
			          <th>Product Id</th>
			          <th>Sku</th>
			          <th>Name</th>
			          <th>Qty</th>
			          <th>MRP</th>
                	  <th>Total Price</th>
			        </tr>
		        </thead>
		        <tbody>
		        	@php ($i = 1)
		        	@php ($grandtotal = 0)
		        	@foreach($data['not_visible'] AS $value)
		        	@php 

		        	$totalprice = $value['mrp']  *  $value['qty'];
		        	$grandtotal += $totalprice;

		        	@endphp

				        <tr>
				          <td> {{ $i++ }} </td>
				          <td> {{ $value['product_id'] }} </td>
				          <td> {{ $value['sku'] }} </td>
				          <td> {{ $value['name'] }} </td>
				          <td> {{ $value['qty'] }} </td>
				          <td> {{ $value['mrp'] }} </td>
				          <td> {{ number_format($totalprice, 2, '.', '')  }}</td>
				         
				        </tr>
				        
				    @endforeach   
				    <tr><td> </td><td> </td><td> </td><td> </td><td> </td>
				    	<th style="width: 150px;">Grand Total</th>
				    	<td colspan="7" style="color: green;">{{ number_format($grandtotal, 2, '.', '') }}</td></tr> 
		        </tbody>
	  		</table>
	  	@endif	
  	</div>	


</div>
<div class="row">
	<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd; height: 400px; overflow: scroll;">
		<h4>Styles without category</h4>
  		@if(!empty($data['without_category']))
	  		<table class="table">
	  			<caption>  </caption>  
		      	<thead>
			        <tr>
			          <th>#</th>
			          <th>Product Id</th>
			          <th>Sku</th>
			          <th>Name</th>
			          <th>Qty</th>
			          <th>MRP</th>
                	  <th>Total Price</th>

			        </tr>
		        </thead>
		        <tbody>
		        	@php ($i = 1)
		        	@php ($grandtotal = 0)
		        	@foreach($data['without_category'] AS $value)
		        	@php 

		        	$totalprice = $value['mrp']  *  $value['qty'];
		        	$grandtotal += $totalprice;

		        	@endphp
				        <tr>
				          <td>{{ $i++ }} </td>
				          <td> {{ $value['product_id'] }} </td>
				          <td> {{ $value['sku'] }} </td>
				          <td> {{ $value['name'] }} </td>
				          <td> {{ $value['qty'] }} </td>
				          <td> {{ number_format($totalprice , 2, '.', '') }}</td>
				        </tr>
				       
				    @endforeach    
				    <tr><td> </td><td> </td><td> </td><td> </td><td> </td>
				    	<th style="width: 150px;">Grand Total</th>
				    	<td colspan="7" style="color: green;">{{ $grandtotal }}</td></tr> 
		        </tbody>
	  		</table>
	  	@endif	
  	</div>

  	<span><h4>Product Without Filters</h4></span>

<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd; height: 400px; overflow: scroll;">
    
      
      @if(!empty($data['filter']))
        <table class="table">
          <caption>  </caption>  
            <thead>
              <tr>
                <th>#</th>
                <th>Product Id</th>
                <th>Name</th>
                <th>Filter Not Assigned</th>
                <th>Qty</th>
                <th>MRP</th>
                <th>Total Price</th>
               
              </tr>
            </thead>
            <tbody>
              @php ($i = 1)
              @php ($grandtotal = 0)
              @foreach($data['filter'] AS $value)
              @php 
              $grandtotal += $value['total_pricing'] ;
              @endphp
                <tr>
                  <td>{{ $i++ }} </td>
                  <td> {{ $value['entity_id'] }} </td>
                  <td> {{ $value['name'] }} </td>
                  <td> {{ $value['not_assigned'] }} </td>
                  <td> {{ $value['qty'] }} </td>
                  <td> {{ $value['mrp'] }} </td>
                  <td> {{ number_format($value['total_pricing'] , 2, '.', '')}} </td>
                  
                </tr>
            @endforeach    
            <tr><td> </td><td> </td><td> </td><td> </td><td> </td>
				    	<th style="width: 150px;">Grand Total</th>
				    	<td colspan="7" style="color: green;">{{ number_format($grandtotal, 2, '.', '') }}</td></tr> 
            </tbody>
        </table>
      @endif  
    </div>  
</div>
<div class="row" style="background-color: #f8f8f8;">
	
  	<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd; height: 400px; overflow: scroll;">
		<h4>Products That Have Not Cross Selling Products</h4>
  		@if(!empty($data['without_cross_sellproduct']))
	  		<table class="table">
	  			<caption>  </caption>  
		      	<thead>
			        <tr>
			          <th>#</th>
			          <th>Product Id</th>
			          <th>Sku</th>
			          <th>Name</th>
			         {{--  <th>Qty</th> --}}
			          <th>MRP</th>
			         {{--  <th>Total Price</th> --}}
			        </tr>
		        </thead>
		        <tbody>
		        	@php ($i = 1)
					@php ($grandtotal = 0)
		        	@foreach($data['without_cross_sellproduct'] AS $value)
		        	@php 
		              $grandtotal += $value['price'] ;
		            @endphp
				        <tr>
				          <td> {{ $i++ }} </td>
				          <td> {{ $value['product_id'] }} </td>
				          <td> {{ $value['sku'] }} </td>
				          <td> <a target="_blank" href="https://www.faridagupta.com/{{$value['link']}}">{{ $value['product_name'] }} </a></td>
				        {{--   <td> {{ $value['qty'] }} </td> --}}
				          <td> {{ $value['price'] }} </td>
				         {{--  <td> {{ $value['total_pricing'] }} </td> --}}
				        </tr>

				    @endforeach  
				    <tr><td> </td><td> </td><td> </td>{{-- <td> </td><td> </td> --}}
				    	<th style="width: 150px;">Grand Total</th>
				    	<td colspan="7" style="color: green;">{{ number_format($grandtotal, 2, '.', '') }}</td></tr>   
		        </tbody>
	  		</table>
	  	@endif	
  	</div>

<h4>Products That Have Not Related Products</h4>

  	<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd; height: 400px; overflow: scroll;">
		
  		@if(!empty($data['product_without_related_product']))
	  		<table class="table">
	  			<caption>  </caption>  
		      	<thead>
			        <tr>
			          <th>#</th>
			          <th>Product Id</th>
			          <th>Sku</th>
			          <th>Name</th>
			          {{-- <th>Qty</th> --}}
			          <th>MRP</th>
                	  {{-- <th>Total Price</th> --}}
			        </tr>
		        </thead>
		       <tbody>
		        	@php ($i = 1)
					@php ($grandtotal = 0)
		        	@foreach($data['product_without_related_product'] AS $value)
		        	@php 
		              $grandtotal += $value['price'] ;
		            @endphp
				        <tr>
				          <td> {{ $i++ }} </td>
				          <td> {{ $value['product_id'] }} </td>
				          <td> {{ $value['sku'] }} </td>
				          <td> <a target="_blank" href="https://www.faridagupta.com/{{$value['link']}}">{{ $value['product_name'] }} </a></td>
				        {{--   <td> {{ $value['qty'] }} </td> --}}
				          <td> {{ $value['price'] }} </td>
				         {{--  <td> {{ $value['total_pricing'] }} </td> --}}
				        </tr>

				    @endforeach  
				    <tr><td> </td><td> </td><td> </td>{{-- <td> </td><td> </td> --}}
				    	<th style="width: 150px;">Grand Total</th>
				    	<td colspan="7" style="color: green;">{{ number_format($grandtotal, 2, '.', '') }}</td></tr>   
		        </tbody>
	  		</table>
	  	@endif	
  	</div>	


</div>

@endsection





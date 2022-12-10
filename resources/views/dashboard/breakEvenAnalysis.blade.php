@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">

<!-- <link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"> -->
	<style type="text/css">
		.profit{
			background-color: green;
			color: #fff;
		}
		.loss{
			background-color: red;
			color: #fff;
		}
		.btn_export{
			background-color: rgba(176,124,131,0.85);
			color: #fff;
			margin-top: 30px;
		}

		table th{
			font-size: 12px;
			font-weight: 400;
		}

		table td, p{
			font-size: 14px;
			font-weight: 300;
		}
		table#dataTable tbody tr.odd {
		    background-color: #f9f9f9;
		}
		table#dataTable tbody tr.even:hover{
		    background-color: whitesmoke;
		}
		.cstm_table {
		    margin-top: 30px;
		    box-shadow: 4px -1px 9px 5px #e4e4e4;
		    border-radius: 7px;
		}

	</style>

@endsection

@section('content')


<div class="row">
	<h4> Breakeven Analysis Dashboard</h4>

	<div class="col-md-12 cstm_table"  style="/*background-color: #f8f8f8; padding: 10px;*/">

		<div id= "type">

			<label class="radio-inline"><input type="radio" name="optradio" value ="product_wise" checked>Product wise</label>
			<label class="radio-inline"><input type="radio" name="optradio" value="month_block_wise">3 Months block wise</label>
			<label class="radio-inline"><input type="radio" name="optradio" value="collection_wise">Collection wise</label>
			<label class="radio-inline"><input type="radio" name="optradio" value="category_wise">Category wise</label>

		</div>

		<hr>

		<div id="product_wise" class="desc">
			<div>
				<label for="styleno">Select Style</label>
				<input list="styleno" id="viewopt1" name="Style" />

				<datalist id="styleno">
					@php
					foreach ($data['style_no'] as $key => $value) {
						$style = $value['product_style_number'];
						echo "<option value=$style>";
					}
					@endphp

				</datalist>
			</div>
		</div>

 		<div id="month_block_wise" class="desc" style="display: none;">
 			<b> Date </b><input type="date" id="viewopt2">
 			{{-- <input type="text" id="datepicker" /> --}}
 		</div>

 		<div id="collection_wise" class="desc" style="display: none;">
 			<div>
 				<b>Select Collection</b>
 				<select id = 'viewopt3'>
 				@foreach ($data['collections'] as $key => $value)
					<option value=<?php echo $value['collectionId']?>><?php echo $value['collectionName']?>
@endforeach
				</select>
 			</div>
 		</div>

 		<div id="category_wise" class="desc" style="display: none;">
 			<div>
 				<b>Select Category</b>
 				<select id = 'viewopt4'>
 					@if(isset($data['categories'] ))
	 					@foreach ($data['categories'] as $key => $value)
						<option value=<?php echo $value['cat_id']?>><?php echo $value['cat_name']?>
						@endforeach
					@endif
				</select>
 			</div>
 		</div>

 		<hr>

 		<button type="button" class="btn btn_export" onclick="viewproduct('offline')" style="padding: 5px 30px;">Show</button>
 	</div>

 	<div class="table-responsive col-md-12 cstm_table">

		<div id="section">
			<h5>Export Data:</h5>
			<table class="table " id="dataTable" class="display table table-striped table-bordered dt-responsive" style="width:100%">
				<thead>
					<tr>

						<th>Style No.</th>
						<th>Product Name</th>
						<th>Product Created</th>
						<th>Manf Cost</th>
						<th>Overheads</th>
						<th>Total Cost</th>
						<th>Planned Qty</th>
						<th>Total Manf Cost</th>
						<th>Product Mrp</th>
						<th>Amount FG Makes</th>
						<th>Breakeven Qty</th>
						<th>Online Sale Qty</th>
						<th>Offline Sale Qty</th>
						<th>Total Sale Qty</th>
						<th>Breakeven Sale Through</th>
						<th>Actual Sale Through</th>
						<th>Profit/Loss</th>

					</tr>
				</thead>
				<tbody id='tbody'>

				</tbody>
			</table>
			<span id="result"></span>
		</div>
	</div>

</div>

@endsection

@section('scripts')


<script src="https://nightly.datatables.net/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js" type="text/javascript"></script>



<script language="javascript" type="text/javascript">
	var radio_val;
	$(document).ready(function() {
		$("input[name$='optradio']").click(function() {
			radio_val = $(this).val();

			$("div.desc").hide();
			$("#" + radio_val).show();
		});

		$('#dataTable').DataTable( {
			dom: 'Bfrtip',
			 buttons: {
		        buttons: [
		            { extend: 'csv', className: 'btn btn_export' },
		            { extend: 'excel', className: 'btn btn_export' },
		            { extend: 'pdf', className: 'btn btn_export' }
		        ]
		    }
		} );

	});

	limit = -99;

	function viewproduct(){


		$("#tbody").empty();
		$("#result").html("Please wait ...");
		var type={};    var item = {}
		var inputs ;
		var radio_val = $("input:radio:checked").val();
		if(radio_val == "product_wise"){
			inputs = $("#viewopt1").val();
			item ["input_option"] = radio_val;
			item ["styleno"] = inputs;
		}
		else if(radio_val == "month_block_wise"){
			inputs = $("#viewopt2").val();
			item ["input_option"] = radio_val;
			item ["from_date"] = inputs.split('-').join('/');
		}
		else if(radio_val == "collection_wise"){
			inputs = $("#viewopt3").val();
			item ["input_option"] = radio_val;
			item ["collection_id"] = inputs;
		}
		else if(radio_val == "category_wise"){
			inputs = $("#viewopt4").val();
			item ["input_option"] = radio_val;
			item ["category_id"] = inputs;
			limit = limit +100 ;
			item ["limit"] = limit;
		}



		   $('#dataTable').DataTable().destroy();

           $('#dataTable').DataTable( {
          	"destroy" : true,
          	"dom": 'Bfrtip',
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
	         "ajax": {
	         	"type": "get",
			  	"url": '/product-details',
			  	"data": item,
	            "dataSrc": ""

	         },
        "columns": [
            { "data": "styleno" },
            { "data": "product_name" },
            { "data": "product_created" },
            { "data": "manf_cost" },
            { "data": "overheads" },
            { "data": "total_cost" },
            { "data": "planned_qty" },
            { "data": "total_manf_cost" },
            { "data": "product_mrp" },
            { "data": "amount_makes" },
            { "data": "breakeven_qty" },
            { "data": "online_Sale_qty" },
            { "data": "offline_Sale_qty" },
            { "data": "total_Sale_qty" },
            { "data": "breakeven_sale_through" },
            { "data": "actual_sale_through" },
            { "data": "profit_or_loss",

                "render": function ( data, type, row, meta ) {
                var dt_val = row['profit_or_loss'];
				if ( dt_val > 0 ) {
		              return "<span class='btn btn-success'>"+dt_val+"</span>";
			    } else {
			        return "<span class='btn btn-danger'>"+dt_val+"</span>";
			    }

			    }
			}
        ]
    });
          $("#result").html("");

	}


</script>

@endsection

@extends('layouts.app')


@section('page-css')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<style type="text/css">
	
	.btn_export{
			background-color: rgba(176,124,131,0.85);
			color: #fff;
			margin-top: 30px;
		}

		table th{
			font-size: 14px;
			font-weight: 700;
		}
		table tr td{
			font-size: 14px;
			font-weight: 500;
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
		.cstm_table .dataTables_wrapper .dataTables_length {
		    margin-top: 15px;
		    margin-bottom: 15px;
		}

</style>
@endsection

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	  <div class="col-xs-12 col-md-4 pull-right">
    <form action="/catalog_by_date" id="filter" name="filter" method="post">
    	{{ csrf_field() }}
    
      <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; margin-right: 15px;">
          <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
          <span></span> <b class="caret"></b>
      </div>
        <button class="pull-right" style="background: rgba(176,124,131,0.85); color:#fff;cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;position: relative;right: 83%;margin-top: 20px;">Apply</button>
      <input type="hidden" name="start-date" id="start-date">
      <input type="hidden" name="end-date" id="end-date">

    </form>
  </div>
	<div class="row">
		<h2 class="">Warehouse Picking Report</h2>
	</div>
<br><br>
<ul class="nav nav-pills">
    <li class="active "><a data-toggle="pill" href="#home">Style Wise</a></li>
    <li><a data-toggle="pill" href="#menu1">Order Wise</a></li>

  </ul>
  
  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
     

      <div class="row">
    <div class="col-md-12 cstm_table">    
    <table class="table table-bordered" id="dataTable">
          	<thead>
	            <tr>
	         		<th> Style No. / Product Name</th>
	         		<th> Product Image</th>
					<th> SKU </th>
					<th> SIZE</th>
					<th> QUANTITY</th>
					
				</tr>
          	</thead>
	        <tbody>
	        	@if( !empty($data['arr']) )
		        	@foreach ($data['arr'] as $key => $value) 
		        	@php
		        	//unset($sku);
			        	$sku = $value['sku'];
						$name = $value['name'];
						$nm = $name[0];
						$nm_arr = explode('-', $nm);
						$nm_val =  $nm_arr[0];
						$img = $value['img'][0];
						
						$size = $value['size'];
						$qty_ordered = $value['qty_ordered'];
						unset($sizeQty);
						unset($sku_res);
		                $eav_attributes = array('XXS','XS','S','M','L','XL','XXL','3XL','Free Size');

		         	@endphp
					<?php
 					foreach ($eav_attributes as $index => $sz) {
						if(in_array($sz, $size))
                           {
                           	$matched_key = array_search($sz, $size);
                           	$qtyCount = $qty_ordered[$matched_key];
                        	$sizeQty[$sz] = $qtyCount;

                        	$skuarr = $sku[$matched_key];
                        	$sku_res[$sz] = $skuarr;

                           }
					}
                     ?>
		        	<tr>
					<td>{{ $key }} 
						<p>
						<a href="<?php echo config("app.site_url").config("app.seperator").str_replace(' ', '-', strtolower($nm_val)).'.html';?>" target="_blank">({{ $nm_val }})	
						</a>	
						</p>
					</td>
					<td align="center">
						<img src="{{ config("app.img_url") . $img }}" height="100" width="100" />
					</td>
					<td>
						@foreach( $sku_res as $sk)
						<p>
							{{ $sk  }}
						</p>
						@endforeach
					</td>
					<td>
 
						@foreach( $sizeQty as $size => $qty)
						<p>
						 {{$size}}
						</p>
					@endforeach
					</td>
					<td>
					@foreach( $sizeQty as $cnt)
						<p>
						{{ round($cnt) }}
						</p>
					@endforeach
					</td>
		         </tr>
		         @endforeach
	         @endif
	    </tbody>
	</table>
	<br>
  
    </div>
  </div>
      
    </div>
    <div id="menu1" class="tab-pane fade">
        @include('dashboard.warehousePickingByOrderID')
    </div>
  
  </div>
@php
$newarr = array();
//dd($data['arr']);
@endphp

  @php

 if( !isset($data['endDate'] ) && !isset($data['startDate']) ){
 	
 		 $data['endDate']   = date('Y-m-d H:i:s');
		$timestamp = strtotime('today midnight');
	  	 $st_date = date("Y-m-d H:i:s",$timestamp);

 }else{

 	 $st_date = $data['startDate'];
 	 $data['endDate'] = $data['endDate'];
 }

  @endphp
@endsection

@section('scripts')


<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script src="https://nightly.datatables.net/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js" type="text/javascript"></script>

<script type="text/javascript">
 var start = moment('<?php echo $st_date;?>');
      var end = moment('<?php echo $data['endDate'];?>');
      function cb(start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY hh:mm:ss A') + ' - ' + end.format('MMMM D, YYYY hh:mm:ss A'));
          $('#start-date').val(start.format('YYYY-MM-DD hh:mm:ss A'));
          $('#end-date').val(end.format('YYYY-MM-DD hh:mm:ss A'));
      }

      $('#reportrange').daterangepicker({
      	 timePicker: true,
      	    timePicker24Hour: true,
    		pick12HourFormat: false,
      	    locale: {
		      format: 'M/DD hh:mm:ss A'
		    },
          startDate: start,
          endDate: end,

          ranges: {
             'Today': [moment().startOf('day'), moment().endOf('day')],
             'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
             'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
             'Last 30 Days': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
             'This Month': [moment().startOf('month').startOf('day'), moment().endOf('month')],
             'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          }
      }, cb);

      cb(start, end);

   var buttonCommon = {
        exportOptions: {
            format: {
                body: function(data,  row, column) {
               
                    data = data.replace(/<br\s*\/?>/ig, "\r\n");
                    //data = data.replace(/[,]+/, "<br />");

                    data =  column >= 7 && column <= 9 ? data.replace( /[$,.]/g, '' ) : data.replace(/(&nbsp;|<([^>]+)>)/ig, "");


                            return data;
                },
                header: function(data, column, row) {
                            data = data.replace(/<br\s*\/?>/ig, "\r\n");//should be with wrapped text
                            return data;
                }
              }
            }
    };

	$('#dataTable').DataTable( {
		 	"fnDrawCallback": function( oSettings ) {
		       $(".table").imagePreviewer({
			    scroll: false
			  });
		    },
			dom: 'Blfrtip',
			stateSave: true,
            "pageLength": 25,
   			"lengthMenu": [[10, 100, 250, 500, -1], [10, 100, 250, 500, "All"]],
   			buttons: [
                $.extend(true, {}, buttonCommon, {
                  extend: 'excel',
                  className: 'btn btn_export',
                  exportOptions: {

                         rows: ':visible',    

                      columns: ':visible'
                 },

                  customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                  //All cell wrapped text
                        $('row c', sheet).each( function () {
                                    $(this).attr( 's', '55' );
                        });       
          
              	}
            }),
            { extend: 'pdf', className: 'btn btn_export' }
          ]
		} );

	

  // });
</script>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
        crossorigin="anonymous">
</script>
<script type="text/javascript" src="{{   asset('js/jquery-imagepreviewer.min.js')}}"></script>

<script type="text/javascript">
	window.onload = function() {
  $(".table").imagePreviewer({
    scroll: false
  });
};
</script>
@endsection 


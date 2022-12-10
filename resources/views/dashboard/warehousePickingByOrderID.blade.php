    <div class="row">
    <div class="col-md-12 cstm_table">    
    <table class="table table-bordered" id="dataTable1">
          	<thead>
	            <tr>
	            	<th>
			          <button type="button" id="selectAll" class="main">
			          <span class="sub"></span> Select </button></th>
	         		<th> OrderID</th>
					<th> SKU </th>
					<th> Style </th>
					<th> Product Name </th>
					<th> Image </th>
			 
					<th> SIZE</th>
					<th> QUANTITY</th>
					
				</tr>
          	</thead>
	        <tbody>
	        	@if( !empty($data['arr2']) )
		        	@foreach ($data['arr2'] as $key => $value) 
		        	@php
		        	 
		        	//unset($sku);
		        		$cntarr = count($data['arr2']);
			        	$sku = $value['sku'];
			        	$style = $value['style'];
						$name = $value['name'];
						$orderID = $value['order_id'];

						$nm = $name[0];
						$nm_arr = explode('-', $nm);
						$nm_val =  $nm_arr[0];
						$img = $value['img'];
						
						$size = $value['size'];
						$qty_ordered = $value['qty_ordered'];
						unset($sizeQty);
						unset($sku_res);
		                $eav_attributes = array('XXS','XS','S','M','L','XL','XXL','3XL','Free Size');

		         	@endphp
					<?php
 					
                     ?>
		        	<tr>
		       
		        		<td>
		        			<input type="checkbox" class="check1"/>
		        		</td>

					<td> 
					 	<p >
						<a href="<?php echo 'https://fgadmin.faridagupta.com/index.php/fgadmin/sales_order/view/order_id/'.$orderID;?>" target="_blank">{{ $key }}	
						</a>	
						</p>
					</td>
					
					<td>
						@foreach( $sku as $sk)
						<p style="height: 75px;">
							{{ $sk  }}
						</p>
						@endforeach
					</td>

					<td>
						@foreach( $style as $styleno)
						<p style="height: 75px;">
							{{ $styleno  }}
						</p>
						@endforeach
					</td>

					<td>
						@foreach( $name as $pname)
						@php
						$nm_arr = explode('-', $pname);
						$nm_val =  $nm_arr[0];
						@endphp
						<p style="height: 75px;">
						<a href="<?php echo config("app.site_url").config("app.seperator").str_replace(' ', '-', strtolower($nm_val)).'.html';?>" target="_blank">{{ $nm_val }}	
						</a>	
						</p>
						@endforeach
					</td>
                    
                    <td align="center">
                    	@foreach($img as $prodimage)
                    	 <p>
						<img src="{{ config("app.img_url") . $prodimage }}" height="75" width="75" />
						 </p>
						@endforeach
					</td>

					<td>
 
						@foreach( $size as $size => $qty)
						<p style="height: 75px;">
						 {{$qty}}
						</p>
					@endforeach
					</td>
					<td>
					@foreach( $qty_ordered as $cnt)
						<p style="height: 75px;">
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


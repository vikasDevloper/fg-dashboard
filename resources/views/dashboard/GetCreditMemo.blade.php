@extends('layouts.app')
@section('content')

<div class="row" style="background-color: #f8f8f8;">
 <div role="tabpanel">
  <div>
    <h3 style="text-align: center; margin-top:0px;">Download Credit Memo </h3>
  </div>

  <!-- Tab panes -->
  <div class="tab-content">

     <div class="col-md-12 text-center">
			<form action="\download-credit-memo" method="Get" class="form-inline">
	           <input type="hidden" name="_token" value="{{ csrf_token() }}">

	           <div class="form-group">
				Order No: <input type="text" id="order_id" name="order" class="form-control"/>
				</div>
				<div class="form-group"> &nbsp;<h6>OR</h6> &nbsp;</div>
				 <div class="form-group">
		            From: <input type="date" name="sdate" class="form-control" onchange="$('#order_id').val('');" /> 
		            To: <input type="date" name="edate" class="form-control"/> 
	             </div>
	             <div class="form-group">
	              <input type="Submit" name="submit" value="Download" class="btn btn-default btn-success">
	             </div>
			</form>
    </div>
  </div> 
 </div>
</div>


@endsection
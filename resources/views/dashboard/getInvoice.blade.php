@extends('layouts.app')
@section('content')

<div class="row" style="background-color: #f8f8f8;">
 
  <div role="tabpanel">
  <div>
    <h3 style="text-align: center; margin-top:0px;">Download PDF Invoice </h3>
  </div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs nav-justified" role="tablist">
    <li role="presentation" class="active">
      <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
        PDF Invoice
      </a>
    </li>
    <li role="presentation">
      <a href="#profile" aria-controls="profile" role="tab"  data-toggle="tab">
        PDF Invoice Summary
      </a>
    </li>
    <li role="presentation">
      <a href="#tax" aria-controls="tax" role="tab"  data-toggle="tab">
        PDF Advance Invoice Summary
      </a>
    </li>
   </ul>

  <!-- Tab panes -->
  <div class="tab-content">

    <div role="tabpanel" class="tab-pane active" id="home">
     <div class="col-md-12 text-center">
			<form action="\download-invoice" method="Get" class="form-inline">
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

    <div role="tabpanel" class="tab-pane" id="profile">
      <div class="col-md-12 col-xs-12">
       <div class="col-md-12 text-center">
  			<form action="\download-invoice" method="Get" class="form-inline">
  	           <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <br>
        			 <div class="form-group">
  		            From: <input type="date" name="csvsdate" class="form-control" onchange="$('#order_id').val('');" /> 
  		            To: <input type="date" name="csvedate" class="form-control"/> 
  	             </div>
  	             <div class="form-group">
  	              <input type="Submit" name="summary" value="Download Summary" class="btn btn-default btn-success">
  	             </div>
  			</form>
       </div>
      </div>  	
    </div>

    <div role="tabpanel" class="tab-pane" id="tax">
      <div class="col-md-12 col-xs-12">
       <div class="col-md-12 text-center">
        <form action="\download-invoice" method="Get" class="form-inline">
               <input type="hidden" name="_token" value="{{ csrf_token() }}">
               <br>
               <div class="form-group">
                  From: <input type="date" name="taxsdate" class="form-control" onchange="$('#order_id').val('');" /> 
                  To: <input type="date" name="taxedate" class="form-control"/> 
                 </div>
                 <div class="form-group">
                  <input type="Submit" name="summary" value="Download Advance Summary" class="btn btn-default btn-success">
                 </div>
        </form>
       </div>
      </div>    
    </div>


</div> 
</div>

@endsection
@extends('layouts.app')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet">
<div class="row">
	<div class="col-md-12">
		<h3 class="text-center">City Cleaning Operation</h3>
		@if(session('message'))
		<div class='alert alert-success'>
			{{ session('message') }}
		</div>
		@elseif(session('error'))
		<div class='alert alert-error'>
			{{ session('error') }}
		</div>
		@endif
		<div class="row bg-info" style="padding: 10px;">
			<div class="col-md-12 text-center">
				<form class="form-inline" method="POST" action="/clean-city-operation">
					{{ csrf_field() }}
					<div class="form-group">
						<select class="form-control selectpicker" name="select-city" id="select-city" data-live-search="true" required="required">
							<option>Select City</option>
							@foreach($data['cities'] as $city)
								<!--not working in special character and numbers -->
								@if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $city['city']) || preg_match('/\./', $city['city']) || preg_match('/\’/', $city['city']) || preg_match('/\//', $city['city']) || is_numeric($city['city']))
									@continue;
								@endif
								<option data-tokens="{{trim($city['city'])}}">{{trim($city['city'])}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<input type="text" name="replace-city" class="form-control" placeholder="Replace with" id="replace-city" required="required">
					</div>
					<button type="submit" class="btn btn-default btn-success">Submit</button>
				</form>
			</div>
		</div>
		<div class="row">
			<div style="width: 1200px; margin: auto; padding-top: 10px;">
				<h4 class="text-center"><u>OUR CITY LIST</u></h4>
				<p class="text-center" style="color: #999; font-weight: 200;"><em>eg: city_name - region, postcode (city_count)</em></p>
				@foreach($data['cities'] as $city)
					@if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $city['city']) || preg_match('/\./', $city['city']) || preg_match('/\’/', $city['city']) || preg_match('/\//', $city['city']) || is_numeric($city['city']))
						@continue;
					@endif
					<div class="col-md-4">
			   			<p><b>{{$city['city']}}</b> - {{$city['region']}}, {{$city['postcode']}} ({{$city['cityCount']}})</p>
			   		</div>
			   	@endforeach
			</div>
		</div>
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


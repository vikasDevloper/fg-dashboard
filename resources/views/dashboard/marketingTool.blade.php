@extends('layouts.app')

@section('content')
<div class="row" style="margin-bottom: 10px;">
<!--   <h2 class="pull-left" style="width: 65%">Dashboard</h2>  -->

</div>

<div class="row" style="background-color: #f8f8f8;">
  	<div class="table-responsive col-md-6" style="border: 1px solid #ccc;">
		<h4>Marketing Tool To Send SMS and Email</h4>
  		<form name="form" method="post" action="/marketing-tool-view">
  			<input type="hidden" name="_token" value="<?php echo csrf_token();?>">
	  		<table class="table">


		        <tbody style="width: 700px; ">

				        <tr>
				        <th></th>
				        <th> <input type="checkbox" name="sendsms" value="1"> Send SMS <input type="checkbox" name="sendemail" value="1"> Send Email </th>
				        </tr>
				        <tr>
				        <th>City</th>
				         <td><select name="city">
				         	<option value="">Please Select City</option>
				         	@if(!empty($data['citylist']))
				         		@foreach($data['citylist'] AS $value)
				         		@php
				         		$cityName = $value['city_id'] .'-'.$value['city_name'];

				         		@endphp
								  <option value="{{ $cityName }}">{{ $value['city_name'] }}</option>

								  @endforeach
							@endif
							</select>
						</td>
				        </tr>
				        <tr>
				         <th>City Like</th>
				         <td><input type="text" name="citylike" value="" required> <strong>(Use comma (,) for multiple cities)</strong></td>
				        </tr>
				        <tr>
				        <th>Email Subject</th>
				        <td><input type="text" name="subject" value=""></td>
				        </tr>
				        <tr>
				        <th>Preview Text</th>
				        <td><input type="text" name="previewtext" value=""></td>
				        </tr>
				        <tr>
				        <th>SMS Content</th>
				        <td><textarea name="smscontent" style="width: 400px;"></textarea></td>
				        </tr>
				        <tr>
				        <th>Template Name </th>
				        <td>
				        <select name="templatename">
				         	<option value="">Please Select Template</option>
				         	@if(!empty($data['templatelist']))
				         		@foreach($data['templatelist'] AS $value)

								  <option value="{{ $value['template_code'] }}">{{ $value['template_code'] }}</option>

								  @endforeach
							@endif
						</select>
						</td>
				        </tr>
				        <tr>
				        <th>User Type </th>
				        <td><select name="usertype">
				         	<option value="">Select User Type</option>

								  <option value="TestUsers">Test Users</option>
								  <option value="LiveUsers">Live Users</option>



							</select>
						</td>
				        </tr>

				    <tr><td></td><td><input type="submit" name="Send" value="Send"></td></tr>
		        </tbody>
	  		</table>
	  	</form>
	  	@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

  	</div>

<div class="table-responsive col-md-6" style="border: 1px solid #ccc;">
		<h4>Add Template</h4>
  		<form name="form" method="post" action="/marketing-tool">
  			<input type="hidden" name="_token" value="<?php echo csrf_token();?>">
	  		<table class="table">


		        <tbody style="width: 300px; ">

				        <tr>
				        <th>Email Subject</th>
				        <td><input type="text" name="template_subject" value="" required></td>
				        </tr>
				        <tr>
				        <th>Template Name With Path</th>
				        <td><input type="text" name="template_name" value="" required></td>
				        </tr>
				        <tr><td><td><strong>( Use path like emails.promotions.TemplateName)</strong></td></tr>


				    <tr><td></td><td><input type="submit" name="Save" value="Add Template"></td></tr>
		        </tbody>
	  		</table>
	  	</form>


  	</div>

</div>


@endsection





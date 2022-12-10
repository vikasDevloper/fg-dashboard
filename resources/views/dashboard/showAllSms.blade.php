@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col-md-12">
    <h3 class="text-center">Show All SMS</h3>
    @foreach($data['showAllSms'] as $sms)
      <div class="row">
        <div style="width: 400px; margin: auto;">
          <h4 class="text-center">Sms Type:: {{$sms['sms_type']}}</h4>
<?php echo nl2br($sms['sms_content']);?>
</div>
      </div>
    @endforeach

  </div>
</div>
@endsection

@section('scripts')
@endsection


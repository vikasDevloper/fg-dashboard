@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col-md-12 text-center">
    <h3>Show All Emails</h3>
    @foreach($data['showAllMails'] as $mails)
      <div class="row text-center">
        <div style="width: 400px; margin:auto;">
          <h4>Subject:: {{$mails['subject']}}</h4>
<?php echo $mails['email_content'];?>
</div>
      </div>
    @endforeach

  </div>
</div>
@endsection

@section('scripts')
@endsection


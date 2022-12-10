@extends('layouts.app')
@section('content')
<?php ini_set('max_execution_time', 18000);?>
<div class="row" style="margin-bottom: 10px;">
<!--   <h2 class="pull-left" style="width: 65%">Dashboard</h2>  -->
  <form action="{{ route('channel-cost-revenue') }}" id="filter" name="filter" method="get">
    <button class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">Apply</button>
    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; margin-right: 15px;">
        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        <span></span> <b class="caret"></b>
    </div>
    <input type="hidden" name="start-date" id="start-date">
    <input type="hidden" name="end-date" id="end-date">

  </form>
</div>

<div class="row">
  <div class="table-responsive col-md-12">
    <h3>SMS cost for every channel</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Sent Date</th>
          <th>Count</th>
          <th>Tag</th>
          <th>Cost</th>
        </tr>
      </thead>
      <tbody>
<?php //echo '<pre>';?>
{{--print_r($data['smsrevenue']) --}}
      @foreach($data['smsrevenue'] AS $key => $value)
        <tr>
          <td>{{$value['sent_at']}}</td>
          <td>{{$value['count']}}</td>
          <td>{{$value['tag']}}</td>
          <td>RS. {{$value['count']*0.12}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="row">
  <div class="table-responsive col-md-12">
    <h3>Email cost for every channel</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Sent Date</th>
          <th>Count</th>
          <th>Tag</th>
          <th>Cost</th>
        </tr>
      </thead>
      <tbody>
<?php //echo '<pre>';?>
{{--print_r($data['smsrevenue']) --}}
      @foreach($data['emailrevenue'] AS $key => $value)
        <tr>
          <td>{{$value['sent_at']}}</td>
          <td>{{$value['count']}}</td>
          <td>{{$value['tag']}}</td>
          <td>RS. {{$value['count']*0.12}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>

<br><br>
@endsection

@section('scripts')
<script type="text/javascript">
  $(function() {

      var start = moment('{{$data['startDate']}}');
      var end = moment('{{$data['endDate']}}');

      // end1 = end;

      function cb(start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          $('#start-date').val(start.format('YYYY-MM-DD'));
          $('#end-date').val(end.format('YYYY-MM-DD'));
          // if(end1.format('YYYY-M-D') != end.format('YYYY-M-D')){
          //    end1 = end;
          //    $('#filter').submit();
          // }
      }

      $('#reportrange').daterangepicker({
          startDate: start,
          endDate: end,
          ranges: {
             'Today': [moment(), moment()],
             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
             'Last 30 Days': [moment().subtract(29, 'days'), moment()],
             'This Month': [moment().startOf('month'), moment().endOf('month')],
             'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          }
      }, cb);

      cb(start, end);

  });
</script>
@endsection
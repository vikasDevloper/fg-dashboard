@extends('layouts.app')
@section('content')
<?php ini_set('max_execution_time', 18000);?>
<div class="row" style="margin-bottom: 10px;">
<!--   <h2 class="pull-left" style="width: 65%">Dashboard</h2>  -->
  <form action="{{ route('product-sold-by-color-price') }}" id="filter" name="filter" method="get">
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

  <div class="table-responsive col-md-6">

    <h3>Product Sold by Color</h3>
    @if(count($data['saleByColors']) > 0)

      <table class="table">
        <thead>
          <tr>
            <th>Color</th>
            <th>No Of Items</th>
          </tr>
        </thead>
        <tbody>
        @php ($i = 0)
        @foreach($data['saleByColors'] AS $key => $value)
          @if($i == 0)
            @php($i++)
            @continue
          @endif
          <tr>
            <td>{{$key}}</td>
            <td>{{$value}} ({{round($value*100/($data['saleByColors']['total']), 2)}}%)</td>
          </tr>
        @endforeach
        <tr><td><b>Total>></b></td><td><b>{{$data['saleByColors']['total']}}</b></td></tr>
        </tbody>
      </table>
    @endif

  </div>

  <div class="table-responsive col-md-6">
    <h3>Product Sold by Price</h3>
    @if(count($data['saleByPrice']) > 0)

      <table class="table">
        <thead>
          <tr>
            <th>Price Range</th>
            <th>No Of Items</th>
          </tr>
        </thead>
        <tbody>
        @php ($i = 0)
        @if($data['saleByPrice']['total'] > 0)
        @foreach($data['saleByPrice'] AS $key => $value)
          @if($i == 0)
            @php($i++)
            @continue
          @endif
          <tr>
            <td>{{$key}}</td>
            <td>{{$value}} ({{round($value*100/($data['saleByPrice']['total']), 2)}}%)</td>
          </tr>
        @endforeach
        @endif
        <tr><td><b>Total>></b></td><td><b>{{$data['saleByPrice']['total']}}</b></td></tr>
        </tbody>
      </table>
    @endif
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
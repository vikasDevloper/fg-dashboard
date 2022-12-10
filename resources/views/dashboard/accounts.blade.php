@extends('layouts.app')

@section('content')
<div class="row" style="margin-bottom: 10px;">
<!--   <h2 class="pull-left" style="width: 65%">Dashboard</h2>  -->
  <form action="{{ route('accounts-dashboard') }}" id="filter" name="filter" method="get">
    <button class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">Apply</button>
    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; margin-right: 15px;">
        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        <span></span> <b class="caret"></b>
    </div>
    <input type="hidden" name="start-date" id="start-date">
    <input type="hidden" name="end-date" id="end-date">

  </form>
</div>


<div class="row" style="background-color: #f8f8f8;">
<div class="table-responsive col-md-12">
@if($data['totalStoreCredit'] > 0)
  <h4>Total store credits :: <b>&#8377; {{$data['totalStoreCredit']}}</b></h4>
@endif
</div>
</div>

<br>

<div class="row">
<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd;">
<h4>Total Qty Sold</h4>
@if(!empty($data['soldQtyByCategory']))
  <table class="table">
    <thead>
      <tr>
        <th>Category</th>
        <th>Qty</th>
      </tr>
    </thead>
    <tbody>
    @php ($i = 0)
    @foreach($data['soldQtyByCategory'] AS $key => $value)
      @if($i == 0)
        @php($i++)
        @continue
      @endif
      <tr>
        <td>{{$key}}</td>
        <td>{{$value}} ({{ $data['soldQtyByCategory']['total'] > 0 ? (round($value*100/($data['soldQtyByCategory']['total']), 2)) : ''}}%)</td>
      </tr>
    @endforeach
    <tr><td><b>Total>></b></td><td><b>{{$data['soldQtyByCategory']['total']}}</b></td></tr>
    </tbody>
  </table>
@endif
</div>

<div class="table-responsive col-md-6">
<h4>Total Qty Refunded</h4>
@if(!empty($data['returnedQtyByCategory']))
  <table class="table">
    <thead>
      <tr>
        <th>Category</th>
        <th>Qty</th>
      </tr>
    </thead>
    <tbody>
    @php ($i = 0)
    @foreach($data['returnedQtyByCategory'] AS $key => $value)
      @if($i == 0)
        @php($i++)
        @continue
      @endif
      <tr>
        <td>{{$key}}</td>
        <td>{{$value}} ({{ $data['returnedQtyByCategory']['total'] > 0 ? (round($value*100/($data['soldQtyByCategory']['total']), 2)) : ''}}%)</td>
      </tr>
    @endforeach
    <tr><td><b>Total>></b></td><td><b>{{$data['returnedQtyByCategory']['total']}}</b></td></tr>
    </tbody>
  </table>
@endif
</div>
</div>

<br>

<div class="row" style="background-color: #f8f8f8; height: 365px; overflow: scroll;">
<p class="text-right"><span style="font-size: 10px;">scroll to see more..&nbsp;
&nbsp;
</span></p>
<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd;">
<h4>Total Sold Tax by State</h4>
@if(!empty($data['totalSoldTaxByState']))
  <table class="table">
    <thead>
      <tr>
        <th>State</th>
        <th>Qty</th>
        <th>Tax Amount</th>
        <th>Basic Value</th>
        <th>Total Amount</th>
      </tr>
    </thead>
    <tbody>
    @foreach($data['totalSoldTaxByState'] AS $value)
      <tr>
        <td>{{$value['state']}}</td>
        <td>{{$value['qty']}}</td>
        <td>{{$value['taxAmount']}}</td>
        <td>{{$value['basicValue']}}</td>
        <td>{{$value['totalAmount']}}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
@endif
</div>

<div class="table-responsive col-md-6">
<h4>Total Refunded Tax by State</h4>
@if(!empty($data['totalRefundedTaxByState']))
{{-- {{print_r($data['totalRefundedTaxByState'])}}--}}
  <table class="table">
    <thead>
      <tr>
        <th>State</th>
        {{-- <th>Qty Ordered</th> --}}
        <th>Qty Refunded</th>
        <th>Tax Amount</th>
        <th>Basic Value</th>
        <th>Total Amount</th>
      </tr>
    </thead>
    <tbody>
    @foreach($data['totalRefundedTaxByState'] AS $value)
      <tr>
        <td>{{$value['state']}}</td>
        {{-- <td>{{$value['qtyOrdered']}}</td> --}}
        <td>{{$value['qtyRefunded']}}</td>
        {{-- <td>{{$value['taxAmount']}}</td>
        <td>{{$value['basicValue']}}</td>
        <td>{{$value['totalAmount']}}</td> --}}
        <td>{{$value['refundedTaxAmount']}}</td>
        <td>{{$value['refundedBasicValue']}}</td>
        <td>{{$value['refundedTotalAmount']}}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
@endif
</div>
</div>

<br>

<div class="row">
<div class="table-responsive col-md-6" style="border-right: 1px solid #ddd;">
  <h4>COD Order delivered based on invoiced date</h4>
  @if(!empty($data['deliveredByInvoicedCod']))
  {{-- {{print_r($data['totalRefundedTaxByState'])}}--}}
    <table class="table">
      <thead>
        <tr>
          <th>Invoiced Date</th>
          <th>Order Delivered</th>
          <th>Qty</th>
          <th>Total Amount</th>
        </tr>
      </thead>
      <tbody>
      @foreach($data['deliveredByInvoicedCod'] AS $value)
        <tr>
          <td>{{$value['invoicedDate']}}</td>
          <td>{{$value['OrderCount']}}</td>
          <td>{{$value['qty']}}</td>
          <td>{{$value['Amount']}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @endif
</div>

<div class="table-responsive col-md-6">
  <h4>Prepaid Order delivered based on invoiced date</h4>
  @if(!empty($data['deliveredByInvoicedPrepaid']))
  {{-- {{print_r($data['totalRefundedTaxByState'])}}--}}
    <table class="table">
      <thead>
        <tr>
          <th>Invoiced Date</th>
          <th>Order Delivered</th>
          <th>Qty</th>
          <th>Total Amount</th>
        </tr>
      </thead>
      <tbody>
      @foreach($data['deliveredByInvoicedPrepaid'] AS $value)
        <tr>
          <td>{{$value['invoicedDate']}}</td>
          <td>{{$value['OrderCount']}}</td>
          <td>{{$value['qty']}}</td>
          <td>{{$value['Amount']}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  @endif
</div>
</div>
@endsection



@section('scripts')
<script type="text/javascript">
  $(function() {

      var start = moment('{{$data['startDate']}}');
      var end = moment('{{$data['endDate']}}');

      // end1 = end;

      function cb(start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          $('#start-date').val(start.format('YYYY-M-D'));
          $('#end-date').val(end.format('YYYY-M-D'));
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


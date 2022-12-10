<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta name="google-site-verification" content="IbTgCvAxEogVmtXume67CeyRMyQEeHz20vjUZmBWCSU" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <!--  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet"> -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('alertifyjs/css/alertify.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('alertifyjs/css/themes/default.css')}}" />

<style type="text/css">
  #fixed_column th, #fixed_column td { text-align: center; background-color: white }
  #fixed_column table { position: relative; width: 400px; overflow: hidden; }
  #fixed_column thead { position: relative; display: block; width: 1150px; overflow: visible; }
  #fixed_column thead th { min-width: 100px; max-width: 100px; padding: 0; background-color: #f39c12; border-color:#e67e22; border: none; word-wrap: break-word; white-space: pre-wrap; }
  #fixed_column thead th:nth-child(1) { position: relative;}
  #fixed_column tbody { position: relative; display: block; width: 1150px; height: 500px; overflow: scroll; }
  #fixed_column tbody td { min-width: 100px; max-width: 100px; background-color: #f3f3f3; border-color:#ccc; }
  #fixed_column tbody tr td:nth-child(1) { position: relative; display: block; }
  .wrap2 { width: 100%;}
  .wrap2 table { width: 100%; table-layout: fixed;}
  table tr td { padding: 5px; border: 1px solid #eee; width: 70px; }
  table.head tr td {font-weight: bold;}
  .inner_table2 { height: 500px; overflow-y: auto; width: 100%; }

.table-fixed {
  width : 100%;
  background-color : #f3f3f3;
}

.table-fixed tbody{
    height : 400px;
    overflow-y : auto;
    width : 100%;
    }

.table-fixed thead, .table-fixed tbody, .table-fixed thead tr, .table-fixed td, .table-fixed th{
    display : block;
  }

/*.table-fixed tbody tr, .table-fixed tbody tr td{
    float:left;
}*/

.table-fixed thead tr th {
    /*float:left;*/
    background-color: #f39c12;
    border-color:#e67e22;
    word-wrap: break-word;
    white-space: pre-wrap;
}
.navbar-nav>li {
    /*width: 143px;*/
    margin-top: 20px;
}


</style>
 @yield('page-css')
</head>
<?php
/*-- $valid = array('kiran@faridagupta.com', 'anand@faridagupta.com', 'mansi@faridagupta.com', 'arjun@faridagupta.com', 'zeba@faridagupta.com', 'varsha@faridagupta.com'); --*/
?>
<body>
    <div id="app">
<?php
$container = 'container';

/*print_r(request()->route()->getAction());
if(null !== request()->route()->getAction()['as']) {
    if ( request()->route()->getAction()['as']!=null && request()->route()->getAction()['as'] == 'break-even-analysis') {
    $container = 'container-fluid';
    }
} */
?>
<nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">



                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ route('main') }}">
                        <img src="https://www.faridagupta.com/skin/frontend/ves_gentshop/default1/images/fg-logo.png" height="50" alt="Farida Gupta" class="pull-left" style="margin-right: 10px; margin-top: -13px;">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">

                    <!-- Right Side Of Navbar -->
                     <ul class="nav navbar-nav navbar-left">
                        <!-- Authentication Links -->
                        @if (Auth::guest())

                        @else
                            @if(Auth::user()->user_type == 'A')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                       Admin Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('main') }}" target="_blank">Site Overview</a></li>
                                        <li><a href="{{ route('products-dashboard') }}" target="_blank">Products Dashboard</a></li>
                                        <li><a href="{{ route('marketing-dashboard') }}" target="_blank">Marketing Dashboard</a></li>
                                        <li><a href="{{ route('cx-dashboard') }}" target="_blank">CX Dashboard</a></li>
                                         <li><a href="{{ route('warehouse-picking-dashboard') }}" target="_blank">Warehouse Picking Dashboard</a></li>
                                        <li><a href="{{ route('accounts-dashboard') }}" target="_blank">Account Dashboard</a></li>
                                        <li><a href="{{ route('logistics-dashboard') }}" target="_blank">Logistic Dashboard</a></li>
                                        <li><a href="{{ route('sales-status') }}" target="_blank">Sales Through</a></li>
                                        <li><a href="{{ route('order-status') }}" target="_blank"> Shipping Dashboard</a></li>
                                        <li><a href="{{ route('shipped-status') }}" target="_blank">Warehouse Pendency Report</a></li>
                                        <li><a href="{{ route('exhibition-dashboard') }}" target="_blank">Exhibition Dashbord</a></li>
                                        <li><a href="{{ route('daily-turnover-status') }}" target="_blank">Daily Turnover Report</a></li>
                                        @if(Auth::user()->email == 'sahil@faridagupta.com' ||Auth::user()->email == 'sanjay@faridagupta.com' || Auth::user()->email == 'nitya@faridagupta.com')
                                        <li><a href="{{ route('yearly-turnover-status') }}" target="_blank">Yearly Turnover Report</a></li>
                                         <li><a href="{{ route('break-even-analysis') }}" target="_blank">Breakeven Dashboard</a></li>
                                        @endif
                                        <li><a href="{{ route('download-invoice') }}" target="_blank">Invoice</a></li>
                                        <li><a href="{{ route('download-credit-memo') }}" target="_blank">Credit Memo</a></li>
                                       
                                    </ul>
                                </li>

                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Marketing Tools<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('marketing-tool') }}" target="_blank">Marketing Tools</a></li>
                                        <li><a href="{{ route('clean-city-operation') }}" target="_blank">Clean City Operation</a></li>
                                        <li><a href="{{ route('send-promotion-mails') }}" target="_blank">Test email check</a></li>
                                        <li><a href="{{ route('channel-cost-revenue') }}" target="_blank">Channel cost revenue ROI</a></li>
                                        <li><a href="{{ route('product-sold-by-color-price') }}" target="_blank">Product sold by color/price</a></li>
                                        <li><a href="{{ route('show-all-emails') }}" target="_blank">Show All Emails</a></li>
                                        <li><a href="{{ route('show-all-sms') }}" target="_blank">Show All SMS</a></li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type == 'ACH')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Accounts Head Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('accounts-dashboard') }}" target="_blank">Account Dashboard</a></li>
                                        <li><a href="{{ route('daily-turnover-status') }}" target="_blank">Daily Turnover Report</a></li>
                                        <li><a href="{{ route('marketing-dashboard') }}" target="_blank">Marketing Dashboard</a></li>
                                        <li><a href="{{ route('download-invoice') }}" target="_blank">Invoice</a></li>
                                        <li><a href="{{ route('download-credit-memo') }}" target="_blank">Credit Memo</a></li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type == 'AC')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Accounts Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('accounts-dashboard') }}" target="_blank">Account Dashboard</a></li>
                                        <li><a href="{{ route('download-invoice') }}" target="_blank">Invoice</a></li>
                                        <li><a href="{{ route('download-credit-memo') }}" target="_blank">Credit Memo</a></li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type == 'CXH')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        CX - Team Lead Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('cx-dashboard') }}" target="_blank">CX Dashboard</a></li>
                                        <li><a href="{{ route('products-dashboard') }}" target="_blank">Products Dashboard</a></li>
                                        <li><a href="{{ route('logistics-dashboard') }}" target="_blank">Logistic Dashboard</a></li>
                                        <li><a href="{{ route('order-status') }}" target="_blank"> Shipping Dashboard</a></li>
                                        <li><a href="{{ route('shipped-status') }}" target="_blank">Warehouse Pendency Report</a></li>
                                        <li><a href="{{ route('sales-status') }}" target="_blank">Sales Through</a></li>
                                        <li><a href="{{ route('warehouse-picking-dashboard') }}" target="_blank">Warehouse Picking Dashboard</a></li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type == 'CX')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        CX Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('cx-dashboard') }}" target="_blank">CX Dashboard</a></li>
                                        <li><a href="{{ route('products-dashboard') }}" target="_blank">Products Dashboard</a></li>
                                        <li><a href="{{ route('order-status') }}" target="_blank"> Shipping Dashboard</a></li>
                                        <li><a href="{{ route('sales-status') }}" target="_blank">Sales Through</a></li>
                                        <li><a href="{{ route('warehouse-picking-dashboard') }}" target="_blank">Warehouse Picking Dashboard</a></li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type == 'WHH')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Warehouse Manager Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('logistics-dashboard') }}" target="_blank">Logistic Dashboard</a></li>
                                        <li><a href="{{ route('products-dashboard') }}" target="_blank">Products Dashboard</a></li>
                                        <li><a href="{{ route('order-status') }}" target="_blank"> Shipping Dashboard</a></li>
                                        <li><a href="{{ route('shipped-status') }}" target="_blank">Warehouse Pendency Report</a></li>
                                        <li><a href="{{ route('sales-status') }}" target="_blank">Sales Through</a></li>
                                         <li><a href="{{ route('warehouse-picking-dashboard') }}" target="_blank">Warehouse Picking Dashboard</a></li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type == 'WH')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Warehouse Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('logistics-dashboard') }}" target="_blank">Logistic Dashboard</a></li>
                                        <li><a href="{{ route('products-dashboard') }}" target="_blank">Products Dashboard</a></li>
                                        <li><a href="{{ route('order-status') }}" target="_blank"> Shipping Dashboard</a></li>
                                        <li><a href="{{ route('shipped-status') }}" target="_blank">Warehouse Pendency Report</a></li>
                                         <li><a href="{{ route('warehouse-picking-dashboard') }}" target="_blank">Warehouse Picking Dashboard</a></li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type == 'QC')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        QC Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('order-status') }}" target="_blank"> Shipping Dashboard</a></li>
                                        <li><a href="{{ route('shipped-status') }}" target="_blank">Warehouse Pendency Report</a></li>
                                    </ul>
                                </li>
                                
                                @elseif(Auth::user()->user_type == 'MK')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Marketing Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('marketing-dashboard') }}" target="_blank"> Marketing Dashboard</a></li>
                                    </ul>
                                </li>

                           @elseif(Auth::user()->user_type == 'LC')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                     Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                      <li><a href="{{ route('fg-short-url') }}" target="_blank">URL Shortener</a></li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->user_type == 'SD')
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Tech-Team Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('main') }}" target="_blank">Site Overview</a></li>
                                        <li><a href="{{ route('products-dashboard') }}" target="_blank">Products Dashboard</a></li>
                                        <li><a href="{{ route('marketing-dashboard') }}" target="_blank">Marketing Dashboard</a></li>
                                        <li><a href="{{ route('cx-dashboard') }}" target="_blank">CX Dashboard</a></li>
                                        <li><a href="{{ route('accounts-dashboard') }}" target="_blank">Account Dashboard</a></li>
                                        <li><a href="{{ route('logistics-dashboard') }}" target="_blank">Logistic Dashboard</a></li>
                                        <li><a href="{{ route('sales-status') }}" target="_blank">Sales Through</a></li>
                                        <li><a href="{{ route('order-status') }}" target="_blank"> Shipping Dashboard</a></li>
                                        <li><a href="{{ route('shipped-status') }}" target="_blank">Warehouse Pendency Report</a></li>
                                         <li><a href="{{ route('warehouse-picking-dashboard') }}" target="_blank">Warehouse Picking Dashboard</a></li>
                                    </ul>
                                </li>

                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Marketing Tools<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('marketing-tool') }}" target="_blank">Marketing Tools</a></li>
                                        <li><a href="{{ route('clean-city-operation') }}" target="_blank">Clean City Operation</a></li>
                                        <li><a href="{{ route('send-promotion-mails') }}" target="_blank">Test email check</a></li>
                                        <li><a href="{{ route('channel-cost-revenue') }}" target="_blank">Channel cost revenue ROI</a></li>
                                        <li><a href="{{ route('product-sold-by-color-price') }}" target="_blank">Product sold by color/price</a></li>
                                        <li><a href="{{ route('show-all-emails') }}" target="_blank">Show All Emails</a></li>
                                        <li><a href="{{ route('show-all-sms') }}" target="_blank">Show All SMS</a></li>
                                    </ul>
                                </li>
                            {{-- @else
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        Developer Dashboard<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ route('main') }}" target="_blank">Site Overview</a></li>
                                        <li><a href="{{ route('products-dashboard') }}" target="_blank">Products Dashboard</a></li>
                                        <li><a href="{{ route('marketing-dashboard') }}" target="_blank">Marketing Dashboard</a></li>
                                        <li><a href="{{ route('cx-dashboard') }}" target="_blank">CX Dashboard</a></li>
                                        <li><a href="{{ route('accounts-dashboard') }}" target="_blank">Account Dashboard</a></li>
                                        <li><a href="{{ route('logistics-dashboard') }}" target="_blank">Logistic Dashboard</a></li>
                                        <li><a href="{{ route('order-status') }}" target="_blank"> Shipping Dashboard</a></li>
                                        <li><a href="{{ route('shipped-status') }}" target="_blank">Warehouse Pendency Report</a></li>
                                        <li><a href="{{ route('sales-status') }}" target="_blank">Sales Through</a></li>
                                        <li><a href="{{ route('channel-cost-revenue') }}" target="_blank">Channel cost revenue ROI</a></li>
                                        <li><a href="{{ route('product-sold-by-color-price') }}" target="_blank">Product sold by color/price</a></li>
                                        <li><a href="{{ route('show-all-emails') }}" target="_blank">Show All Emails</a></li>
                                        <li><a href="{{ route('show-all-sms') }}" target="_blank">Show All SMS</a></li>
                                    </ul>
                                </li> --}}
                            @endif
<?php /*
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
Dashboards<span class="caret"></span>
</a>

<ul class="dropdown-menu" role="menu">
<li><a href="{{ route('main') }}" target="_blank">Site Overview</a></li>
<li><a href="{{ route('products-dashboard') }}" target="_blank"> Products Dashboard</a></li>
<li><a href="{{ route('sales-status') }}" target="_blank"> Sales Through</a></li>
</ul>
</li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
Account<span class="caret"></span>
</a>

<ul class="dropdown-menu" role="menu">
<li><a href="{{ route('accounts-dashboard') }}" target="_blank"> Account Dashboard</a></li>
</ul>
</li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
Marketing <span class="caret"></span>
</a>

<ul class="dropdown-menu" role="menu">
<li><a href="{{ route('marketing-dashboard') }}" target="_blank">Marketing Dashboard</a></li>
<li><a href="{{ route('channel-cost-revenue') }}" target="_blank">Channel cost revenue ROI</a></li>
<li><a href="{{ route('product-sold-by-color-price') }}" target="_blank">Product sold by color/price</a></li>
<li><a href="{{ route('show-all-emails') }}" target="_blank">Show All Emails</a></li>
<li><a href="{{ route('show-all-sms') }}" target="_blank">Show All SMS</a></li>
{{-- <li><a href="{{ route('show-call-log') }}">Show Call Logs</a></li> --}}
</ul>
</li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
CX <span class="caret"></span>
</a>

<ul class="dropdown-menu" role="menu">
<li><a href="{{ route('cx-dashboard') }}" target="_blank">CX Dashboard</a></li>
</ul>
</li>

<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
Logistics<span class="caret"></span>
</a>
<ul class="dropdown-menu" role="menu">
<li><a href="{{ route('logistics-dashboard') }}" target="_blank">Logistic Dashboard</a></li>
<li><a href="{{ route('order-status') }}" target="_blank"> Shipping Dashboard</a></li>
<li><a href="{{ route('shipped-status') }}" target="_blank">Warehouse Pendency Report</a></li>
</ul>
</li> */?>
@endif
                    </ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <!-- <li><a href="{{ route('register') }}">Register</a></li> -->
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <div class="{{$container}}">
            @yield('content')
        </div>
    </div>
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('script')


 <!--    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script type="text/javascript" src="{{ asset('alertifyjs/alertify.js')}}"></script>

@yield('scripts')

</body>
</html>

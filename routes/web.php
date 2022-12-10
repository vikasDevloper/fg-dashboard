<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();


Route::get('/', 'Web\Dashboard\Dashboard@show')->name('main');
Route::post('/', 'Web\Dashboard\Dashboard@show');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/generate-pdf/{id?}', 'Web\Dashboard\PdfGenerateController@pdfview')->where('id', '[0-9]+')->name('generate-pdf');
Route::get('/download-invoice', 'Web\Dashboard\PdfGenerateController@downloadInvoice')->name('download-invoice');
Route::get('/download-backup', 'Web\Dashboard\PdfGenerateController@downloadbackup');
Route::get('/download-credit-memo', 'Web\Dashboard\CreditMemoController@creditMemo')->name('download-credit-memo');; 
Route::get('/generate-credit-memo', 'Web\Dashboard\CreditMemoController@generateCrMemo');
Route::get('/image-sync', 'Web\Dashboard\ImageSyncController@imagesync');
//Added routes for Api test
/****** FGURL *****    */
Route::post('/fgurl-apicall', 'Web\Dashboard\UrlShortnerLink@shortUrl');
Route::post('/fgurl-updated-apicall', 'Web\Dashboard\UrlShortnerLink@modifyUrl');
Route::post('/fgurl-get-urlshortenlog', 'Web\Dashboard\UrlShortnerLink@getUrlShortenLog');
Route::get('/fg-short-url', 'Web\Dashboard\UrlShortnerLink@showshortUrl')->name('fg-short-url');


Route::get('/apitest', function () {
		return view('api');
	});

Route::get('test_api', 'Web\Dashboard\Dashboard@testApi');

Route::get('my_first_api', 'ApiController@my_first_api');
Route::get('/redirect', 'ApiController@redirectAuthorization')->name('get.token');

Route::get('/callback', 'ApiController@callbackAccess');
Route::get('/turnover-status', 'Web\Dashboard\TurnoverController@show')->name('turnover-status');
Route::get('/daily-turnover-status', 'Web\Dashboard\TurnoverController@dailyRevenue')->name('daily-turnover-status');
Route::get('/setTarget', 'Web\Dashboard\TurnoverController@setTarget')->name('setTarget');
Route::get('/setTargetYear', 'Web\Dashboard\TurnoverController@setTargetYearly')->name('setTargetYear');
Route::get('/getMonthly', 'Web\Dashboard\TurnoverController@getMonthlyData')->name('getMonthly');
Route::get('/break-even-analysis', 'Web\Dashboard\BreakevenController@breakEvenAnalysis')->name('break-even-analysis');
Route::get('/product-details', 'Web\Dashboard\BreakevenController@show')->name('product-details');
//end Api routes  product-details
Route::middleware(['basicAuth'])->group(function () {
		//All the routes are placed in here
		Route::get('/home2', 'Web\Dashboard\Dashboard@show');
	});

Route::get('/logistics-dashboard', 'Web\Dashboard\LogisticsController@show')->name('logistics-dashboard');
Route::get('/marketing-dashboard', 'Web\Dashboard\MarketingDashboardController@show')->name('marketing-dashboard');
Route::get('/accounts-dashboard', 'Web\Dashboard\AccountsController@show')->name('accounts-dashboard');
Route::get('/products-dashboard', 'Web\Dashboard\ProductController@show')->name('products-dashboard');
Route::get('/marketing-tool', 'Web\Dashboard\MarketingToolController@show')->name('marketing-tool');
Route::get('/order-status', 'Web\Dashboard\OrderReportStatusController@show')->name('order-status');
Route::get('/shipped-status', 'Web\Dashboard\ShippedStatusControllerReport@show')->name('shipped-status');
Route::get('/city-changes-Test', 'Web\Dashboard\CityUpdateController@show')->name('city-changes-Test');

Route::get('/sales-status', 'Web\Dashboard\SalesReportController@show')->name('sales-status');
Route::post('/marketing-tool-view', 'Web\Dashboard\MarketingToolController@store')->name('marketing-tool-view');

Route::get('/rma-check', 'Web\Dashboard\SalesFlatCreditMemoGridController@showinfo')->name('rma-check');

Route::post('/marketing-tool', 'Web\Dashboard\MarketingToolController@create');

Route::get('/thank-you', 'Web\Dashboard\ExhibitionUserSourceController@viewthankyou');
Route::post('/save-source', 'Web\Dashboard\ExhibitionUserSourceController@storeSource');
Route::get('/exhibition-source', 'Web\Dashboard\ExhibitionUserSourceController@showSource')->name('exhibition-source');

Route::get('/ads-dashboard', 'FacebookAdsController@getAds')->name('facebook-dashboard');
Route::get('/google-ads-dashboard', 'GoogleAdsController@campaigns')->name('facebook-dashboard');

Route::get('/cx-dashboard', 'Web\Dashboard\CxDashboard@show')->name('cx-dashboard');
Route::get('/refund-report', 'Web\Dashboard\RefundReportController@show')->name('refund-report');

Route::get('downloadExcel/{id}', ['as' => 'report', 'uses' => 'Web\Dashboard\MarketingDashboardController@downloadExcel']);

/*
Create mails and use these controllers to test them
 */

Route::get('/send-promotion-mails', 'Web\Mails\TestMailsController@sendMail')->name('send-promotion-mails');
Route::get('/call-logs', 'HomeController@showCallLog')->name('show-call-log');
Route::get('/channel-cost-revenue', 'Web\Dashboard\MarketingDashboardController@channelCostRevenueRoi')->name('channel-cost-revenue');
Route::get('/product-sold-by-color-price', 'Web\Dashboard\MarketingDashboardController@productSoldByColorPrice')->name('product-sold-by-color-price');
Route::get('/show-all-emails', 'Web\Dashboard\Dashboard@showAllMails')->name('show-all-emails');
Route::get('/show-all-sms', 'Web\Dashboard\Dashboard@showAllSms')->name('show-all-sms');

/**
 *  Amazon SNS setup
 */

Route::post('/handle-bounces', 'Web\AmazonSNSController@handleBounces');
Route::post('/handle-complains', 'Web\AmazonSNSController@handleComplains');

Route::get('/clean-city-operation', 'Web\CleanCityOperationController@show')->name('clean-city-operation');
Route::post('/clean-city-operation', 'Web\CleanCityOperationController@cleanCityOperation');

/**
 * Exhibition Dashboard
 */

Route::post('/select-year-exhibition', 'Web\Dashboard\ExhibitionController@selectYear')->name('select-year');
Route::post('/select-month-exhibition', 'Web\Dashboard\ExhibitionController@selectMonth')->name('select-month');
Route::post('/select-place-exhibition', 'Web\Dashboard\ExhibitionController@selectPlace')->name('select-place');
Route::post('/search-exhibition-data', 'Web\Dashboard\ExhibitionController@exhibitionDataReport')->name('select-place');
Route::post('/search-exhibition-footFall', 'Web\Dashboard\ExhibitionController@exhibitionDataFootfallSource')->name('select-place');
Route::post('/search-exhibition-tranRep', 'Web\Dashboard\ExhibitionController@exhibitionDataTransactionReport')->name('select-place');
Route::post('/search-exhibition-histPer', 'Web\Dashboard\ExhibitionController@exhibitionDataHistoricalPerformance')->name('select-place');
Route::get('/exhibition-dashboard', 'Web\Dashboard\ExhibitionController@show')->name('exhibition-dashboard');


/**
 * ************ YOY Dashboard ************
 */

Route::get('/yearly-turnover-status', 'Web\Dashboard\YearlyTurnoverController@show')->name('yearly-turnover-status');
Route::post('/yearly_record', 'Web\Dashboard\YearlyTurnoverController@getRecord')->name('yearly_record');
Route::get('/yearly_record', 'Web\Dashboard\YearlyTurnoverController@getRecord');


/**
 * ************ CX Dashboard ************
*/


Route::get('/warehouse-picking-dashboard', 'Web\Dashboard\SalesReportController@showCatalog')->name('warehouse-picking-dashboard');
//Route::post('/catalog_by_date', 'Web\Dashboard\SalesReportController@getCatalogByDate');
Route::get('/catalog_by_date', 'Web\Dashboard\SalesReportController@getCatalogByDate');
Route::get('/update-picked-order', 'Web\Dashboard\SalesReportController@updatepickingOrder');
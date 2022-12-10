<?php

namespace Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\AdsInsights;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use Dashboard\Classes\Helpers\GoogleAdsService;
use FacebookAds\Object\CustomAudience;    // customaudience
use FacebookAds\Object\Fields\CustomAudienceFields;
use FacebookAds\Object\Values\CustomAudienceSubtypes;
//use Edbizarro\LaravelFacebookAds\FacebookAds;

class FacebookAdsController extends Controller
{
    //
    protected $adsApi = '';

    public function __construct()
    {
    	$accessToken = '';
    	Api::init(config('facebook-ads.app_id'), config('facebook-ads.app_secret'), config('facebook-ads.access_token'));
        $this->adsApi = Api::instance();
        //$this->adsApi->setLogger(new CurlLogger());
        //
    }

  //   public function getAds()
  //   {
  //   	$ad_account_id = config('facebook-ads.ad_account_id');
    	
  //   	$fields = array(
		//   'cost_per_result',
		//   'cost_per_total_action',
		//   'cpm',
		//   'cpp',
		//   'spend',
		//   'today_spend',
		// );
		
		// $params = array(
		//   'time_range' => array('since' => '2017-06-14','until' => '2017-07-14'),
		//   'filtering' => array(array('field' => 'delivery_info','operator' => 'IN','value' => array('completed','recently_completed')),array('field' => 'objective','operator' => 'IN','value' => array('REACH','LINK_CLICKS','CONVERSIONS','EVENT_RESPONSES')),array('field' => 'buying_type','operator' => 'IN','value' => array('AUCTION','FIXED_PRICE','RESERVED')),array('field' => 'adset.placement.page_types','operator' => 'ANY','value' => array('desktopfeed','mobilefeed','rightcolumn','instagramstory','mobileexternal','instagramstream'))),
		//   'level' => 'campaign',
		//   'breakdowns' => array(),
		// );

		// echo json_encode((new AdAccount($ad_account_id))->getInsights(
		//   $fields,
		//   $params
		// )->getResponse()->getContent(), JSON_PRETTY_PRINT);
    
  //   }

    public function getAds()
   	{

   		$adwords = new GoogleAdsService();
   		$page = $adwords->campaigns();
   		print_r($page);
   	}

    public function getAudience(){


    $graph_url= "https://graph.facebook.com/v5.0/act_381753992562760/customaudiences";
    $access_token = 'EAADmWwF9ppYBAHD49035NHONQ0Xzoz8JqCuh7TY5KybNbQOnEZBINl31JnkGnKJmCXXCHirZAQFtI0oWMaIjpkSZB6fVST4NovOZAMAiWeJInuZCrGj2ZAA8491SG4BdkJCqdM7q9jzPlcZBcFKjTuyZAwr2Dzj4O1DBOmr2KhCjxuKnSqvUQTjtDhtI7MMWU9cBR5YI2LsF8IhwwuW8ZCOkha6lBybCirY6CdXaycvQRVrwZBtIwejG4wdQgGVx2lkcwZD';
   

    $hash_mail1 = hash("sha256", "nainika@faridagupta.com");
    $hash_mail2 = hash("sha256", "naina@gmail.com");
    $hash_mail3 = hash("sha256", "rahul@gmail.com");

    $arr_mail = array( $hash_mail1, $hash_mail2, $hash_mail3);
  
    $audience_url= "https://graph.facebook.com/v5.0/23844147832630013/users";
  $customer_file_source = 'USER_PROVIDED_ONLY';
    
  $subtype = 'CUSTOM';  
  $name = 'My new Custom Audience-testing';
  $description = 'Testing Audience API';
  $postData =  "&access_token=" .$access_token.'&payload={"schema": ["EMAIL_SHA256"],"data":[["'.$hash_mail1.'"],["'.$hash_mail2.'"],["'.$hash_mail3.'"]]}';

    $ch = curl_init();    
   // curl_setopt($ch, CURLOPT_URL, $graph_url);
    curl_setopt($ch, CURLOPT_URL, $audience_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $output = curl_exec($ch);
    return $output;
    curl_close($ch);
    }
}

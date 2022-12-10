<?php

namespace Dashboard\Http\Controllers\Web\Dashboard;

use Illuminate\Http\Request;
use Dashboard\Classes\Helpers\Utility;
use Dashboard\Classes\Helpers\UrlShortener;
use Dashboard\Data\Models\UrlShortnerLog;
use Dashboard\Http\Controllers\Controller;
use Dashboard\Data\Models\urlShortner;
use Dashboard\Data\Models\CustomizedUrlShortner;
use Illuminate\Support\Facades\Validator;


class UrlShortnerLink extends Controller
{
    public static function setShortUrl(Request $request){
         //Utility::get_access_token();
         $data = self::urlShortApi();
         return $data;
  //       Utility::set_short_url("https://www.faridagupta.com/all-products?dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_3oct19_nb_last30&utm_location=-1&nofilter=1m");

		// return json_encode($data);
    }
    
    public static function urlShortApi() {

		$result = Utility::apiCallNew( $url="urlshort", $type="POST" , $_GET );
		return $result;
	}

    public function index()
    {
        $shortLinks = urlShortner::latest()->get();
   
        return view('shortenLink', compact('shortLinks'));
    }
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request)
    {
    	$data = $request->input();
    	$validator = Validator::make($data, [
            "url"         => "required|url",
        ]);

        $validator1 = Validator::make($data, [
            "url"         => "unique:url_shortener,redirect_url",
        ]);
        
         if ($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'status'      => 'failure',
                'error'       =>  $validator->errors()
             ]);
        }
        //echo  $data['url'];
        if ($validator1->fails()) {
            return response()->json([
                'status_code' => 201,
                'status'      => 'success',
                'result'     => config('app.url')."/".CustomizedUrlShortner::getShortUrl($data['url'])
             ]);
        }
         
       $input['redirect_url']       = $data['url'];
       $input['short_url']          = str_random(6);
       $urlID                       = "";
       $urlID                       = urlShortner::create($input)->id;
       $inputCustom['master_id']    = $urlID;
       $inputCustom['custom_url']   =  $input['short_url'];
       $inputCustom['redirect_url'] =  $data['url'];

       CustomizedUrlShortner::create($inputCustom);
       
       if(!empty($urlID)){
       	return response()->json([
                'status_code' => 201,
                'status'      => 'success',
                'result'     => config('app.url')."/".$input['short_url']
             ]);
       }else {
       	   return response()->json([
                'status_code' => 400,
                'status'      => 'failure',
                'error'       => $validator->errors()
             ]);
       }
    }
      
   public function editUrl(Request $request) {
   	$data = $request->input();
	$validator1 = Validator::make($data, [
        "url"        		 => "required",
        "custom_url"         => "required",
    ]);

    $validator2 = Validator::make($data, [
        "custom_url"         => "unique:customized_url_shortener,custom_url",
    ]);
     $validator3 = Validator::make($data, [
        "url"                => "exists:customized_url_shortener,custom_url",
    ]);
    if ($validator1->fails()) {
            return response()->json([
                'status_code' => 400,
                'status'      => 'failure',
                'error'       =>  $validator1->errors()
             ]);
        }

     if ($validator2->fails()) {
            return response()->json([
                'status_code' => 201,
                'status'      => 'exist',
                'result'      =>  $validator2->errors()
             ]);
        }   
	    if ($validator3->fails()) {
	        return response()->json([
	            'status_code' => 400,
	            'status'      => 'not exist',
	            'result'      =>  $validator3->errors()
	         ]);
	    }   
         
        try{
         
        $masterID = CustomizedUrlShortner::select('master_id','redirect_url')->where('custom_url', $data['url'])->first();
        $masterurlID = "";
        if(isset($masterID)){
          $inputCustom['master_id'] 	= $masterID->master_id;
          $inputCustom['redirect_url'] 	= $masterID->redirect_url;
          $inputCustom['custom_url'] 	= $data['custom_url'];
        }
        
        CustomizedUrlShortner::create($inputCustom);
        
     	return response()->json([
           'status_code'  => 200,
           'status'       => 'success',
           'success'      => $masterurlID
         ]);
        }
	    catch(\Exception $e){
	 	 	 return response()->json([
	            'status_code'  => 400,
                'status'       => 'failure',
                'error'        => $e->getMessage()   
	        ]);
	 	 }
    return $sortUrl;
   }

   public function getShortUrl(Request $request){

     $validator = Validator::make($data, [
            "url"         => "exists:url_shortener,redirect_url",
        ]);
        
         if ($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'status'      => 'failure',
                'error'       =>  $validator->errors()
             ]);
        }
        if ($validator1->fails()) {
            return array([		
                'status_code' => 201,
                'status'      => 'success',
                'result'     => CustomizedUrlShortner::getShortUrl($data['url'])
             ]);
        }
   }

    public function getLongUrl(Request $request){
     
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shortenLink($code)
    {

        $find = CustomizedUrlShortner::where('custom_url', $code)->first();
        $input['cutomized_id']  = "";
        $input['short_url']     = "";
        $input['redirect_url']  = "";
        $input['ip_addr']       = $_SERVER['REMOTE_ADDR'];
        if(isset($find)){
            $input['cutomized_id']  = $find->id;
            $input['short_url'] 	= $find->custom_url;
            $input['redirect_url']  = $find->redirect_url;

            UrlShortnerLog::create($input);
            return redirect($find->redirect_url);
        }
        else 
        {
            UrlShortnerLog::create($input);
            return redirect("https://www.faridagupta.com/");
        }
 
        
    }


    public function showshortUrl(){
        return view('dashboard.fgUrlShortner');

    }
    public function shortUrl(Request $request){
        
         $data = $request->input();
         $validator = Validator::make($data, [
            "input-url"         => "required",
        ]);
        
         if ($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'status'      => 'failure',
                'error'       =>  $validator->errors()
             ]);
        }
        if ($data['input-url'] != '')
                $url = json_decode(UrlShortener::get_fg_short_url($data['input-url']));
            
           if(isset($url->error) && $url->error == "Unauthenticated."){
             return response()->json([
                'status_code' => 400,
                'status'      => 'failure',
                'error'       =>  "FGURL Authentication Error"
             ]);
           }

           if(!isset($url->status_code)){
            return response()->json([
                'status_code' => 400,
                'status'      => 'failure',
                'error'       =>  "FGURL Status Not Set"
             ]);
           }

        if(!empty($url)){
        return json_encode($url);
       }

    }

}

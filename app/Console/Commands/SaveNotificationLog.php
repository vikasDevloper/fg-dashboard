<?php

namespace Dashboard\Console\Commands;

use Illuminate\Console\Command;
use Dashboard\Data\Models\NotificationLog;
use Dashboard\Data\Models\NotificationSend;
use Dashboard\Data\Models\UrlShortenerLog;
use Dashboard\Classes\Helpers\UrlShortener;
use DB;
class SaveNotificationLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificationlog:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adding UTM compaign';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // api to get url and save to url shortener log

        $a1 = $this->saveLogSms();
        $a2 = $this->saveLogEmail();
        if($a1 && $a2){
            echo 'Success';
        }else{
            echo 'Failure';
        }

       // $this->getClicks();


    }

    public function saveLogSms(){
       // $arr = true;

        //$resUrlClicks = UrlShortenerLog::getUrlLogs();
        //shorturl, date
        $date = date("Y-m-d");
        $res = NotificationSend::getAllUrlLogsSMS($date);


        if(empty($res)){
              foreach ($res as $key => $value) {

        if(!empty($res)){
        foreach ($res as $key => $value){
            //$clicksapi = UrlShortener::get_url_clicks_count($value['date'], $value['utm_id']);
            $clicksapi = 0;
            if($value['utm_id'] != ''){
                $click_arr = UrlShortener::get_url_clicks_count($value['date'],$value['utm_id']);
                $arr1 = json_decode($click_arr);
                if(isset($arr1[0]->count)){
                    $clicksapi = $arr1[0]->count;
                }
            }

            $notificationlogdata                =  new NotificationLog;
            $notificationlogdata->type  = $value['sms'] ; 
            $notificationlogdata->tag    = $value['purpose'] ; 
            $notificationlogdata->utm_id    = $value['utm_id'] ; 
            $notificationlogdata->compaign_name    = $value['compaign_name'] ; 
            $notificationlogdata->total_added    = $value['added'] ; 
            $notificationlogdata->costing    = $value['costing'] ; 
            $notificationlogdata->count    = $value['sent'] ; 
            $notificationlogdata->clicks    = $clicksapi ; 
            $notificationlogdata->sent_at    = $value['date']; 

           $arr = $notificationlogdata->save();
           return $arr;
        }

        }else{
            return false;
        }   
      
        
        // dd($arr);
       // $arr = NotificationLog::insert($data);


       // dd($data);

}
        
 }
    }
      public function saveLogEmail(){
     //   $arr = true;
        $date = date("Y-m-d");
        $res = NotificationSend::getAllUrlLogEmails($date);


      if(empty($res)){
        foreach ($res as $key => $value) {
            //$clicksapi = UrlShortener::get_url_clicks_count($value['date'], $value['utm_id']);
             $clicksapi = 0;

            if($value['compaign_name'] != ''){
                $click_arr = UtmCampaign::getCompaignCount($value['date'],$value['compaign_name']);
                $arr1 = json_decode($click_arr);
                if(isset($arr1[0]->count)){
                    $clicksapi = $arr1[0]->count;
                }
            }
           
            $notificationlogdata                =  new NotificationLog;
            $notificationlogdata->type  = $value['email'] ; 
            $notificationlogdata->tag    = $value['purpose'] ; 
            $notificationlogdata->utm_id    = $value['utm_id'] ; 
            $notificationlogdata->compaign_name    = $value['compaign_name'] ; 
            $notificationlogdata->costing    = $value['costing'] ; 
            $notificationlogdata->total_added    = $value['added'] ; 
            $notificationlogdata->count    = $value['sent'] ; 
            $notificationlogdata->clicks    = $clicksapi ; 
            $notificationlogdata->sent_at    = $value['date']; 

            $arr = $notificationlogdata->save();
           return $arr;
        }


        }else{
            return false;
        }  

    }


}

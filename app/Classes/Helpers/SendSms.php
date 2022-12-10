<?php

namespace Dashboard\Helpers;
use Dashboard\Classes\Helpers\Netcore;
use Dashboard\Classes\Helpers\Pinnacle;

class SendSms
{

    protected static $driver;

    static function sendSms($mobile, $smsBody, $date = '', $msgType = '') {
        
        self::$driver = config('sms.driver');

        if(self::$driver == 'ibulk') {
            return self::sendSmsViaIbulk($mobile, $smsBody, $date, $msgType); 
        } elseif(self::$driver == 'msg91') { 
            return self::sendSmsViaMsg91($mobile, $smsBody, $date, $msgType);
        } elseif(self::$driver == 'netcore') {
            return Netcore::sendSmsViaNetcore($mobile, $smsBody, $date, $msgType);
        } elseif(self::$driver == 'pinnacle') {
            return Pinnacle::sendSmsViaPinnacle($mobile, $smsBody, $date, $msgType);
        }       

    }

    static function sendSmsViaIbulk($mobile, $smsBody, $date = '', $msgType = '') 
    {

        $msgDate = '';
        
        if(!empty($date)) {
            $msgDate = '&scheduledDate=' . $date;
        }

        $createUrl = config('sms.ibulk.url') 
            . 'user=' . config('sms.ibulk.user')
            . '&pwd=' . config('sms.ibulk.pwd')
            . '&senderid=' . config('sms.ibulk.sender_id')
            . '&mobileno=' . $mobile
            . '&msgtext=' . urlencode($smsBody)
            . $msgDate;
       
        if(!empty($msgType)){
            $createUrl .= '&smstype=' . 13;
        }

        return $sent = self::callApi($createUrl);
    }

    static function callApi($url) {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
    
        if (empty($curl_scraped_page)) {
            return false;
        }
        return true;
    }


    static function sendSmsViaMsg91($mobile, $smsBody, $date = '', $msgType = '')
    {

        //Your message to send, Add URL encoding here.
        $message = urlencode($smsBody);

        //Prepare you post parameters
        $postData = array(
            'authkey' => config('sms.msg91.auth_key'),
            'mobiles' => $mobile,
            'message' => $message,
            'sender' => config('sms.msg91.sender_id'),
            'route' => config('sms.msg91.route')
        );
        //var_dump($postData);

        //API URL
        $url = config('sms.msg91.url');

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        //get response
        $output = curl_exec($ch);


        //Print error if any
        if(curl_errno($ch))
        {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);

        if (empty($output)) {
            return false;
        }
        return true;
    }

}	

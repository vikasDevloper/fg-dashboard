<?php

namespace Dashboard\Console\Commands;

use Illuminate\Console\Command;
use Dashboard\Classes\Helpers\Utility;
use Dashboard\Classes\Helpers\UrlShortener;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\NotificationSend;
use Dashboard\Helpers\SendSms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPromotionalSmsEoss.php extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotionalSmsEoss:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Coomon Sms for buyers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->launchDate = '23mar20';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
   public function handle() {

        //
        set_time_limit(0);

        $smsPurpose = 'sms_'.$this->launchDate.'_ordered';

        Log::info('Promotional SMS Eoss:: '.$smsPurpose.' Started');

        //$signature = config('sms.smallsignature.without_no');
        $signature = config('sms.signature.without_no');
        $site      = config('app.site_url');
        //$number     = config('app.support_no');
        $number     = config('sms.supportNo.support_no');
        $start      = 100*0;
        $limit      = 10;
        $totalUsers = 10;

        //$smsText = "Hi [NAME],\n\nDue to an overwhelming response to our latest collection, there might be a slight delay (2-3 days) in shipping your order."."\n\nThank you for your patience and understanding.\n\nPlease feel free to reach out to us on 8287-567-567 for any assistance.\n\n".$signature;

        //$smsText = "Just Arrived | 3 Summer Cotton Kurtas\n\nClick Now - [URL]\n\nEasy Exchanges & Returns\n\n".$signature."\n".$number;

        $smsText = "FARIDA GUPTA | ORDER UPDATE\nKindly note, due to COVID-19 scare, we will resume shipping all orders on 01.04.2020 as of now.\nStay safe.\n".$signature."\n".$number;

        $i = 0;

        // $users[0]['mobile']      = '9873621245';
        // $users[0]['firstname']   = 'Sanjay Singh';
        // $users[0]['customer_id'] = '1';
        // $users[0]['purpose']     = 'sms_'.$this->launchDate.'_20t';
        // $users[0]['city']        = 'XS,XL,XL';
        // //$users[0]['city'] = '';

        // $users[1]['mobile']      = '9818137346';
        // $users[1]['firstname']   = 'Sahil Gupta';
        // $users[1]['customer_id'] = '2';
        // $users[1]['purpose']     = 'sms_'.$this->launchDate.'_20pt';
        // $users[1]['city']        = 'M,M,M,M,M,M,M,M,M,M,M,S,M,M,M,M,M,M,M,XS,M,M,M,S,M';
        // //$users[1]['city'] = '';

        // $users[2]['mobile']      = '7533061241';
        // $users[2]['firstname']   = 'Komal Bhagat';
        // $users[2]['customer_id'] = '3';
        // $users[2]['purpose']     = 'sms_'.$this->launchDate.'_b_20pt';
        // $users[2]['city']        = 'XS,XL,XL,L,XS,XS,XS';
        // //$users[2]['city'] = '';

        // $users[3]['mobile']      = '7906077429';
        // $users[3]['firstname']   = 'Rajan';
        // $users[3]['customer_id'] = '4';
        // $users[3]['purpose']     = 'sms_'.$this->launchDate.'_b_6t';
        // $users[3]['city']        = 'XS,XL,XL';
        // //$users[3]['city'] = '';

        // $users[5]['mobile']      = '8800745258';
        // $users[5]['firstname']   = 'Sandeep';
        // $users[5]['customer_id'] = '6';
        // $users[5]['purpose']     = 'sms_'.$this->launchDate.'_2t';
        // $users[5]['city']        = 'XS,XL,XL,XL,XL,XL,XXL,XL,XL,XL,XXL';
        // //$users[5]['city'] = '';

        // $users[6]['mobile']      = '9999973755';
        // $users[6]['firstname']   = 'Nitin';
        // $users[6]['customer_id'] = '7';
        // $users[6]['purpose']     = 'sms_'.$this->launchDate.'_1t';
        // $users[6]['city']        = 'M,M,M,M,M,M,M,M,M,M,M,S,M,M,M,M,M,M,M,XS,M,M,M,S,M';
        // //$users[6]['city'] = '';

        // $users[8]['mobile']      = '9999060387';
        // $users[8]['firstname']   = 'Varsha';
        // $users[8]['customer_id'] = '9';
        // $users[8]['purpose']     = 'sms_'.$this->launchDate.'_1t';
        // $users[8]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
        // //$users[8]['city'] = '';

        // $users[9]['mobile']      = '9910067249';
        // $users[9]['firstname']   = 'Adnan';
        // $users[9]['customer_id'] = '10';
        // $users[9]['purpose']     = 'sms_'.$this->launchDate.'_11t';
        // $users[9]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';

        // $users[10]['mobile']      = '7428266467';
        // $users[10]['firstname']   = 'Sushant';
        // $users[10]['customer_id'] = '11';
        // $users[10]['purpose']     = 'sms_'.$this->launchDate.'_13t';
        // $users[10]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';

        $users[11]['mobile']      = '9045682529';
        $users[11]['firstname']   = 'Rajan';
        $users[11]['customer_id'] = '4';
        $users[11]['purpose']     = 'sms_'.$this->launchDate.'_ordered';
        $users[11]['city']        = 'XS,XL,XL';
        //$users[11]['city'] = '';

        // $users[12]['mobile']      = '8826065309';
        // $users[12]['firstname']   = 'Sandeep';
        // $users[12]['customer_id'] = '4';
        // $users[12]['purpose']     = 'sms_'.$this->launchDate.'_5t';
        // $users[12]['city']        = 'XS,XL,XL';
        // //$users[12]['city'] = '';

        $users[13]['mobile']      = '8076649281';
        $users[13]['firstname']   = 'Vikas';
        $users[13]['customer_id'] = '4';
        $users[13]['purpose']     = 'sms_'.$this->launchDate.'_ordered';
        $users[13]['city']        = 'XS,XL,XL';
        //$users[12]['city'] = '';

        $u                      = NotificationSend::getCustomersToSendSms($start, $limit, $smsPurpose);
        echo "\n\n".$totalUsers = count($u)." ";

        if ($totalUsers > 0) {
            $this->sendSms($users, $smsText);
            exit;// for Test Sms

            while ($totalUsers > 0) {

                $users = NotificationSend::getCustomersToSendSms($start, $limit, $smsPurpose);

                echo "\n\n".$totalUsers = count($users)." ";
                // echo "\n\n" . count($users) . "  ";

                if (!empty($users)) {

                    $this->sendSms($users, $smsText);
                    $i = $i+count($users);

                    if ($i >= 300) {
                        exit;
                    }
                }
            }
        }
    }

    public function sendSms($users, $smsBody) {

        $res     = '';
        $smsText = '';

        $unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();

        foreach ($users as $user) {

            // if ($user['purpose'] == 'sms_9may19_3t') {
            //  Log::info('3t please change sms');
            //  exit;
            // }

            $smsText = $smsBody;

            $mobile      = trim($user['mobile']);
            $mobileregex = "/^[6-9][0-9]{9}$/";

            if (preg_match($mobileregex, $mobile) == 0) {
                $user['status'] = -1;
                NotificationSend::updateMobileStatus($user);
                continue;
            } else if (strlen($mobile) != 10) {
                $user['status'] = -1;
                NotificationSend::updateMobileStatus($user);
                continue;
            } else if (in_array($mobile, $unsubscribedMobileUser)) {
                $user['status'] = -1;
                NotificationSend::updateMobileStatus($user);
                continue;
            }

            // if (strlen($mobile) == 11) {
            //  $mobile = substr($mobile, 1);
            // }

            // if (strlen($mobile) == 12) {
            //  $mobile = substr($mobile, 2);
            // }

            $name = '';

            if (!empty($user['firstname'])) {
                $name = ucfirst(strtolower(trim(explode(" ", $user['firstname'])[0])));
                if (strtolower($name) == 'unknown' || strtolower($name) == 'test') {
                    $name = '';
                }
            }

            // $name = 'Sanjay';
            echo $mobile." ";
            $url           = "";
            $generated_url = '';

            /********************For Customized SMS*********************/

            //$smsText = str_replace(array("[NAME]"), array($name), $smsText);

            //$res = SendSms::sendSms($mobile, $smsText);
            // if ($res) {
            //  $user['status'] = 1;
            // } else {
            //  $user['status'] = -1;
            // }

            // // if($mobile == '9873621245' || $mobile == '9818137346' || $mobile == '9716834689')
            // // {
            // //   continue;
            // // }

            // NotificationSend::updateMobileStatus($user);
            // continue;
            //exit;
            /********************For Customized SMS*********************/

            // $url = 'http://bit.ly/launch-13dec-nb';
            // if ($user['customer_id'] == 1) {

            //$sizeUrl      = explode('__', $user['purpose']);

            if (!empty($user['city'])) {
                //Log::error('Mobile::'.$mobile.' City::'.$user['city']);
                $size_array   = array_unique(explode(',', $user['city']));
                $sizeid_array = array();

                foreach ($size_array as $value) {

                    switch ($value) {
                        case 'XS':
                            $sizeid_array[] = 34;
                            break;

                        case 'S':
                            $sizeid_array[] = 32;
                            break;

                        case 'M':
                            $sizeid_array[] = 31;
                            break;

                        case 'L':
                            $sizeid_array[] = 30;
                            break;

                        case 'XL':
                            $sizeid_array[] = 33;
                            break;

                        case 'XXL':
                            $sizeid_array[] = 35;
                            break;

                        case '3XL':
                            $sizeid_array[] = 29;
                            break;
                    }
                }

                $sizeids         = implode(',', $sizeid_array);
                $sizein_campaign = implode('_', $size_array);
                // $generated_url   = '';

                //$sizeids = '';//for kaftans also show in all-products

                switch ($user['purpose']) {

                    case 'sms_'.$this->launchDate.'_b_20pt':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_20pt_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_20t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_20t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_19t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_19t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_18t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_18t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_17t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_17t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_16t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_16t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_15t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_15t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_14t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_14t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_13t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_13t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_12t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_12t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_11t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_11t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_10t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_10t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_9t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_9t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_8t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_8t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_7t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_7t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_6t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_6t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_5t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_5t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_4t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_4t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_3t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_3t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_2t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_2t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                    case 'sms_'.$this->launchDate.'_b_1t':

                        $generated_url = "https://www.faridagupta.com/all-products?size=".$sizeids."&dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_".$this->launchDate."_1t_".$sizein_campaign."&utm_location=-1&nofilter=1";

                        break;

                        // case 'sms_'.$this->launchDate.'_nb_last30':

                        //  $url = 'http://bit.ly/2VJ19Nd';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_last90-30':

                        //  $url = 'http://bit.ly/30CkZgR';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_last180-90':

                        //  $url = 'http://bit.ly/2wfk7Ry';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_last320-180':

                        //  $url = 'http://bit.ly/2WZmlAd';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_last640-320':

                        //  $url = 'http://bit.ly/2WnLQOp';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_ExDi':

                        //  $url = 'http://bit.ly/30Gu9Jt';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-noida':

                        //  $url = 'http://bit.ly/2Vq2pou';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-delhi':

                        //  $url = 'http://bit.ly/2LCoK2m';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-pune':

                        //  $url = 'http://bit.ly/2JG30Qr';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-bengaluru':

                        //  $url = 'http://bit.ly/2VqNmuw';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-chennai':

                        //  $url = 'http://bit.ly/2W6cvz0';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-hyderabad':

                        //  $url = 'http://bit.ly/2VjSpNu';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-gurugram':

                        //  $url = 'http://bit.ly/2Vq2pou';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-chandigarh':

                        //  $url = 'http://bit.ly/2YsOQGQ';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-vadodara':

                        //  $url = 'http://bit.ly/2HmOeeJ';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-surat':

                        //  $url = 'http://bit.ly/2Hh4Jdq';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-mumbai':

                        //  $url = 'http://bit.ly/2VjCTBj';

                        //  break;
                }

            } else {

                switch ($user['purpose']) {

                        // case 'sms_'.$this->launchDate.'_nb_last30':

                        //  $url = 'http://bit.ly/2VJ19Nd';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_last90-30':

                        //  $url = 'http://bit.ly/30CkZgR';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_last180-90':

                        //  $url = 'http://bit.ly/2wfk7Ry';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_last320-180':

                        //  $url = 'http://bit.ly/2WZmlAd';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_last640-320':

                        //  $url = 'http://bit.ly/2WnLQOp';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_ExDi':

                        //  $url = 'http://bit.ly/30Gu9Jt';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-noida':

                        //  $url = 'http://bit.ly/2Vq2pou';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-delhi':

                        //  $url = 'http://bit.ly/2LCoK2m';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-pune':

                        //  $url = 'http://bit.ly/2JG30Qr';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-bengaluru':

                        //  $url = 'http://bit.ly/2VqNmuw';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-chennai':

                        //  $url = 'http://bit.ly/2W6cvz0';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-hyderabad':

                        //  $url = 'http://bit.ly/2VjSpNu';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-gurugram':

                        //  $url = 'http://bit.ly/2Vq2pou';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-chandigarh':

                        //  $url = 'http://bit.ly/2YsOQGQ';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-vadodara':

                        //  $url = 'http://bit.ly/2HmOeeJ';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-surat':

                        //  $url = 'http://bit.ly/2Hh4Jdq';

                        //  break;

                        // case 'sms_'.$this->launchDate.'_nb_non-mumbai':

                        //  $url = 'http://bit.ly/2VjCTBj';

                        //  break;

                }

                // $generated_url = "https://www.faridagupta.com/all-products?dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_25apr19_nb_last30&utm_location=-1&nofilter=1";

                // if($user['purpose'] == 'sms_4jan_eoss_nb'){

                //  $generated_url = "https://www.faridagupta.com/fg-eoss?dir=desc&order=recentlyadded&utm_source=sms&utm_medium=cps&utm_campaign=sms_4jan_eoss_nb&utm_location=-1&nofilter=1";

                // } else{

                // }

            }

            /* get the short url */
            if ($generated_url != '') {
                //$url = Utility::get_bitly_short_url($generated_url, 'vaibhav15', 'R_eb5ff082a32747059c8bfb39223f0615');
                $url = json_decode(UrlShortener::get_fg_short_url($generated_url));
            }
            if(isset($url) && $url=="")
            {
              $urldeatils  =  json_decode($url);
              $status_code = $url->status_code;
            }
            
 
            $checkurl = 'http://fgurl.in';
            if ($url == 'RATE_LIMIT_EXCEEDED' || $url == '' || $url == 'UNKNOWN_ERROR') {

                Log::error('Mobile::'.$mobile.' URL::'.$url);
                if ($url != '') {
                    echo $url;
                    exit;
                }
                //sleep(5);
                continue;
            }

            if ($url->status_code == '400' || $url == '' || $url == 'UNKNOWN_ERROR') {

                Log::error('Mobile::'.$mobile.' URL::'.$url);
                if ($url != '') {
                    echo $url;
                    exit;
                }
                //sleep(5);
                continue;
            }

            if (!strstr($url->result, $checkurl)) {
                echo $url;
                exit;
            }

            $smsText = str_replace(array("[URL]"), array($url->result), $smsText);
            $smsText = str_replace(array("[NAME]"), array($name), $smsText);

             //echo $smsText."    ";
            // exit;

            $res = SendSms::sendSms($mobile, $smsText);
            // print_r($res);
            //  exit;
            if ($res) {
                $user['status'] = 1;
            } else {
                $user['status'] = -1;
            }

            // // if($mobile == '9873621245' || $mobile == '9818137346' || $mobile == '9716834689')
            // // {
            // //   continue;
            // // }
          
            NotificationSend::updateMobileStatus($user);
        }

        // Log::info('Promotional SMS:: Sent');

    }
}

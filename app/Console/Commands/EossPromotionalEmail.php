<?php

namespace Dashboard\Console\Commands;

use Dashboard\Classes\Helpers\Falconide;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\EossNotificationSend;
use Dashboard\Mail\SalePromotions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class EossPromotionalEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eossPromotionalEmail:send {file}';

    protected $launchDate;

    protected $subject;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send promotional mails to Farida Gupta Customer Base for Eoss';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->launchDate = '11dec19';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        //
        set_time_limit(0);
        $file       = $this->argument('file');
        $start      = 100*$file;
        $limit      = 10;
        $totalUsers = 10;

        //$mailPurpose = 'eoss_coupon_sms_email_'.$this->launchDate.'_nb';
        //$mailPurpose = 'eoss_coupon_sms_email_nb';
        $mailPurpose = 'eoss_coupon_email_nb';

        Log::info('Promotional Email:: '.$mailPurpose.' Started');

        // $data['tag']     = 'eoss-sale-22';

        //Buyer
        $this->subject       = "Our Year End Season Sale is live! Rs. 200/- off on first purchase!";
        $data['previewText'] = "Up to 30% off on ALL STYLES ";


//         $data['previewText'] = "Welcome to the FG Family!\n\nHere's a special welcome gift to get you started.\nGET
// \nRs 200\nOFF YOUR FIRST PURCHASE\n\nEnter Code XXXX at checkout\n\nRedeem Now!\n\nValid on cart value Rs 2,000-Rs 2,999. T&C Apply";

        // //Non Buyer
        // $this->subject       = "😍 Introducing Nausheen Collection";
        // $data['previewText'] = "Handcrafted styles in soothing soft cotton fabric, explore the Art of block prints.";

        $data['url'] = "https://goo.gl/N16BLE";
        //$data['template'] = 'emails.promotions.NewArrival_25Apl19_nb';
        $data['template'] = 'emails.promotions.13thDec_EOSS2020_NonBuyer';

        //$data['template']      = 'emails.promotions.eoss-sale-9jan';

        // $users[0]['email']       = 'sandeep@faridagupta.com';
        // $users[0]['firstname']   = 'Sandeep';
        // $users[0]['customer_id'] = '00001';
        // $users[0]['purpose']     = 'email_'.$this->launchDate.'_20t';
        // $users[0]['city']        = 'M,M,M,M,M,M,M,M,M,M,M,S,M,M,M,M,M,M,M,XS,M,M,M,S,M';
        // $users[0]['coupon_code'] = 'FG200VOWQR';

        // //$users[0]['city'] = '';

        // $users[1]['email']       = 'komal@faridagupta.com';
        // $users[1]['firstname']   = 'Komal';
        // $users[1]['customer_id'] = '11111';
        // $users[1]['purpose']     = 'email_'.$this->launchDate.'_20pt';
        // $users[1]['city']        = 'M,M,M,M,M,M,M,M,M,M,M,S';
        // $users[1]['coupon_code'] = 'FG200VOWQR';

        // //$users[1]['city'] = '';

        $users[8]['email'] = 'rajan@faridagupta.com';
        //$users[8]['email']       = 'rajan13215@gmail.com';
        $users[8]['firstname']   = 'Rajan';
        $users[8]['customer_id'] = '66666';
        $users[8]['purpose']     = 'email_'.$this->launchDate.'_1t';
        $users[8]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
        $users[8]['city'] = '';
        $users[8]['coupon_code'] = 'FG200VOWQR';


        // $users[2]['email']       = 'sahil@faridagupta.com';
        // $users[2]['firstname']   = 'Sahil';
        // $users[2]['customer_id'] = '2222';
        // $users[2]['purpose']     = 'email_'.$this->launchDate.'_19t';
        // $users[2]['city']        = 'XS,XL,XL';
        // $users[2]['coupon_code'] = 'FG200VOWQR';

        // //$users[2]['city'] = '';

        // $users[3]['email']       = 'monu@faridagupta.com';
        // $users[3]['firstname']   = 'Monsandeepu';
        // $users[3]['customer_id'] = '3333';
        // $users[3]['purpose']     = 'email_'.$this->launchDate.'_18t';
        // $users[3]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
        // $users[3]['coupon_code'] = 'FG200VOWQR';

        // //$users[3]['city'] = '';

        // // $users[4]['email']       = 'komalktr15@gmail.com';
        // // $users[4]['firstname']   = 'Komal';
        // // $users[4]['customer_id'] = '8888';
        // // $users[4]['purpose']     = 'email_'.$this->launchDate.'_13t';
        // // $users[4]['city']        = 'S,XL,XL,XL,XL,XL,XXL,XL,XL,XL,XXL';
        // // //$users[4]['city'] = '';

        // // $users[5]['email']       = 'nitin@faridagupta.com';
        // // $users[5]['firstname']   = 'Nitin';
        // // $users[5]['customer_id'] = '8888';
        // // $users[5]['purpose']     = 'email_'.$this->launchDate.'_11t';
        // // $users[5]['city']        = 'M,M,M,M,M,M,M,M,M,M,M,S,M,M,M,M,M,M,M,XS,M,M';
        // // //$users[5]['city'] = '';

        // $users[6]['email']       = 'sana@faridagupta.com';
        // $users[6]['firstname']   = 'Sana';
        // $users[6]['customer_id'] = '44444';
        // $users[6]['purpose']     = 'email_'.$this->launchDate.'_9t';
        // $users[6]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
        // $users[6]['coupon_code'] = 'FG200VOWQR';

        // //$users[6]['city'] = '';

        // $users[7]['email']       = 'sanjay@faridagupta.com';
        // $users[7]['firstname']   = 'Sanjay';
        // $users[7]['customer_id'] = '5555';
        // $users[7]['purpose']     = 'email_'.$this->launchDate.'_6t';
        // $users[7]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
        // //$users[7]['city'] = '';
        // $users[7]['coupon_code'] = 'FG200VOWQR';

        // $users[9]['email']       = 'ritu@faridagupta.com';
        // $users[9]['firstname']   = 'Ritu';
        // $users[9]['customer_id'] = '7777';
        // $users[9]['purpose']     = 'email_'.$this->launchDate.'_4t';
        // $users[9]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
        // //$users[9]['city'] = '';

        // $users[10]['email']       = 'adnan@faridagupta.com';
        // $users[10]['firstname']   = 'Adnan';
        // $users[10]['customer_id'] = '8888';
        // $users[10]['purpose']     = 'email_'.$this->launchDate.'_1t';
        // $users[10]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
        // //$users[10]['city'] = '';

        // $users[11]['email']       = 'sushant@faridagupta.com';
        // $users[11]['firstname']   = 'Sushant';
        // $users[11]['customer_id'] = '9999';
        // $users[11]['purpose']     = 'email_'.$this->launchDate.'_1t';
        // $users[11]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
        // //$users[11]['city'] = '';

        // $users[12]['email']       = 'shad@faridagupta.com';
        // $users[12]['firstname']   = 'Shad';
        // $users[12]['customer_id'] = '9999';
        // $users[12]['purpose']     = 'email_'.$this->launchDate.'_1t';
        // $users[12]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
        // //$users[11]['city'] = '';

        $users[13]['email']       = 'vikas@faridagupta.com';
        $users[13]['firstname']   = 'Vikas';
        $users[13]['customer_id'] = '9999';
        $users[13]['purpose']     = 'eoss_coupon_sms_email_'.$this->launchDate.'_nb';
        $users[13]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
        $users[13]['coupon_code'] = 'FG200VOWQR';
        //$users[11]['city'] = '';

        // $users[14]['email']       = 'vinay@faridagupta.com';
        // $users[14]['firstname']   = 'Vinay';
        // $users[14]['customer_id'] = '9999';
        // $users[14]['purpose']     = 'email_'.$this->launchDate.'_1t';
        // $users[14]['city']        = 'XS,XL,XL,L,XS,XS,S,M,S,XL';
        // //$users[11]['city'] = '';

        // $users[4]['email']     = 'web-reax8@mail-tester.com';
        // $users[4]['firstname'] = 'Mail Tester';

        $u = EossNotificationSend::getCustomers($start, $limit, $mailPurpose);

        echo "\n\n".$totalUsers = count($u);

        if ($totalUsers > 0) {

              $this->sendEmails($users, $data);
               //exit;//for test mails

            $i = 0;

            while ($totalUsers > 0) {

                $users                  = EossNotificationSend::getCustomers($start, $limit, $mailPurpose);
                echo "\n\n".$totalUsers = count($users);

                if (!empty($users)) {
                    $this->sendEmails($users, $data);
                    $i = $i+count($users);
                    if ($i >= 3000) {
                        exit;
                    }
                }

            }
        }

    }

    public function sendEmails($users, $data) {

        // get all the user who have unsubscribed
        $unsubscribedEmailUser = NewsletterSubscriber::getEmailUnsubscribers();

        foreach ($users as $user) {

            // if ($user['purpose'] == 'email_'.$this->launchDate.'_nb_ExDi') {
            //  exit;
            // }

            echo $data['to'] = strtolower(trim($user['email']));

            if (empty($data['to'])) {
                $user['status'] = -1;
                EossNotificationSend::updateStatus($user);
                continue;
            } else if (filter_var($data['to'], FILTER_VALIDATE_EMAIL) == false) {
                $user['status'] = -1;
                EossNotificationSend::updateStatus($user);
                continue;
            } else if (in_array($data['to'], $unsubscribedEmailUser)) {
                $user['status'] = -1;
                EossNotificationSend::updateStatus($user);
                continue;
            }

            $firstname         = explode(' ', $user['firstname']);
            $data['firstname'] = ucfirst(strtolower(trim($firstname['0'])));

            if (strtolower($data['firstname']) == 'unknown' || strtolower($data['firstname']) == 'test') {
                $data['firstname'] = '';
            }

            $data['subject'] = str_replace(array('[NAME]'), array($data['firstname']), $this->subject);
            //print_r($data); exit;
            $url     = '';
            $sizeids = '';
            $size_campaign = '';
            if (!empty($user['city'])) {
                //Log::error('Mobile::'.$mobile.' City::'.$user['city']);
                $size_array   = array_unique(explode(',', $user['city']));
                $sizeid_array = array();

                $sizeids         = implode(',', $sizeid_array);
                $sizein_campaign = implode('_', $size_array);
                $size_campaign   = '';
                 

            }  

            $email = '"'.$data['firstname'].'" <'.$data['to'].'>';

            $data['tag'] = $user['purpose'];
            //echo $user['coupon_code'];  
            if (config('mail.through') == 'Falconide') {

                $data['message'] = (string) View::make($data['template'])->with([
                        'subject'       => $data['subject'],
                        'previewText'   => $data['previewText'],
                        'firstname'     => array('adsada'),
                        'customer_id'   => $user['customer_id'],
                        'size_id'       => $sizeids,
                        'size_campaign' => $size_campaign,
                        'coupon'        => 'dadad'
                    ]);

                $data["replyTo"] = config('mail.reply-to.address');
                $data["from"]    = 'mailers@faridaguptaonline.com';//config('mail.from.address');

                $falconideObj = new Falconide();
                try {

                    $res = $falconideObj->createMail($data);
                    if ($res->message == 'SUCCESS') {
                        $user['status'] = 1;
                    } else {
                        print_r($res);
                        continue;
                    }
                    //$user['status'] = 1;
                } catch (\Exception $e) {
                    echo "mail not sent".$e->getMessage();
                    $user['status'] = -1;
                }

            } else {
                try {
                    Mail::to($data['to'])->send(new SalePromotions($data));
                    $user['status'] = 1;
                } catch (\Exception $e) {
                    echo "mail not sent".$e->getMessage();
                    $user['status'] = -1;
                }
            }

            EossNotificationSend::updateStatus($user);

        }

    }
}

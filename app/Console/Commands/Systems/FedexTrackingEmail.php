<?php

namespace Dashboard\Console\Commands\Systems;

use Illuminate\Console\Command;
use Dashboard\Classes\Helpers\DebugSoapClient;
use Dashboard\Data\Models\ShippingLabelGlobal;
use Dashboard\Data\Models\CoreEmailQueue;
use SoapHeader;
use SoapClient;
use DB;
use Illuminate\Support\Facades\Log;

class FedexTrackingEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fedextrackingemail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        
        $data = ShippingLabelGlobal::fedexAwbNumber();

        if(!empty($data)) {

            $msgBody1 = config('fedex.EmailContent');

            $status = '';
            $shipmentMessage = '';
            foreach ($data as $value) {

                $statusArray     = $this->getFedexStatus($value['TrackingNumber']);

                if(!empty($statusArray)) {
                    $status          = $statusArray->CompletedTrackDetails->TrackDetails->StatusDetail->Code;
                    if($status == 'HL') {

                        $shipmentMessage = lcfirst($statusArray->CompletedTrackDetails->TrackDetails->ServiceCommitMessage);

                        $replacetext     = ["<Name>", "<INCREMENTEDID>", "<SHIPMENTMESSAGE>"];
                        $replaceWith     = [$value['customer_firstname'], $value['increment_id'], $shipmentMessage];

                        $msgBody1Content = str_replace($replacetext, $replaceWith, $msgBody1);

                        $dt = CoreEmailQueue::getAthubEmail($value['entity_id']);
                        // print_r($dt);
                        // exit;
                        if(empty($dt)) {

                            $param             = array();
                            $customerEmailInfo = array();
                            $recipientInfo     = array();

                            $param['subject']           = str_replace('<ORDER_ID>', $value['increment_id'], config('fedex.Subject'));
                            $param['return_path_email'] = '';
                            $param['is_plain']          = '';
                            $param['from_email']        = config('mail.reply_to.address');
                            $param['from_name']         = config('mail.reply_to.name');
                            $param['reply_to']          = config('mail.reply_to.address');
                            $param['return_to']         = '';

                            
                            $customerEmailInfo['entity_id']          = $value['entity_id'];
                            $customerEmailInfo['entity_type']        = 'fedex info';
                            $customerEmailInfo['event_type']         = 'at_hub';
                            $customerEmailInfo['message_body']       = $msgBody1Content;
                            $customerEmailInfo['message_parameters'] = serialize($param);


                            $insertedCoreEmail = CoreEmailQueue::insertGetId($customerEmailInfo);

                            $recipientInfo['message_id']        = $insertedCoreEmail;
                            $recipientInfo['recipient_email']   = $value['customer_email'];
                            $recipientInfo['recipient_name']    = $value['customer_firstname'] .' '. $value['customer_lastname'];
                            $recipientInfo['email_type']        = 0;
                            DB::table('core_email_queue_recipients')->insert($recipientInfo);

                            Log::info('Order No. #'.$value['increment_id'].' On AT HUB emails sent.');  
                        } 
                    }
                }
            }
        }
    }

    public function getFedexStatus($trackingNumber) { 

            $publicPath     = public_path();
            $path_to_wsdl   = $publicPath."/TrackService_v14.wsdl";

            ini_set("soap.wsdl_cache_enabled", "0");
            $client = new SoapClient($path_to_wsdl, array(
            'trace'                             => 1
            ));

            $request['WebAuthenticationDetail'] = array(
                'ParentCredential' => array(
                    'Key'       => config('fedex.Key'),
                    'Password'  => config('fedex.Password')
                ),
                'UserCredential' => array(
                    'Key'       => config('fedex.Key'),
                    'Password'  => config('fedex.Password')
                )
            );

            $request['ClientDetail'] = array(
                'AccountNumber' => config('fedex.AccountNumber'),
                'MeterNumber'   => config('fedex.MeterNumber')
            );
            $request['TransactionDetail'] = array('CustomerTransactionId' => '*** Track Request using PHP ***');
            $request['Version'] = array(
                'ServiceId'     => 'trck', 
                'Major'         => '14', 
                'Intermediate'  => '0', 
                'Minor'         => '0'
            );
            $request['SelectionDetails'] = array(
                'PackageIdentifier' => array(
                    'Type' => 'TRACKING_NUMBER_OR_DOORTAG',
                    'Value' => $trackingNumber
                )
            );
            //echo '<pre>';
            //print_r(json_encode($request));
            
            try {
                if($this->setEndpoint('changeEndpoint')){
                    $newLocation = $client->__setLocation($this->setEndpoint('endpoint'));
                }
                
                $response = $client->track($request);
                $object = json_decode(json_encode($response), true);
                // echo '<pre>';
                // print_r($object);

                return $response;  
                //Log::info(json_encode($response));
                //$this->writeToLog($client);    // Write to log file   
            } catch (SoapFault $exception) {
                Log::error($exception);
                //$this->printFault($exception, $client);
            }

    }

    public function setEndpoint($var){
        if($var == 'changeEndpoint') Return false;
        if($var == 'endpoint') Return 'XXX';
    }
}

<?php

namespace Dashboard\Console\Commands\Systems;

use Illuminate\Console\Command;
use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Data\Models\ExhibitionsData;
use Dashboard\Helpers\SendSms;

use Illuminate\Support\Facades\Log;


class AttendeesRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendees:remove';

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
        $this->currentDate = date('Y-m-d');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $exiDates = ExhibitionsData::getExiDates($this->currentDate);
        //dd($exiDates);

       if(isset($exiDates) && !empty($exiDates)){
         foreach ($exiDates as $exiID => $value) {
           // $value['from'] = '2019-10-31';
           // $value['to']   = '2019-11-06';
            $removedAttendees = SmsUpdates::removeAttendees($value['from'], $value['to']);
            //$send_to = array('8076649281','9045682529','7533061241','9873621245','8010258215');
            $send_to = array('8076649281');
            
            if($removedAttendees > 0){
             $smsText = $removedAttendees . " attendees Removed";
            
             foreach ($send_to as $send) {
                SendSms::sendSms($send, $smsText);
             }
             echo "$removedAttendees attendees Removed";
             Log::info("$removedAttendees attendees Removed");
            }else {
             $smsText = "No attendees Found";
            foreach ($send_to as $send) {
             SendSms::sendSms($send, $smsText);
            }
            echo "$removedAttendees attendees Removed";
             Log::info("$removedAttendees attendees Removed");

            }
         }
       }
       else
       {
        echo "No Exhibitions Going";
       }
       exit;
    }
}

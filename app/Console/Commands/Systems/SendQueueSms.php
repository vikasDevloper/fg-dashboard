<?php

namespace Dashboard\Console\Commands\Systems;

use Dashboard\Data\Models\CoreSmsQueue;
use Dashboard\Helpers\SendSms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Dashboard\Classes\Helpers\Netcore;

class SendQueueSms extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'queueSms:send';

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
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		Log::info('Queue SMS :: Started');
		set_time_limit(0);
		//

		/*
		 * get all the sms not processed
		 **/

		$smsDatas = CoreSmsQueue::getUnprocessedSms();

		// dd($mailsData);

		echo "\n\n".$totalUsers = count($smsDatas).' ';

		if (!empty($smsDatas)) {

			foreach ($smsDatas as $smsData) {

				$data = array();
				echo $smsData['sms_mobile'].' ';

				$data['mobile'] = trim($smsData['sms_mobile']);

				$name = ucfirst(strtolower(trim(explode(" ", $smsData['sms_name'])[0])));

				if (strtolower($name) == 'unknown' || strtolower($name) == 'test') {
					$name = '';
				}

				$data['name'] = $name;

				$data['smsText'] = str_replace('[NAME]', $data['name'], $smsData['sms_description']);

				//$res = SendSms::sendSms($data['mobile'], $data['smsText']);
				$res = Netcore::sendSmsViaNetcore($data['mobile'], $data['smsText']);

				if ($res) {
					$data['status'] = 1;
					Log::info('Queue SMS '.$smsData['sms_id'].':: Sent');
				} else {
					$data['status'] = -1;
					Log::info('Queue SMS '.$smsData['sms_id'].':: Not Sent');
				}

				$update = CoreSmsQueue::where('sms_id', $smsData['sms_id'])
					->update(['sms_status' => $data['status']]);

				//exit;

			}

		}
	}
}

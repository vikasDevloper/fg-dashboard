<?php

namespace Dashboard\Console\Commands\Systems;

use Dashboard\Classes\Helpers\Falconide;
use Dashboard\Classes\Helpers\Pinnacle;

use Dashboard\Helpers\SendSms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SmsBalanceNotify extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'smsBalanceNotify:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send SMS if low balance on SMS service';

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

		set_time_limit(0);

		$faconide_obj = new Falconide();
		$balance_sms  = $faconide_obj->check_balance();

		$balance_sms = $balance_sms;

		$credit_remaining = $balance_sms->data->details->credit_remaining;

		Log::info('SMS low balance check:: Started');

		$balance = Pinnacle::checkBalancePinnacle();

		$send_to = array('7533061241', '9873621245', '8130106434', '7906077429', '8800745258', '9818137346', '8076649281');

		if ($balance < 50000) {

			$smsText = "Low Balance: ".$balance." Pinnacle remaining SMS balance";

			foreach ($send_to as $send) {
				SendSms::sendSms($send, $smsText);
			}

			Log::info('Low balance Pinnacle SMS :: Sent');
		}

		if (!empty($credit_remaining)) {
			if ($credit_remaining < 100000) {

				$smsText = "Low Balance: ".$credit_remaining." Falconide remaining Email balance";

				foreach ($send_to as $send) {
					SendSms::sendSms($send, $smsText);
				}

				Log::info('Low balance Falconide Email :: Sent');
			}
		}
	}
}

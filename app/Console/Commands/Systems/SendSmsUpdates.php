<?php

namespace Dashboard\Console\Commands\Systems;

use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Helpers\SendSms;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendSmsUpdates extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'smsUpdate:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send updates from sms updates table';

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
		Log::info('SMS Updates:: Started');

		$site       = config('app.site_url');
		$number     = config('app.support_no');
		$start      = 100*0;
		$limit      = 10;
		$totalUsers = 10;

		$i = 0;
        $k = 0;
		while ($totalUsers > 0) {

			$users = SmsUpdates::where('send', '0')
				->where('mobile', '!=', '')
				->orderBy('id', 'asc')
				->skip($start)	->take($limit)
				->get();

			echo "\n\n".$totalUsers = count($users)." ";
			// echo "\n\n" . count($users) . "  ";

			if (!empty($users)) {
				$this->sendSms($users);
				$i = $i+count($users);
				if ($i >= 3000) {
					exit;
				}
			}
          $k++;
          if($k==20)
          	break;
		}

		Log::info('SMS Updates:: Sent');
	}

	public function sendSms($users) {
		$res = '';

		foreach ($users as $user) {

			$mobile  = trim($user['mobile']);
			$smsBody = $user['sms_content'];

			if (strlen($mobile) == 11) {
				$mobile = substr($mobile, 1);
			}

			if (strlen($mobile) == 12) {
				$mobile = substr($mobile, 2);
			}

			// $name = 'Sanjay';
			echo $mobile." ";

			$res = SendSms::sendSms($mobile, $smsBody);

			if ($res) {
				$user['status'] = 1;
			} else {
				$user['status'] = -1;
			}

			$update = SmsUpdates::where('mobile', $user['mobile'])
				->update(['send' => $user['status'], 'send_time' => date('Y-m-d H:i:s')]);
		}

	}
}

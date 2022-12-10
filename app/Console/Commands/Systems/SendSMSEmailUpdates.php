<?php

namespace Dashboard\Console\Commands\Systems;

use Dashboard\Classes\Helpers\Falconide;
use Dashboard\Data\Models\EmailUpdates;
use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Helpers\SendSms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendSMSEmailUpdates extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'smsEmailUpdate:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send updates from sms updates and email updates table';

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
		Log::info('SMS/Email Updates :: Started');

		set_time_limit(0);
		$site   = config('app.site_url');
		$number = config('app.support_no');
		$file   = $this->argument('file');
		$start  = 100*$file;
		$limit  = 10;

		//SMS Updates
		$totalUsersSMS = 10;
		$i             = 0;

		while ($totalUsersSMS > 0) {

			$sms_users = SmsUpdates::where('send', '0')
				->where('mobile', '!=', '')
				->orderBy('id', 'asc')
				->skip($start)	->take($limit)
				->get();

			echo "\n\n".$totalUsersSMS = count($sms_users)." ";
			// echo "\n\n" . count($users) . "  ";

			if (!empty($sms_users)) {
				$this->sendSms($sms_users);
				$i = $i+count($sms_users);
				if ($i >= 2000) {
					exit;
				}
			}
		}

		//Email Updates
		$totalUsersEmail = 10;

		while ($totalUsersEmail > 0) {

			$j = 0;

			$email_users = EmailUpdates::where('send', '0')
				->where('email', '!=', '')
				->where('subject', '!=', '')
				->orderBy('id', 'asc')
				->skip($start)	->take($limit)
				->get();

			echo "\n\n".$totalUsersEmail = count($email_users);

			if (!empty($email_users)) {
				$this->sendEmails($email_users);
				//exit;
				$j = $j+count($email_users);
				if ($j >= 1000) {
					exit;
				}
			}
		}

		Log::info('SMS/Email Updates :: Sent');
	}

	public function sendEmails($users) {
		foreach ($users as $user) {

			echo $data['to'] = strtolower(trim($user['email']));

			$firstname         = explode(' ', $user['firstname']);
			$data['firstname'] = ucfirst(strtolower(trim($firstname['0'])));

			if (strtolower($data['firstname']) == 'unknown' || strtolower($data['firstname']) == 'test') {
				$data['firstname'] = '';
			}

			$data['subject'] = $user['subject'];
			$data['tag']     = $user['email_type'];
			$data['message'] = $user['email_content'];

			//if (config('mail.through') == 'Falconide') {

			$falconideObj = new Falconide();
			$msgSent      = $falconideObj->createMail($data);

			//var_dump($msgSent);

			if ($msgSent->message == 'SUCCESS') {
				$user['status'] = 1;
			} else {
				$user['status'] = -1;
			}
			//}

			$update = EmailUpdates::where('email', $user['email'])
				->where('email_type', $user['email_type'])
				->update(['send' => $user['status'], 'send_time' => date('Y-m-d H:i:s')]);
		}

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

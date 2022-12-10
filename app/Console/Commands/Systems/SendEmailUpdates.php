<?php

namespace Dashboard\Console\Commands\Systems;

use Dashboard\Classes\Helpers\Falconide;
use Dashboard\Data\Models\EmailUpdates;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendEmailUpdates extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'emailUpdate:send {file}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send updates from email updates table';

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
		//
		set_time_limit(0);
		Log::info('Email Updates:: Started');

		$file       = $this->argument('file');
		$start      = 100*$file;
		$limit      = 10;
		$totalUsers = 10;

		while ($totalUsers > 0) {

			$i = 0;

			$users = EmailUpdates::where('send', '0')
				->where('email', '!=', '')
				->where('subject', '!=', '')
				->orderBy('id', 'asc')
				->skip($start)	->take($limit)
				->get();

			echo "\n\n".$totalUsers = count($users);

			if (!empty($users)) {
				$this->sendEmails($users);
				//exit;
				$i = $i+count($users);
				if ($i >= 1000) {
					exit;
				}
			}
		}

		Log::info('Email Updates:: Sent');

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
			//Log::info('Exhibition Email:: '.var_dump($msgSent).' Check rate');
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
}

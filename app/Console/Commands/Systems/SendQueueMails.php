<?php

namespace Dashboard\Console\Commands\Systems;

use Dashboard\Classes\Helpers\Falconide;
use Dashboard\Data\Models\CoreEmailQueue;
use Dashboard\Data\Models\SalesFlatOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendQueueMails extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'queueMails:send';

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
		Log::info('Queue mails :: Started');
		set_time_limit(0);
		//

		/* create falconide object
		 *
		 **/

		$falconideObj = new Falconide();

		/*
		 * get all the mails not pocessed
		 **/

		$mailsData = CoreEmailQueue::getUnprocessedMails();

		//dd($mailsData);

		echo "\n\n".$totalUsers = count($mailsData);

		if (!empty($mailsData)) {

			foreach ($mailsData as $mailData) {

				$isCancel = "";

                $isCancel = SalesFlatOrder::checkCancelOrder($mailData['entity_id']);
                if(isset($isCancel) && $isCancel == 1){
                  $update = CoreEmailQueue::where('message_id', $mailData['message_id'])->update(['processed_at' => '0000-00-00 00:00:00', 'entity_type' => 'canceled']);
                  continue;
                }
 
				$data = array();

				echo $data['to'] = strtolower(trim($mailData['recipient_email']));

				$data['recipient_name'] = ucfirst(strtolower(trim($mailData['recipient_name'])));

				//$data['to'] = $data['recipient_name'] . '<' . $data['to'] . '>';

				$parameters = unserialize($mailData['message_parameters']);

				$data['subject'] = "Farida Gupta: Order Confirmation 😇";//$parameters['subject'];

				$data["replytoid"] = $parameters['from_email'];
				//$data["from"]      = $parameters['from_email'];
				$data["from"]     = config('mail.from.address_mailers');
				$data["fromname"] = $parameters['from_name'];
				$data["message"]  = $mailData['message_body'];
				$data["tag"]      = $mailData['event_type'];

				if ($falconideObj->createMail($data)) {
					echo $status        = 1;
					$mailData['status'] = date('Y-m-d H:i:s');
					Log::info('Queue Email '.$mailData['message_id'].':: Sent');
				} else {
					echo "mail not sent".$e->getMessage();
					$status             = -1;
					$mailData['status'] = '0000-00-00 00:00:00';
					Log::error('Queue Email '.$mailData['message_id'].':: Not Sent');
				}

				/*
				 * get all the mails not pocessed
				 **/

				$update = CoreEmailQueue::where('message_id', $mailData['message_id'])->update(['processed_at' => $mailData['status']]);

			}

		}

	}
}

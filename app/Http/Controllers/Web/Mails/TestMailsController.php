<?php

namespace Dashboard\Http\Controllers\Web\Mails;

use Dashboard\Http\Controllers\Controller;
use Dashboard\Classes\Helpers\Falconide;

use Illuminate\Support\Facades\View;

class TestMailsController extends Controller {
	/* test mails
	send and test mails controller
	 */
	static function sendMail() {

		echo $data['subject']  = "Just Launched | 3 Kurtas";
		$data['previewText']   = "Shop for a Cause: Flat 20% OFF";
		$data['url']           = "https://goo.gl/hhoUqA";
		$data['name']          = "FG";
		$data['utm_campaign']  = "All-Time-2T";
		$data['template']      = "emails.promotions.9April20_non_buyer_LaunchMailer"; 

		// $users = NotificationSend::getCustomers();

		// foreach ($users as $user) {

		 //    echo $data['to'] = $user['email'];

			// Mail::to($user['email'])->send(new SalePromotions($data));

		// 	NotificationSend::updateStatus($user);

		// }

		// $data['to'] = 'sneha@faridagupta.com';
                                                     
		$data['tag'] = "test";

			if (config('mail.through') == 'Falconide') {

				$data['message'] = (string) View::make($data['template'])->with([
						'subject'       => $data['subject'],
						'previewText' => $data['previewText'],
						'firstname'     => $data['name'],
						'size_id'       => '34,32',
		 				'size_campaign' => 'XS_S_3t',
					]);

				$data["replyTo"] = config('mail.reply-to.address');
				$data["from"]    = config('mail.from.address');

				$falconideObj = new Falconide();
				
				try {
					$res = array();
					
					// for sending mails to you

					$res = $falconideObj->createMail($data);
					
					if ($res->message == 'SUCCESS') {
						$user['status'] = 1;
					} else {
						print_r($res);
						continue;
					}

				} catch (\Exception $e) {
					echo "mail not sent".$e->getMessage();
					$user['status'] = -1;
				}
		    }

		return view($data['template'])
			->with([
				'subject'       => $data['subject'],
				'previewText' => $data['previewText'],
				'firstname'     => $data['name'],
				'utm_campaign'  => $data['utm_campaign'],
				'size_id'       => '34,32',
		 		'size_campaign' => 'XS_S_3t',
			]);

		// return view('emails.promotions.NewArrival_31Jan19_nb')
		// 	->with([
		// 		'subject'       => $data['subject'],
		// 		'mcPreviewText' => $data['mcPreviewText'],
		// 		'url'           => $data['url'],
		// 		'name'          => $data['name'],
		// 		'size_id'       => '34,32',
		// 		'size_campaign' => 'XS_S_3t',
		// 	]);
	}

}

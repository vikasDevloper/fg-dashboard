<?php

namespace Dashboard\Console\Commands\Systems;

use Dashboard\Data\Models\CustomerEntity;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Data\Models\SubscriberDiary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SubscribeSubscriberDiary extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'subscriberDiary:subscribe';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Subscribe customers from Subscriber Diary';

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

		Log::info('Subscribe customers from subscriber diary:: started');

		$diaries = SubscriberDiary::subscribeFromDiary();

		// $unsubscribedEmailUser  = NewsletterSubscriber::getEmailUnsubscribers();
		// $unsubscribedMobileUser = NewsletterSubscriber::getMobileUnsubscribers();

		$all_customers = CustomerEntity::getAllEmails();
		// print_r($all_customers);
		// exit;
		$subscriberEmails = NewsletterSubscriber::getAllEmails();

		$subscriberMobiles = NewsletterSubscriber::getAllMobiles();

		$i = 0;

		foreach ($diaries as $diary) {

			$mobileregex = "/^[6-9][0-9]{9}$/";

			$status = 0;

			$c_email  = strtolower(trim($diary['email']));
			$c_mobile = trim($diary['mobile']);

			$valid_mobile = 0;
			$valid_email  = 0;

			//Mobile validity check

			if (preg_match($mobileregex, $c_mobile) == 0) {

				if (strlen($c_mobile) == 12) {

					if (substr($c_mobile, 0, 2) == '91') {

						$c_mobile = substr($c_mobile, 2);

						if (preg_match($mobileregex, $c_mobile) != 0) {

							$valid_mobile = 1;
						}
					}
				}
			} else {

				$valid_mobile = 1;
			}

			//Email validity check
			if (filter_var($c_email, FILTER_VALIDATE_EMAIL) == true) {

				$valid_email = 1;
			}

			$data = array();

			if ((empty($c_email) && empty($c_mobile)) || ($valid_email == 0 && $valid_mobile == 0)) {

				$status = -1;
				$this->updateSubscriberDiary($c_email, $status);
				continue;

			} elseif (in_array($c_email, $all_customers)) {

				$status = -1;
				$this->updateSubscriberDiary($c_email, $status);
				continue;

			} elseif (in_array($c_email, $subscriberEmails) || in_array($c_mobile, $subscriberMobiles)) {

				if (in_array($c_email, $subscriberEmails) && in_array($c_mobile, $subscriberMobiles)) {

					$status = -1;
					$this->updateSubscriberDiary($c_email, $status);
					continue;

				} elseif (!in_array($c_email, $subscriberEmails)) {

					if ($valid_email == 1) {

						//Insert Email ID

						$data['subscriber_email']  = $c_email;
						$data['source']            = $diary['source'];
						$data['subscriber_status'] = 1;
						$status                    = 2;
					}

				} elseif (!in_array($c_mobile, $subscriberMobiles)) {

					if ($valid_mobile == 1) {

						//Insert mobile number

						$data['mobile']            = $c_mobile;
						$data['source']            = $diary['source'];
						$data['mobile_sub_status'] = 1;
						$status                    = 3;
					}
				}

			} else {
				//Insert Email ID & mobile number

				$data = array();

				if ($valid_email == 1 && $valid_mobile == 1) {

					$data['subscriber_email']  = $c_email;
					$data['subscriber_status'] = 1;
					$data['mobile']            = $c_mobile;
					$data['mobile_sub_status'] = 1;
					$data['source']            = $diary['source'];
					$data['subscriber_status'] = 1;
					$status                    = 1;

				} else {

					if ($valid_email == 1) {

						$data['subscriber_email']  = $c_email;
						$data['subscriber_status'] = 1;
						$data['source']            = $diary['source'];
						$data['subscriber_status'] = 1;
						$status                    = 2;

					} elseif ($valid_mobile == 1) {

						$data['mobile']            = $c_mobile;
						$data['mobile_sub_status'] = 1;
						$data['source']            = $diary['source'];
						$data['subscriber_status'] = 1;
						$status                    = 3;

					} else {

						$status = -1;
						$this->updateSubscriberDiary($c_email, $status);
						continue;
					}
				}
			}

			if (!empty($data)) {
				$insertData = NewsletterSubscriber::insert($data);

				if ($insertData) {
					$i++;
					unset($data);
				}
			}

			if (!empty($status)) {
				$this->updateSubscriberDiary($c_email, $status);
			}

		}

		Log::info('Customers from subscriber diary:: '.$i.' Subscribed');

	}

	public function updateSubscriberDiary($email, $status) {

		SubscriberDiary::whereRaw("email = '".$email."'")->update(['subscribed' => $status]);
		$status = 0;
	}

}

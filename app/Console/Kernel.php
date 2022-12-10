<?php

namespace Dashboard\Console;

use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		//

		Commands\Users\TopSellingPromotions::class ,
		Commands\Users\PromoteFgSteal::class ,
		Commands\Users\CompleteTheLook::class ,
		Commands\Users\AttendeesNonattendees::class ,
		Commands\Users\CityCustomers::class ,
		Commands\Users\ZeroTimeCustomersEmail::class ,
		Commands\Users\OneTimeCustomersEmail::class ,
		Commands\Users\TwoTimeCustomersEmail::class ,
		Commands\Users\ThreeTimeCustomersEmail::class ,
		Commands\Users\ZeroTimeCustomersSms::class ,
		Commands\Users\OneTimeCustomersSms::class ,
		Commands\Users\TwoTimeCustomersSms::class ,
		Commands\Users\ThreeTimeCustomersSms::class ,
		Commands\Users\ZeroTimeCustomers30daysEmail::class ,
		Commands\Users\OneTimeCustomers30daysEmail::class ,
		Commands\Users\TwoTimeCustomers30daysEmail::class ,
		Commands\Users\ThreeTimeCustomers30daysEmail::class ,
		Commands\Users\ZeroTimeCustomers30daysSms::class ,
		Commands\Users\OneTimeCustomers30daysSms::class ,
		Commands\Users\TwoTimeCustomers30daysSms::class ,
		Commands\Users\ThreeTimeCustomers30daysSms::class ,

		Commands\Users\ProductAvailableNotify::class ,

		/** Commands to send Notification to users
		 *
		 */
		Commands\Systems\ClearNotification::class ,
		Commands\Systems\SendSmsUpdates::class ,
		Commands\Systems\SendEmailUpdates::class ,
		Commands\Systems\SendSMSEmailUpdates::class ,
		Commands\Systems\SendQueueMails::class ,
		Commands\Systems\SendQueueSms::class ,
		Commands\Systems\PendingPayments::class ,
		Commands\SendPromotionalEmails::class ,
		Commands\SendPromotionalEmailSub::class ,
		Commands\SendPromotionalEmails1::class ,
		Commands\SendPromotionalSms::class ,
		Commands\SendPromotionalSms1::class ,
		Commands\Systems\SmsBalanceNotify::class ,
		Commands\Systems\RtoOrderMail::class ,
		Commands\Systems\ProductStatusEnable::class ,
		Commands\Systems\SubscribeSubscriberDiary::class ,
		Commands\Systems\ShippingPincodeUpdate::class ,
		Commands\Systems\AffiliateReport::class ,

		Commands\CreateInvoice::class ,
		Commands\CreateCreditMemo::class ,
		Commands\ProductRemove::class ,
		Commands\Systems\FedexTrackingEmail::class ,
		Commands\Systems\AttendeesRemove::class ,
		Commands\EossPromotionalSms::class ,
		Commands\EossPromotionalEmail::class ,
		Commands\EosstrigresEmail::class ,
		Commands\EossPromotionalSms::class ,
		Commands\SendCovidPurchaserSms::class ,
		Commands\SendCovidPurchaserEmails::class ,


		Commands\SaveNotificationLog::class,

	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule) {

		//To notify customer who have subscribed for a particular size of a product when available send SMS
		try {
			// $schedule->command('productAvailableNotify:create')->twiceDaily(11, 17)->withoutOverlapping();
			$schedule->command('productAvailableNotify:create')->cron('0 11,15,17,18 * * *')->withoutOverlapping();
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		// //Notifation clear
		// try {
		// 	$schedule->command('notifications:clear')->dailyAt('18:40')->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		//Pinnacle service check balance
		try {
			$schedule->command('smsBalanceNotify:send')->dailyAt('17:00')->withoutOverlapping();
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		// //Complete the look
		// try {
		// 	$schedule->command('completeTheLookPromotions:create')->dailyAt('12:00')->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		//RTO Order List Email
		try {
			$schedule->command('rtoOrderMail:send')->dailyAt('16:00')->withoutOverlapping();
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		//Pincode update Status change
		try {
			$schedule->command('shippingPincode:update')->hourly()->between('2:00', '6:00')->withoutOverlapping();
			//$schedule->command('shippingPincode:update')->dailyAt('23:00')->withoutOverlapping();
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		// //Product Stock Status, Product Status change
		// try {
		// 	$schedule->command('productstatus:enable')->everyTenMinutes()->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		//Change ownership log folder
		try {
			$schedule->exec('chown -R www-data:www-data /home/farida/Backend/storage/logs')->dailyAt('06:20')->withoutOverlapping();
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		//Pending payment update
		try {
			$schedule->command('pendingPayments:update')->everyThirtyMinutes()->between('4:00', '23:00')->withoutOverlapping();
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		//Send queue SMS
		try {
			$schedule->command('queueSms:send')->everyFiveMinutes()->withoutOverlapping();
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		// //Send SMS updates
		// try {
		// 	$schedule->command('smsUpdate:send')
		// 	         ->everyFiveMinutes()
		// 	         ->between('08:00', '18:40')
		// 	         ->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		// // //Send Email updates
		// try {
		// 	$schedule->command('emailUpdate:send 0')
		// 	         ->everyTenMinutes()
		// 	         ->between('08:00', '18:00')
		// 	         ->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		// // //Send promotional SMS for subscriber
		// try {
		// 	$schedule->command('promotionalSms1:send')
		// 	         //->everyFifteenMinutes()
		// 	         ->cron('*/10 * * * *')
		// 	         ->between('10:00', '18:00')
		// 	         ->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		//Send queue Emails
		// try {
		// 	$schedule->command('queueMails:send')->everyTenMinutes()->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		//Send promotional SMS for buyer
		try {
			$schedule->command('promotionalSms:send')
			          //->everyFifteenMinutes()
			         ->cron('*/15 * * * *')
			         ->between('10:00', '18:00')
			         ->withoutOverlapping();
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		// // //Promote FG steal
		// // try {
		// //     $schedule->command('promoteFgSteal:create')->weekly()->fridays()->at('6:30');
		// // } catch (Exception $e) {
		// //     $this->exceptions->report($e);
		// // }

		//Send promotional emails buyer
		try {
			$schedule->command('promotionalEmail:send 0')
			//->everyFifteenMinutes()
		    ->cron('*/15 * * * *')
			//->everyThirtyMinutes()
			->between('10:00', '18:00')
			->withoutOverlapping();
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		
		
  //       	////	Send promotional emails subscriber
		// try {
		// 	$schedule->command('promotionalEmailSub:send 0')
  //           //->everyFifteenMinutes()
		// 	 ->cron('*/10 * * * *')
		// 	->between('10:00', '18:00')
		// 	->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		// //Send promotional emails for Global Customer
		// try {
		// 	$schedule->command('promotionalEmail1:send 0')->dailyAt('18:58')->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		//Send HL status email
		try {
			$schedule->command('fedextrackingemail:send')->dailyAt('00:30')->withoutOverlapping();
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		// //Subscribe customer from subscriber diary
		// try {
		// 	$schedule->command('subscriberDiary:subscribe')->everyThirtyMinutes()->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		/*
		 * send all the orders and updated order mails to users and
		 * care@faridagupta.com runs every minutes and process all the non processed mails.
		 **/

		//private $filePath  = 'storage/'
		/*
		 * Create top selling SMS at 6 AM on friday
		 *
		 **/

		/*
		 * Start sending all updates SMSes from 11 AM to 17 PM
		 *
		//  **/

		// $schedule->command('promotionalEmail:send 1')
		//           ->weekly()->mondays()->at('10:00')
		//           ->withoutOverlapping();

		// $schedule->command('promotionalEmail:send 2')
		//           ->weekly()->mondays()->at('10:00')
		//           ->withoutOverlapping();

		// pdf Invoice Generate

		// try {
		// 	$schedule->command('pdfInvoice:create')->dailyAt('01:30')->withoutOverlapping();
		// 	$schedule->command('pdfInvoice:create')->dailyAt('12:00')->withoutOverlapping();
		// 	$schedule->command('pdfInvoice:create')->dailyAt('16:00')->withoutOverlapping();

		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		// try {
		// 	$schedule->command('pdfInvoice:create')->dailyAt('16:00')->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		// command for creation of credit memo

		try {
			$schedule->command('creditMemo:create')->dailyAt('16:00')->withoutOverlapping();
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		// command for remove attendees from exhibition

		// try {
		// 	$schedule->command('attendees:remove')->dailyAt('00:05')->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }
		// // command for remove launch product after launch

		try {
			$schedule->command('product:remove')->weekly()->thursdays()->at('17:00');
		} catch (Exception $e) {
			$this->exceptions->report($e);
		}

		//// EoSS SMS for Non buyers
		// try {
		// 	$schedule->command('eosspromotionalsms1:send')->everyTenMinutes()->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		// EoSS Email for Non buyers

		// try {
		// 	$schedule->command('eossPromotionalEmail:send 0')->everyTenMinutes()->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }

		// try {
		// 	$schedule->command('eossTriggerEmail:send 0')->everyMinute()->withoutOverlapping();
		// } catch (Exception $e) {
		// 	$this->exceptions->report($e);
		// }
	}

	/**
	 * Register the Closure based commands for the application.
	 *
	 * @return void
	 */
	protected function commands() {
		require base_path('routes/console.php');
	}
}

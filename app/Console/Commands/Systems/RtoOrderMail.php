<?php

namespace Dashboard\Console\Commands\Systems;

use Carbon\Carbon;
use Dashboard\Classes\Helpers\Falconide;
use Dashboard\Data\Models\SalesFlatCreditMemoGrid;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;

class RtoOrderMail extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'rtoOrderMail:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Every Day RTO Order List Send on E-Mail ';

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

		$email_address = array('zeba@faridagupta.com', 'mansi@faridagupta.com', 'anand@faridagupta.com', 'varsha@faridagupta.com', 'dixit@faridagupta.com');

		$falconideObj = new Falconide();
		$mytime       = Carbon::now()->format('Y-m-d');
		$maildata     = array();
		$emailBody    = SalesFlatCreditMemoGrid::rtoData($mytime);

		$maildata['to']             = implode(',', $email_address);
		$maildata["recipient_name"] = "care";
		$maildata["subject"]        = "List of RTOs Created Today";
		$maildata["replytoid"]      = "care@faridagupta.com";
		$maildata["from"]           = config('mail.from.address_mailers');
		$maildata["message"]        = $emailBody;
		$maildata["tag"]            = 'rto_list';

		if ($falconideObj->createMail($maildata)) {
			$status = 1;
			Log::info('RTO Order List :: Sent');
		} else {
			$status = 0;
			Log::error('RTO Order List :: Not Sent');
		}

	}

}

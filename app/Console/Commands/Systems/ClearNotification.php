<?php

namespace Dashboard\Console\Commands\Systems;

use Illuminate\Console\Command;

use Dashboard\Data\Models\SmsUpdates;
use Dashboard\Data\Models\SmsUpdatesLog;
use Dashboard\Data\Models\EmailUpdates;
use Illuminate\Support\Facades\Log;

class ClearNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:clear';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('Clear Notification:: Started');
        //
        /**
          *  before adding new clear old updates.
          */
        $this->clearSmsUpdates();

        /**
          *  before adding new clear old updates.
          */
        $this->clearEmailUpdates();
    }

    /*
     *  truncate Sms Update table
     */

    public function clearSmsUpdates() {

        SmsUpdates::insertInNotificationLog();
        SmsUpdates::truncate();
        Log::info('Clear Notification:: SMS Updates empty');

    }

    /*
     *  truncate Email Update table
     */

    public function clearEmailUpdates() {

        EmailUpdates::insertInNotificationLog();
        EmailUpdates::truncate();
        Log::info('Clear Notification:: Email Updates empty');
    }
    
    /*
     *  Empty the Notifictaion table table
     */

    // public function clearNotifications() {

    //     NotificationSend::insertSentEmailsToNotificationLog();
    //     NotificationSend::insertSentEmailsToNotificationLog();
    //     EmailUpdates::truncate();

    // }

}

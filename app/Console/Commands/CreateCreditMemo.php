<?php

namespace Dashboard\Console\Commands;

use Illuminate\Console\Command;
use Dashboard\Http\Controllers\Web\Dashboard\CreditMemoController;
use Illuminate\Support\Facades\Log;


class CreateCreditMemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'creditMemo:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command for create Credit Memo';

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
        Log::useFiles(storage_path().'/logs/creditmemo.log');
        Log::info('Credit Memo Start');
        $controller = new CreditMemoController();
         $controller->generateCrMemo();
         Log::info('Credit Memo End');
        //echo exec('date');
    }
}

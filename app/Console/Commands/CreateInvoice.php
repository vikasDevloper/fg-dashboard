<?php

namespace Dashboard\Console\Commands;

use Illuminate\Console\Command;
use Dashboard\Http\Controllers\Web\Dashboard\PdfGenerateController;
use Illuminate\Support\Facades\Log;


class CreateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdfInvoice:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command for create Invoice';

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
        Log::useFiles(storage_path().'/logs/invoice.log');
        Log::info('PDF Invoice Start');
        $controller = new PdfGenerateController();
         $controller->pdfview();
         Log::info('PDF Invoice End');
        //echo exec('date');
    }
}

<?php

namespace Dashboard\Console\Commands\Systems;

use Dashboard\Data\Models\CataloginventoryStockStatus;
use Dashboard\Data\Models\CatalogProductEntityInt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProductStatusEnable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */ 
    protected $signature = 'productstatus:enable'; // Product Enable Disable Command

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product Enable Disable Through Cron';

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
        Log::info('Product Status Enable/Disable :: Started');
        if(CataloginventoryStockStatus::ProductStockInformation()){
            Log::info('Product Set Status :: Enable');
        }
        

        if(CataloginventoryStockStatus::ProductDisableInformation()){
            Log::info('Product Set Status :: Disable'); 
        }
               
    }
}

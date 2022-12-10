<?php

namespace Dashboard\Console\Commands;

use Illuminate\Console\Command;
use Dashboard\Data\Models\ManfProductLaunch;
use Illuminate\Support\Facades\Log;

class ProductRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Product from Manufacturing Product';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->launchDate = date("Y-m-d");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
       $updateStatus = ManfProductLaunch::updateStatus($this->launchDate);
       if($updateStatus > 0){
        echo "$updateStatus Product Removed Successfully \n";
        Log::info("$updateStatus Product Launch Removed Successfully");
       } else{
        echo "Product Not Removed \n";
        Log::info('Product Not Removed');
       } 

    }
}

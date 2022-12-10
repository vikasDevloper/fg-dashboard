<?php

namespace Dashboard\Console\Commands\Systems;

use Illuminate\Console\Command;
use Dashboard\Data\Models\ShippingPincodeInfo;
use Illuminate\Support\Facades\Log;

class ShippingPincodeUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shippingPincode:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pincode Update Status';

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
        
        $data            = ShippingPincodeInfo::getPincodeList();  
      Log::useFiles(storage_path().'/logs/shippingPincode.log');
        foreach($data as $value):            
            if($value['pincode'] != '')   
            Log::info('Get Pincode:: '.$value['pincode'].' Started');             
            $bluedart_status = ShippingPincodeInfo::getPincodeBludeartStatus($value['pincode']);
        endforeach;
    }
}

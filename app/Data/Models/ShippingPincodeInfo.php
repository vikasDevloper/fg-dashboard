<?php

namespace Dashboard\Data\Models;
use Dashboard\Classes\Helpers\DebugSoapClient;
use Illuminate\Database\Eloquent\Model;
use SoapHeader;
use Dashboard\Data\Models\ShippingPincodeInfo;

class ShippingPincodeInfo extends Model
{
       
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'shipping_pincode_info';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;

	static function getPincodeList(){

		$pincode_list = ShippingPincodeInfo::SelectRaw('pincode')
						->where('provider_id', '1')
                        ->limit(20)
                        ->orderBy('id', 'desc')                     
						->get();		

		return $pincode_list->toArray();

	}

	static function updatePincodeStatus($provider_id,$pincode,$status,$delivery_type){

        /*

        use Dashboard\Data\Models\ShippingPincodeInfo;

        $data            = ShippingPincodeInfo::getPincodeList();

        foreach($data as $value):            
            if($value['pincode'] != '')
            $bluedart_status = ShippingPincodeInfo::getPincodeBludeartStatus($value['pincode']);
        endforeach;

        */

		$pincode_update = ShippingPincodeInfo::whereRaw("provider_id = '".$provider_id."'" )
						->whereRaw("pincode = '".$pincode."'" )
						->update(array('status' => $status, 'delivery_type' => $delivery_type));
        
	}

	static function getPincodeBludeartStatus($pincode = false){

		  $soap = new DebugSoapClient('http://netconnect.bluedart.com/Ver1.8/ShippingAPI/Finder/ServiceFinderQuery.svc?wsdl',
            array(
            'trace'         => 1,  
            'style'         => SOAP_DOCUMENT,
            'use'           => SOAP_LITERAL,
            'soap_version'  => SOAP_1_2
            ));
            
            $soap->__setLocation("http://netconnect.bluedart.com/Ver1.8/ShippingAPI/Finder/ServiceFinderQuery.svc");
            
            $soap->sendRequest  = true;
            $soap->printRequest = false;
            $soap->formatXML    = true;
                        
            $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IServiceFinderQuery/GetServicesforPincode',true);
            $soap->__setSoapHeaders($actionHeader);                 
                        
            $params = array('pinCode' => $pincode,
                     'profile' => 
                     array(
                        'Api_type' 		=> 'S',
                        'Area' 			=> '',
                        'Customercode' 	=> '',
                        'IsAdmin' 		=> '',
                        'LicenceKey' 	=> 'ddc55234e7d6e404d98bedfe00271ae3',
                        'LoginID' 		=> 'DL252895',
                        'Password' 		=> '',
                        'Version' 		=> '1.9')
                        );
                                  
            $result = $soap->__soapCall('GetServicesforPincode',array($params)); 
            
            $IsError 				= $result->GetServicesforPincodeResult->IsError;
            $limit      			= $result->GetServicesforPincodeResult->AirValueLimit;

            $eTailCODAirInbound 	=  $result->GetServicesforPincodeResult->eTailCODAirInbound;
            $eTailCODAirOutbound 	=  $result->GetServicesforPincodeResult->eTailCODAirOutbound;

            $eTailPrePaidAirInbound =  $result->GetServicesforPincodeResult->eTailPrePaidAirInbound;
            $eTailPrePaidAirOutound =  $result->GetServicesforPincodeResult->eTailPrePaidAirOutound;

            $provider_id 	= 1; 


         if (!$IsError) :
            switch ($eTailPrePaidAirInbound && $eTailPrePaidAirOutound && $eTailCODAirInbound &&$eTailCODAirOutbound && $limit) {

            	case ($eTailPrePaidAirInbound == 'Yes' && $eTailPrePaidAirOutound == 'Yes' && $limit > 0):

            		$status 	 	= 1;
	            	$delivery_type 	= 2;
	            	$pincode_update = ShippingPincodeInfo::updatePincodeStatus($provider_id,$pincode,$status,$delivery_type);

            		break;

            	case ($eTailCODAirInbound == 'Yes' && $eTailCODAirOutbound == 'Yes' && $limit > 0):

            		$status 	 	= 1;
	            	$delivery_type 	= 1;
	            	$pincode_update = ShippingPincodeInfo::updatePincodeStatus($provider_id,$pincode,$status,$delivery_type); 

            		break;

            	// case ($eTailCODAirInbound == 'No' && $eTailCODAirOutbound == 'No' && $limit > 0 && $eTailPrePaidAirInbound == 'No' && $eTailPrePaidAirOutound == 'No'):
            		
            	// 	$status 	 	= 0;
	            // 	$delivery_type 	= 1;
	            // 	$pincode_update = ShippingPincodeInfo::updatePincodeStatus($provider_id,$pincode,$status,$delivery_type);

            	// 	break;
            	
            	default:
            		$status 	 	= 0;
	            	$delivery_type 	= 1;
	            	$pincode_update = ShippingPincodeInfo::updatePincodeStatus($provider_id,$pincode,$status,$delivery_type); 

            	break;
            }

        else:
        	$status 	 	= 0;
        	$delivery_type 	= 1;
        	$pincode_update = ShippingPincodeInfo::updatePincodeStatus($provider_id,$pincode,$status,$delivery_type); 
        endif;

            return $result;

	}


}

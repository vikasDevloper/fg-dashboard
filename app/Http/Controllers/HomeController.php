<?php

namespace Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Analytics;
use Dashboard\Classes\Helpers\DebugSoapClient;
use Dashboard\Classes\Helpers\KnowlarityService;
use SoapHeader;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function my_first_api(){

         
        $data= [
            'name'=>'Vikas',
            'mobile'=>'80872728',
            'email'=>'vikas@faridagupta.com',
            'status'=>0
        ];

        return  $data;
    }

    public function index(Request $request)
    {
        
        $pincode = $request->input('pincode');

        //echo $site_id = Analytics::getSiteIdByUrl('https://www.faridagupta.com');
        // $site_id = config('google-analytics.site_id');
        //$stats = Analytics::query($site_id, '7daysAgo', 'yesterday', 'ga:visits,ga:pageviews');
    
        // $stats = Analytics::query($site_id, '7daysAgo', 'yesterday', 'ga:sessions, ga:pageviews', array('dimensions'=>'ga:sessionDurationBucket'));

        $soap = new DebugSoapClient('http://netconnect.bluedart.com/Ver1.8/ShippingAPI/Finder/ServiceFinderQuery.svc?wsdl',
            array(
            'trace'                             => 1,  
            'style'                             => SOAP_DOCUMENT,
            'use'                                   => SOAP_LITERAL,
            'soap_version'              => SOAP_1_2
            ));
            
            $soap->__setLocation("http://netconnect.bluedart.com/Ver1.8/ShippingAPI/Finder/ServiceFinderQuery.svc");
            
            $soap->sendRequest = true;
            $soap->printRequest = false;
            $soap->formatXML = true;
            
            
            $actionHeader = new SoapHeader('http://www.w3.org/2005/08/addressing','Action','http://tempuri.org/IServiceFinderQuery/GetServicesforPincode',true);
            $soap->__setSoapHeaders($actionHeader);
        
                    #echo End of Soap1.2 (ws_Http_Version)
   

            $paramsLive = array('pinCode' => '110057',
                     'profile' => 
                     array(
                        'Api_type' => 'S',
                        'Area'=>'DEL',
                        'Customercode'=>'252895',
                        'IsAdmin'=>'',
                        'LicenceKey'=>'f2ebc5cf97f69390af0e9fe735d6ad10',
                        'LoginID'=>'JO334644',
                        'Password'=>'',
                        'Version'=>'1.8')
                        );
                        
            $params = array('pinCode' => $pincode,
                     'profile' => 
                     array(
                        'Api_type' => 'S',
                        'Area'=>'',
                        'Customercode'=>'',
                        'IsAdmin'=>'',
                        'LicenceKey'=>'ddc55234e7d6e404d98bedfe00271ae3',
                        'LoginID'=>'DL252895',
                        'Password'=>'',
                        'Version'=>'1.8')
                        );
                        
            #var_dump($params);
            #echo '<h2>Parameters</h2><pre>'; print_r($params); echo '</pre>';
            // Here I call my external function
            $result = $soap->__soapCall('GetServicesforPincode',array($params));
            echo "<pre>";
            print_r($result);

            echo $result->GetServicesforPincodeResult->ErrorMessage ;
            echo "<br>";
            echo $result->GetServicesforPincodeResult->PincodeDescription;
            echo "<br>";

        
        // echo "<pre>";
        // print_r($stats);
        
        //return view('home');
    
    }

    public function showCallLog(Request $request)
    {
        $getCallLog = KnowlarityService::getCallLogs($request);
    }    
}

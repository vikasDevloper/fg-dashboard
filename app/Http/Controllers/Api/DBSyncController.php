<?php

namespace Dashboard\Http\Controllers\Api;

use Illuminate\Http\Request;
use Dashboard\Http\Controllers\Controller;
use Dashboard\Data\Models\SalesFlatOrderAddress;
use Dashboard\Data\Models\OfflineCustomerEntity;
use Dashboard\Data\Models\NewsletterSubscriber;
use Dashboard\Classes\Helpers\Utility;
use Illuminate\Support\Facades\Validator;
use DB;

class DBSyncController extends Controller
{ 

	protected $preDate;

    public function __construct() {
        $this->preDate = date('Y-m-d h:m:s', strtotime(' -1 day'));
    }

	public function GetOnlineBuyer(){


		$results = DB::select( "SELECT SFO.entity_id, SFO.status, SFO.base_grand_total, SFO.order_currency_code, SFOA.customer_id, SFOA.region_id,SFOA.region, SFOA.street, SFOA.postcode, SFOA.lastname, SFOA.firstname, SFOA.city, SFOA.email,SFOA.country_id,SFOA.telephone as mobile, SFOA.created_at, SFO.updated_at, GROUP_CONCAT( if(INSTR(SFOI.name,'-')> 0 , SUBSTRING_INDEX(SFOI.name,'-',-1), '')) AS sizes,GROUP_CONCAT(SUBSTRING_INDEX(SFOI.name,'-',1)) AS name FROM `sales_flat_order_address` as SFOA INNER Join `sales_flat_order` as SFO ON SFO.entity_id = SFOA.parent_id inner join `sales_flat_order_item` as SFOI ON SFOI.order_id = SFO.entity_id where SFOA.address_type = 'shipping' and  SFOI.product_type = 'simple' group by SFOI.order_id  ");

            $results = Utility::encryptdata($results);

        	return $results;

	}	

    /**
	* city wise users of offline Users (exhibition)
	**/

      public function offlineUsers(){
        $results = DB::select( DB::raw("select OOD.`order_id`,OOD.customer_id, OOD.order_bill_number, OOD.exhibitions_id, OOD.order_total, OOD.order_qty, GROUP_CONCAT(OID.item_name) AS name,GROUP_CONCAT(OID.item_size) AS size, OCE.`mobile`,OCE.`email`,OCE.`name`,OCE.`city`, OCE.`city_id` , OOD.created_at from `offline_customer_entity`  as OCE left join `offline_order_details` as OOD on `OCE`.`entity_id` = `OOD`.`customer_id` inner join `offline_item_details` as OID on `OOD`.`order_bill_number` = `OID`.`bill_number` group by `OID`.`bill_number`") );        

        // $results = DB::select( DB::raw("select OCE.entity_id,  OCE.`mobile`,OCE.`email`,OCE.`name`,OCE.`city`, OCE.`city_id` , OCE.created_at from `offline_customer_entity` as OCE 
        //         group by `OCE`.`mobile`,`OCE`.`email` ") ); 
             
            $results = Utility::encryptdata($results);
             
             return $results;
    }

    /**
	* Users of online Users (Buyer of multiple orders)
	**/

    // public function onlineUsers(){
    // 	$results = DB::select(" SELECT SFO.entity_id, SFOA.parent_id, SFOA.customer_id, SFOA.region_id, SFOA.postcode, SFOA.lastname, SFOA.firstname, SFOA.city, SFOA.email,SFOA.country_id,SFOA.telephone, SFOA.created_at, SUBSTRING_INDEX(SFOI.name,'-',-1) AS sizes,SUBSTRING_INDEX(SFOI.name,'-',1) AS name FROM `sales_flat_order_address`   as SFOA INNER Join `sales_flat_order` as SFO ON SFO.entity_id = SFOA.parent_id inner join `sales_flat_order_item` as SFOI ON SFOI.order_id = SFO.entity_id and SFOI.product_type = 'simple'" );
       		
    //    		return $results;
    // }

    public function exhibition_cities(){
	    $results = DB::select(" SELECT city_id,city_name from exhibition_cities");
	    return $results;
    }

    public function newsletterSuscriber(){
    	$results = DB::select("SELECT NS.subscriber_id, NS.customer_id, NS.subscriber_email as email, NS.subscriber_cities,NS.city,NS.subscriber_name, NS.mobile, NS.country_code,NS.source,NS.mobile_sub_status,NS.subscriber_status, NS.created_at, NS.updated_at
			FROM `newsletter_subscriber` AS NS
			LEFT OUTER JOIN sales_flat_order_address AS SFO on telephone = mobile
			WHERE subscriber_status != 3
			AND mobile_sub_status != 2
			AND mobile != ''
			AND SFO.email is null AND SFO.telephone is null
			AND subscriber_email != ''"); 
            
            $results = Utility::encryptdata($results);

    		return $results;
    	//dd(json_encode($results)); 
    }

    public function updatedOnlineBuyer(){
     
     $pdate =  $this->preDate;
     $results = SalesFlatOrderAddress::updatedOnlineBuyers($pdate);
     $results = Utility::encryptdata($results);

     return $results; 

    }

	public function updatedOfflineBuyer(){
     
     $pdate =  $this->preDate;
     $results = OfflineCustomerEntity::updatedOfflineBuyers($pdate);
     $results = Utility::encryptdata($results);

     return $results; 

    }

    public function updatedNewsletterSuscriber(){
     $pdate   =  $this->preDate;
     $results = NewsletterSubscriber::updatedNewsletterSuscriber($pdate);
     $results = Utility::encryptdata($results);

     return $results; 
    }
 
    /**
	* city wise users of online, offline, and newsletter subscriber
	**/

	// public function cityWiseUsers($city_id, $city_like){      
     	// $results = DB::table('sales_flat_order_address')->get();
 //    	$results = DB::select( DB::raw("  SELECT telephone AS mobile, email,city FROM sales_flat_order_address WHERE city IN ('Huderabad', 'Hyderabad', 'Hyderadab', 'Hyderbad', 'hyderebad', 'Hydrabad') GROUP BY mobile, email UNION SELECT mobile, subscriber_email AS email FROM newsletter_subscriber WHERE FIND_IN_SET(subscriber_cities, 4) GROUP BY mobile, email UNION SELECT mobile, null FROM offline_customer_entity WHERE city_id = 4 OR city IN ('Hyderabad') GROUP BY mobile") );
 //    	// print_r(count($results));
 //      	dd(json_encode($results)); 
 //    }
    
}


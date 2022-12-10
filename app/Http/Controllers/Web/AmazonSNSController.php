<?php

namespace Dashboard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Dashboard\Http\Controllers\Controller;

use Dashboard\Data\Models\BouncedEmails;

use Log;
use Storage;

class AmazonSNSController extends Controller
{
    //
      // handle bounced emails
    public function handleBounces(Request $request)
    {

        // $bounce_simulator_email = 'bounce@simulator.amazonses.com';

        // get bounce message
      $payload = json_decode($request->getContent());

      Storage::disk('local')->append('bounce.log', $request->getContent());

      if(property_exists($payload, 'Type')) {
        
        if($payload->Type === "SubscriptionConfirmation") {
            // if we are verifying notification subscription in this Endpoint for the
            // first time then go the said URL to confirm
            $confirmation_url = curl_init($payload->SubscribeURL);
            curl_exec($confirmation_url);
        
        }

      } else {

	        // process bounces
	        // You have to set "subscription attribute to RAW" on endpoints from SNS console to get JSON message
	        $notificationType = $payload->notificationType;
	        $bounceType = $payload->bounce->bounceType;
	        $bounced_email = $payload->bounce->bouncedRecipients[0]->emailAddress; //get first email

	        $user = BouncedEmails::updateOrCreate(['email' => $bounced_email]);

	        // take action
	        if($notificationType === 'Bounce') {

	            if($bounceType === "Transient") {
	                // increment soft bounce count

	                BouncedEmails::where(['email' => $bounced_email])->increment('softbounced');
	                Storage::disk('local')->append('bounce.log', $bounced_email . ' ' . 'SoftBounced');
	            
	            } else {
	                // mark email as bounced permanently
	                BouncedEmails::where(['email'=> $bounced_email])->update(['bounced' => 1]);
	                Storage::disk('local')->append('bounce.log', $bounced_email . ' ' . 'Bounced');
	            }

	            //mark as hard bounced for emails which has soft bounced count of 3 or more
	            BouncedEmails::where(['email' => $bounced_email, 'softbounced' => 3])->update(['bounced' => 1]);

	        }

        }

    }




    // handle email complaints
    public function handleComplains(Request $request)
    {

	  // $complaint_simulator_email = 'complaint@simulator.amazonses.com';
      Storage::disk('local')->append('complaints.log', $request->getContent());
	  $payload = json_decode($request->getContent());


	  if(property_exists($payload, 'Type'))
	  {
	    if($payload->Type == "SubscriptionConfirmation")
	    {
	      // if we are verifying notification subscription in this Endpoint for the
	      // first time then go the said URL to confirm
	      $confirmation_url = curl_init($payload->SubscribeURL);
	      curl_exec($confirmation_url);
	    }
	  } else {
	    // You have to set "subscription attribute to RAW" on endpoints from SNS console to get JSON message
	    $notificationType = $payload->notificationType;
	    $complaint_email = $payload->complaint->complainedRecipients[0]->emailAddress; // get the first one
	    $user = BouncedEmails::updateOrCreate(['email' => $complaint_email]);

	    if($notificationType === 'Complaint')
	    {
	        // Mark complained for this email
	        BouncedEmails::where(['email'=> $complaint_email])->update(['complained' => 1]);
	        Storage::disk('local')->append('complaints.log', $complaint_email);
	    }
	  }


	}
}

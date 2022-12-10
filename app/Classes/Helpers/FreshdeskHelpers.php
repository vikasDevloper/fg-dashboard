<?php

namespace Dashboard\Classes\Helpers;

use Freshdesk;

class FreshdeskHelpers
{

	static function getTicketsCount($data) 
	{
		
		$filters = '?updated_since=' . $data['startDate'] . 'T00:00:00Z';
	    //Tickets
		$tickets = Freshdesk::tickets()->view($filters);
		$ticketStatus = array();
		$open = 0;
		$closed= 0;
		$resolved = 0;
		$pending = 0;
		// echo "<pre>";
		// print_r(count($tickets));

		if(!empty($tickets)) {
			foreach($tickets as $ticket) {
		
				if($ticket["status"] == 2) {
					$open += 1;
				} elseif ($ticket["status"] == 3) {
					$pending +=  1;
				} elseif ($ticket["status"] == 4) {
					$resolved +=  1;
				} elseif ($ticket["status"] == 5) {
					$closed += 1;
				}
			}
		}

		$ticketStatus['tickets'] = count($tickets);
		$ticketStatus['open'] = $open;
		$ticketStatus['pending'] = $pending;
		$ticketStatus['closed'] = $closed;
		$ticketStatus['resolved'] = $resolved;

		// echo "<pre>";
		// print_r($ticketStatus);
		return $ticketStatus;
	}	


}
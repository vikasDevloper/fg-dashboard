<?php

namespace Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use LaravelGoogleAds\Services\AdWordsService;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201609\cm\CampaignService;
use Google\AdsApi\AdWords\v201609\cm\OrderBy;
use Google\AdsApi\AdWords\v201609\cm\Paging;
use Google\AdsApi\AdWords\v201609\cm\Selector;

class GoogleAdsController extends Controller
{
    
    /** @var AdWordsService */
    protected $adWordsService;
    
    /**
     * @param AdWordsService $adWordsService
     */
    public function __construct(AdWordsService $adWordsService)
    {
        $this->adWordsService = $adWordsService;
    }

    public function campaigns()
    {
        $customerClientId = '201-945-3264';

        $campaignService = $this->adWordsService->getService(CampaignService::class, $customerClientId);

        // Create selector.
        $selector = new Selector();
        $selector->setFields(array('Id', 'Name'));
        $selector->setOrdering(array(new OrderBy('Name', 'ASCENDING')));

        // Create paging controls.
        $selector->setPaging(new Paging(0, 100));

        // Make the get request.
        $page = $campaignService->get($selector);
        return $page;
    }
}

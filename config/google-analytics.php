<?php
/*
|--------------------------------------------------------------------------
| OAuth2 Service Account
|--------------------------------------------------------------------------
|
| You can generate client id, service account name and .p12 keyfile from:
| - https://code.google.com/apis/console
|
| For information on how to obtain these keys refer to the README
|
| For information about how it works visit:
| - https://developers.google.com/accounts/docs/OAuth2?hl=it#serviceaccount
| - https://developers.google.com/accounts/docs/OAuth2ServiceAccount
| - https://code.google.com/p/google-api-php-client/wiki/OAuth2#Service_Accounts
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Client ID
    |--------------------------------------------------------------------------
    |
    | Set your client id, it should look like:
    | xxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com
    |
    */

    'client_id'        => '503887013711-ma5dj9j9mj6951b5us1p98lp8hmnjcff.apps.googleusercontent.com',


    /*
    |--------------------------------------------------------------------------
    | Service Account Name
    |--------------------------------------------------------------------------
    |
    | Set your service account name, it should look like:
    | xxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx@developer.gserviceaccount.com
    |
    */
    'service_email'    => 'sanjay@faridaguptaanalytics.iam.gserviceaccount.com',


    /*
    |--------------------------------------------------------------------------
    | Path to the .p12 certificate
    |--------------------------------------------------------------------------
    |
    | You need to download this from the Google API Console when the
    | service account was created.
    |
    | Make sure you keep your key.p12 file in a secure location, and isn't
    | readable by others.
    |
    */

    'certificate_path' => __DIR__.'/keys/FaridaGuptaAnalytics-31f564415a15.p12',


    /*
    |--------------------------------------------------------------------------
    | Returns objects
    |--------------------------------------------------------------------------
    |
    | Returns objects of the Google API Service instead of associative arrays
    |
    */

    'use_objects'      => true,

    /*
    | -------------------------------------------------------------------------
    | Site Id 
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'site_id'        => 'ga:112220153',

    'metrics' => 'ga:sessions, ga:pageviews, ga:users, ga:CPC, ga:CTR, ga:adClicks, ga:adCost, ga:costPerTransaction, ga:transactions',
    
    'pageviews-metrics' => 'ga:uniquePageviews',
    'pageviews-extras' => array('dimensions' => 'ga:pagePath', 'sort' => '-ga:uniquePageviews', 'filters' => 'ga:pagePath=@/cart/,ga:pagePath=@/success,ga:pagePath=@/onepage'),

    'cost-per-transaction-metrics' => 'ga:transactions, ga:costPerTransaction',
    'cost-per-transaction-extras' => array('dimensions' => 'ga:adwordsCampaignID', 'filters' => 'ga:adwordsCampaignID!=(not set)'),
        
    'dimensions' => array(),


];

<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizedUrlShortner extends Model
{
     protected $table = 'customized_url_shortener';
     
     public static function getShortUrl($longUrl){
 
     $data = CustomizedUrlShortner::select('custom_url')
             ->whereRaw("redirect_url='".$longUrl."'")
             ->get();
             $short_url = "";
            // print_r($data);
             foreach ($data as $key => $value) {
             	 $short_url = $value->custom_url;
             }
            return $short_url;
   }

     protected $fillable = [
        'master_id','custom_url', 'redirect_url'
    ];
}

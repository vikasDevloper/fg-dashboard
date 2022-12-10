<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class urlShortner extends Model
{
   protected $table = 'url_shortener';

   
   protected $fillable = [
        'short_url', 'redirect_url'
    ];
}

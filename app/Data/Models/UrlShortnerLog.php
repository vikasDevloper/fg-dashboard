<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class UrlShortnerLog extends Model
{
    protected $table = 'url_shortener_log';

    protected $fillable = [
        'cutomized_id','short_url', 'redirect_url','ip_addr','is_mobile','http_referer'
    ];

}

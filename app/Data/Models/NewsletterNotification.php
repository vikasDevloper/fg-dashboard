<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterNotification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'newsletter_notification';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

    public $timestamps = false;

    /** 
     * Get all the orders group by status
     *
     */
}

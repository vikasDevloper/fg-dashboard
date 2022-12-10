<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionUserSource extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'exhibition_user_source';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

    public $timestamps = false;

    /** 
     * get order counts by cancel resons
     *
     * @return array
     */
}

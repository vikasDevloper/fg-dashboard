<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogProductRelation extends Model
{
    //
    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'catalog_product_relation';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;
	
}

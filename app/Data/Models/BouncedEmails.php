<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class BouncedEmails extends Model {
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */

	protected $table = 'bounced_emails';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */

	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['email', 'bounced_type'];

	/**
	 * Get all the orders group by status
	 *
	 */

	static function getAllEmails() {

		$data      = array();
		$allEmails = BouncedEmails::all();

		if (!empty($allEmails)) {
			foreach ($allEmails as $value) {
				$data[] = $value['email'];
			}
		}

		return $data;

	}

}

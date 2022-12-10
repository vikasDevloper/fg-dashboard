<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'newsletter_template';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */


    static function getListTemplates() {
		$templates = NewsLetter::orderBy('template_id', 'ASC')
			->select("template_id", "template_code", "template_subject")
			->get();

		$data = array();
		if (!empty($templates)) {
			foreach ($templates as $templatesList) {
				$data[] = $templatesList;
			}
		}

		return $data;
	}
}

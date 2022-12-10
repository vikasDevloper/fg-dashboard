<?php

namespace Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'select-city'  => 'required|max: 255',
            'replace-city' => 'required|max: 255',
		];
	}
}

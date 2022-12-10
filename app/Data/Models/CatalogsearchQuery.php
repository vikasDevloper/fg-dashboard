<?php

namespace Dashboard\Data\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogsearchQuery extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'catalogsearch_query';

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

    static function getSearchTerms($limit)
    {
    	$allSearchTerms = CatalogsearchQuery::orderBy('updated_at', 'desc')
    					->select('query_text', 'num_results', 'popularity')
    					->take($limit)->get();
    	
    	$data = array();
    	
    	if(!empty($allSearchTerms)) {
	    	foreach ($allSearchTerms as $searchTerm) {
	    		$d['searchTerm'] 		 = $searchTerm['query_text'];
	    		$d['numberOfResults'] = $searchTerm['num_results'];
	    		$d['numberofUses']    = $searchTerm['popularity'];
	    		$data[] = $d;
	    	}
    	}

    	return $data;
    }

    static function getSearchTermsByPopularity($limit)
    {
    	$allSearchTerms = CatalogsearchQuery::orderBy('popularity', 'desc')
    					->select('query_text', 'num_results', 'popularity')
    					->take($limit)->get();
    	
    	$data = array();
    	
    	if(!empty($allSearchTerms)) {
	    	foreach ($allSearchTerms as $searchTerm) {
	    		$d['searchTerm'] 		 = $searchTerm['query_text'];
	    		$d['numberOfResults'] = $searchTerm['num_results'];
	    		$d['numberofUses']    = $searchTerm['popularity'];
	    		$data[] = $d;
	    	}
    	}

    	return $data;
    }
}

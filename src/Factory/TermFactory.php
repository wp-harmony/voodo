<?php
namespace Harmony\Voodoo\Factory;

use stdClass;
use WP_Query;
use Harmony\Voodoo\Contract\ModelFactory;
use Harmony\Voodoo\Collection;
use Harmony\Voodoo\Model\Term;

/**
 * Term factory for building active record models
 * 
 * @package Voodoo
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
class TermFactory implements ModelFactory
{
	public function make($term)
	{
		// is it a term id? if so get the term object
		if (is_numeric($term)) {
			global $wpdb;
			$taxonomy = $wpdb->get_var($wpdb->prepare( "SELECT tt.taxonomy FROM $wpdb->term_taxonomy AS tt WHERE tt.term_id = %s LIMIT 1", $id));
			$term = get_term($id, $taxonomy);
		}

		// is it a term object? if so build the model 
		if (is_object($term) && isset($term->term_id)) {
			return $this->build($term);
		}

		// Don't know what it is try to parse it
		if (is_array($term)) {
			return $this->parse($term);
		}
	}

	public function make_many($many)
	{
		$collection = new Collection;
		foreach($many as $term) {
			$collection[] = $this->make($term);
		}
		return $collection;
	}
	
	public function query($query)
	{
		return null;
	}

	private function build(stdClass $term)
	{
		$data = [];
		$data = array_merge($data, (array)$term);
		$data = apply_filters('voodoo_term_data', $data);
		return new Term($data);
	}
	
	private function parse(array $array)
	{
		if (empty($array)) {
			return null; // Is empty
		}
		
		$values = array_values($array);
		$keys = array_keys($array);
		$first_value = array_pop($values);
		$first_key = array_pop($keys);

		if (array_key_exists('term_id', $first_value)) {
			return $this->make_many($array); // Is result of get_terms()
		} elseif (is_numeric($first_key) &&  is_numeric($first_value)) {
			return $this->make_many($array); // Is an array of term id's
		}
	}
}

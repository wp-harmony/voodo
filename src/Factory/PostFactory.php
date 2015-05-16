<?php
namespace Harmony\Voodoo\Factory;

use WP_Query;
use WP_Post;
use Harmony\Voodoo\Contract\ModelFactory;
use Harmony\Voodoo\Collection;
use Harmony\Voodoo\Model\Post;

/**
 * Post factory for building active record models
 * 
 * @package Voodoo
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
class PostFactory implements ModelFactory
{
	public function make($post)
	{
		// is it a post id? if so get the post object
		if(is_numeric($post)) {
			$post = get_post($post);
		}

		// is it a post object? if so build the model
		if ($post instanceof WP_Post) {
			return $this->build($post);
		}

		// Not a post id or post object, but maybe a post query or array of posts
		if ($post instanceof WP_Query) {
			return $this->query($post);
		}

		// Don't know what it is try to parse it
		if (is_array($post)) {
			return $this->parse($post);
		}
	}

	public function make_many($many)
	{
		$collection = new Collection;
		foreach($many as $post) {
			$collection[] = $this->make($post);
		}
		return $collection;
	}
	
	public function query($query)
	{
		if ( ! ($query instanceof WP_Query) ) {
			$query = new WP_Query($query);
		}

		return $this->make_many($query->posts ? $query->posts : $query->get_posts());
	}

	private function build(WP_Post $post)
	{
		$data = [];
		$meta = get_post_meta($post->ID);
		foreach ($meta as $key => $value) {
			if(is_array($value) && count($value) === 1 && isset($value[0])) {
				$value = array_pop($value);
			}
			$value = maybe_unserialize($value);
			if(is_string($value) && null !== ($jsond = json_decode($value, true))) {
				$value = $jsond;
			}
			$meta[$key] = $value;
		}
		$data = array_merge($data, $meta);
		$data = array_merge($data, (array)$post);
		$data = apply_filters('voodoo_post_data', $data);
		return new Post($data);
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
		
		if ($first_value instanceof WP_Post) {
			return $this->make_many($array); // Is result of get_posts()
		} elseif (is_numeric($first_key) &&  is_numeric($first_value)) {
			return $this->make_many($array); // Is an array of post id's
		} else {
			return $this->query($array);     // Is WP_Query args
		}
	}
}

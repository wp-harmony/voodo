<?php
namespace Harmony\Voodoo\Factory;

use WP_User_Query;
use WP_User;
use Harmony\Voodoo\Contract\ModelFactory;
use Harmony\Voodoo\Collection;
use Harmony\Voodoo\Model\User;

/**
 * User factory for building active record models
 * 
 * @package Voodoo
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
class UserFactory implements ModelFactory
{
	public function make($user)
	{
		// is it a post id? if so get the post object
		if(is_numeric($user)) {
			$user = new WP_User($user);
		}

		// is it a post object? if so build the model
		if ($user instanceof WP_User) {
			return $this->build($user);
		}

		// Not a post id or post object, but maybe a post query or array of posts
		if ($user instanceof WP_User_Query) {
			$this->query($user);	
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
		if ( ! ($query instanceof WP_User_Query) ) {
			$query = new WP_User_Query($query);
		}

		$query->set('fields', 'all_with_meta');
		
		return $this->make_many($query->get_results());
	}

	private function build(WP_User $user)
	{
		$data = [];
		$meta = get_user_meta($user->ID);
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
		$data = array_merge($data, (array)$user->data);
		$data = apply_filters('voodoo_user_data', $data);

		return new User($data);
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
		
		if ($first_value instanceof WP_User) {
			return $this->make_many($array); // Collection of WP_Users
		} elseif (is_numeric($first_key) && is_numeric($first_value)) {
			return $this->make_many($array); // Is an array of user id's
		} else {
			return $this->query($array);     // Is WP_User_Query args
		}
	}
}

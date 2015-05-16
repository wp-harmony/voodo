<?php
namespace Harmony\Voodoo\Factory;

use Harmony\Voodoo\Contract\ModelFactory;
use Harmony\Voodoo\Collection;
use Harmony\Voodoo\Model\Setting;

/**
 * Setting factory for building active record models
 * 
 * @package Voodoo
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
class SettingFactory implements ModelFactory
{
	public function make($setting)
	{
		$data = [];
		if ($setting) {
			list($first) = explode('.', $setting);
			$data = get_option($first, []);
		} else {
			$setting = '';
		}
		
		return new Setting($data, $id);
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
}

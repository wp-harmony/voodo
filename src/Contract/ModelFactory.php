<?php
namespace Harmony\Voodoo\Contract;

use WP_Query;
use WP_Post;

/**
 * Model factory interface for building active record models
 * 
 * @package Voodoo
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
interface ModelFactory
{
	
	public function make($subject);

	public function make_many($many);

	public function query($query);
}

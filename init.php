<?php
/**
 * Voodoo - Active Record Models
 *
 * Part of the Harmony Group 
 *
 * Plugin Name: Harmony Voodoo
 * Depends: Harmony Mana
 * 
 * @package Voodoo
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @version 2.0.0
 */

use Harmony\Voodoo\Factory\PostFactory;
use Harmony\Voodoo\Factory\TermFactory;
use Harmony\Voodoo\Factory\UserFactory;
use Harmony\Voodoo\Factory\SettingFactory;

/**
 * Register Voodoo
 * 
 * @param Harmony\Mana\Container $app
 */
function voodoo_register($app)
{
	$app['autoloader']['Harmony\\Voodoo'] = __DIR__ . '/src';
	require('functions.php');
}

add_action('harmony_register', 'voodoo_register');


/**
 * Boot Voodoo
 * 
 * @param $app
 */
function voodoo_boot($app)
{
	$app['voodoo.factory'] = $factory = [
		'post' => new PostFactory,
		'term' => new TermFactory,
		'user' => new UserFactory,
		'setting' => new SettingFactory,
	];

	do_action('voodoo_loaded', $factory);
}

add_action('harmony_boot', 'voodoo_boot');

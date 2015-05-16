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

function voodoo_register($app)
{
	$app['autoloader']['Harmony\\Voodoo'] = __DIR__ . '/src';
	require('functions.php');
}

add_action('harmony_register', 'voodoo_register');
<?php
/**
 * Voodoo Functions
 *
 * @package    Harmony
 * @subpackage Voodoo
 * @author     Simon Holloway <holloway.sy@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT
 */

function post($id)
{
	return (new Harmony\Voodoo\Factory\PostFactory)->make($id);	
}

function term($id)
{
	return (new Harmony\Voodoo\Factory\TermFactory)->make($id);		
}

function user($id)
{
	return (new Harmony\Voodoo\Factory\UserFactory)->make($id);		
}

function setting($id = null)
{
	return (new Harmony\Voodoo\Factory\SettingFactory)->make($id);
}
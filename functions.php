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
	return app('voodoo.factory.post')->make($id);	
}

function term($id)
{
	return app('voodoo.factory.term')->make($id);		
}

function user($id)
{
	return app('voodoo.factory.user')->make($id);		
}

function setting($id = null)
{
	return app('voodoo.factory.setting')->make($id);
}
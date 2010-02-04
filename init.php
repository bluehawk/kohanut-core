<?php defined('SYSPATH') or die('No direct script access.');

// Grab the list of modules, and check if the install folder is hanging around, if it is, set the install route
$modules = Kohana::modules();
if (is_dir($modules['kohanut'].'/classes/controller/kohanut/install'))
{
	Route::set('kohanut-install','admin/install')
		->defaults(array(
			'controller' => 'install',
			'action'     => 'index',
			'directory'  => 'kohanut/install'
		));
}

// Set the kohanut admin route
Route::set('kohanut-admin','admin(/<controller>(/<action>(/<params>)))',array('params'=>'.*'))
	->defaults(array(
		'controller' => 'pages',
		'action'     => 'index',
		'directory'  => 'kohanut/admin'
	));
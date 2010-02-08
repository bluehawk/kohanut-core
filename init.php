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

// Set the kohanut media route
Route::set('kohanut-media','admin/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'admin',
		'action'     => 'media',
		'directory'  => 'kohanut',
		'file'       => NULL,
	));

Route::set('kohanut-login','admin/<action>',array('action'=>'login|logout'))
	->defaults(array(
		'controller' => 'admin',
		'directory'  => 'kohanut',
	));

// Set the kohanut admin route
Route::set('kohanut-admin','admin(/<controller>(/<action>(/<params>)))',array('params'=>'.*'))
	->defaults(array(
		'controller' => 'pages',
		'action'     => '',
		'directory'  => 'kohanut'
	));

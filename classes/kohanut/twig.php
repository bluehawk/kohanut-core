<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohanut_Twig {
	
	public static function render($code)
	{
		// Make the twig loader, environment and template and pass the layout code.
		$loader = new Twig_Loader_String();
		$twig = new Twig_Environment($loader, array(
			'cache' => APPPATH.'cache/twig',
		));
	
		$template = $twig->loadTemplate($code);
		return $template->render(array('Kohanut'=>new Kohanut));

	}
	
}
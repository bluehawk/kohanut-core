<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kohanut Twig. Takes care of loading and running twig.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Kohanut_Twig {
	
	/**
	 * Render a twig template.
	 * 
	 * @param  string  The code to render
	 * @return string
	 */
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
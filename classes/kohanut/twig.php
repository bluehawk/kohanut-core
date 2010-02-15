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
	
	static private $twig = null;
	
	/**
	 * Render a twig template.
	 * 
	 * @param  string  The code to render
	 * @return string
	 */
	public static function render($code)
	{
		if (Kohana::$profiling === TRUE)
		{
			// Start a new benchmark
			$benchmark = Profiler::start('Kohanut', 'Twig Render');
		}
		
		if (self::$twig === null)
		{
			// Make the twig loader, environment and template and pass the layout code.
			$loader = new Twig_Loader_String();
			self::$twig = new Twig_Environment($loader, array(
				'cache' => APPPATH.'cache/twig',
			));
		}
		
		$template = self::$twig->loadTemplate($code);
		
		$out = $template->render(array('Kohanut'=>new Kohanut));
		
		if (isset($benchmark))
		{
			// Stop the benchmark
			Profiler::stop($benchmark);
		}
		
		return $out;
	}
}
<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kohanut Twig Extension, makes calling Kohanut functions in Twig much faster
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
if ( ! class_exists('Twig_Autoloader'))
{
	// Load the Twig class autoloader
	require Kohana::find_file('vendor', 'Twig/lib/Twig/Autoloader');
	// Register the Twig class autoloader
	Twig_Autoloader::register();
}

class Kohanut_Twig_Extension extends Twig_Extension {

	public function getName()
	{
		return 'kohanut';
	}
	
	public function getTokenParsers()
	{
		return array(
			new Kohanut_Twig_Token_Area(),
			new Kohanut_Twig_Token_Nav(),
			//new Kohanut_Twig_Token_Mainnav(),
			new Kohanut_Twig_Token_Stats(),
			new Kohanut_Twig_Token_Element(),
		);
	}

}

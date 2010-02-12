<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kohanut Twig Extension, makes calling Kohanut functions in Twig much faster
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Kohanut_Twig_Node_Stats extends Twig_Node {

	
	
	public function __construct($lineno)
	{
		parent::__construct($lineno);
		
		
	}
	
	public function compile($compiler)
	{
		$compiler
			->addDebugInfo($this)
			->write('echo Kohanut::stats()')
			->raw(";\n")
		;
	}

}

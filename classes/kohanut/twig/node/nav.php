<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kohanut Twig Extension, makes calling Kohanut functions in Twig much faster
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Kohanut_Twig_Node_Nav extends Twig_Node {

	protected $params;
	
	public function __construct($params, $lineno)
	{
		parent::__construct($lineno);
		
		$this->params = $params;
	}
	
	public function compile($compiler)
	{
		$compiler
			->addDebugInfo($this)
			->write('echo Kohanut::nav("' . $this->params . '");')
			->raw(";\n")
		;
	}

}

<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kohanut Twig Extension, makes calling Kohanut functions in Twig much faster
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Kohanut_Twig_Node_Element extends Twig_Node {

	protected $type;
	protected $name;
	
	public function __construct($type, $name, $lineno)
	{
		parent::__construct($lineno);
		
		$this->type = $type;
		$this->name = $name;
	}
	
	public function compile($compiler)
	{
		$compiler
			->addDebugInfo($this)
			->write('echo Kohanut::element("' . $this->type . '","' . $this->name . '");')
			->raw(";\n")
		;
	}

}

<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kohanut Twig Extension, makes calling Kohanut functions in Twig much faster
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Kohanut_Twig_Node_Area extends Twig_Node {

	protected $id;
	protected $name;
	
	public function __construct($id, $name, $lineno)
	{
		parent::__construct($lineno);
		
		$this->id = $id;
		$this->name = $name;
	}
	
	public function compile($compiler)
	{
		$compiler
			->addDebugInfo($this)
			->write('echo Kohanut::element_area(' . $this->id . ',"' . $this->name . '");')
			->raw(";\n")
		;
	}

}

<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kohanut Twig Extension, makes calling Kohanut functions in Twig much faster
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Kohanut_Twig_Token_Element extends Twig_TokenParser {

	public function parse(Twig_Token $token)
	{
		$lineno = $token->getLine();
		$this->parser->getStream()->expect(Twig_Token::OPERATOR_TYPE, '(');
		$type = $this->parser->getStream()->expect(Twig_Token::STRING_TYPE)->getValue();
		$this->parser->getStream()->expect(Twig_Token::OPERATOR_TYPE, ',');
		$name = $this->parser->getStream()->expect(Twig_Token::STRING_TYPE)->getValue();
		$this->parser->getStream()->expect(Twig_Token::OPERATOR_TYPE, ')');
		//$value = $this->parser->getExpressionParser()->parseExpression();
		
		$this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);
		
		return new Kohanut_Twig_Node_Element($type, $name, $lineno, $this->getTag());
	}
	
	public function getTag()
	{
		return 'Kohanut_Element';
	}

}

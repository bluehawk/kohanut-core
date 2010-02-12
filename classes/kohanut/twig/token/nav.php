<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kohanut Twig Extension, makes calling Kohanut functions in Twig much faster
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Kohanut_Twig_Token_Nav extends Twig_TokenParser {

	public function parse(Twig_Token $token)
	{
		$lineno = $token->getLine();
		$this->parser->getStream()->expect(Twig_Token::OPERATOR_TYPE, '(');
		$params = $this->parser->getStream()->expect(Twig_Token::STRING_TYPE)->getValue();
		$this->parser->getStream()->expect(Twig_Token::OPERATOR_TYPE, ')');
		
		$this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);
		
		return new Kohanut_Twig_Node_Nav($params, $lineno, $this->getTag());
	}
	
	public function getTag()
	{
		return 'Kohanut_Nav';
	}

}

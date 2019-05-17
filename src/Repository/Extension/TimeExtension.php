<?php

namespace App\Repository\Extension;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

class TimeExtension extends FunctionNode {

	public $time;

	public function getSql(SqlWalker $sqlWalker): string {
		return 'TIME(' . $sqlWalker->walkArithmeticPrimary($this->time) . ')';
	}

	/**
	 * @param Parser $parser
	 * @throws QueryException
	 */
	public function parse(Parser $parser): void {
		$parser->match(Lexer::T_IDENTIFIER);
		$parser->match(Lexer::T_OPEN_PARENTHESIS);
		$this->time = $parser->ArithmeticPrimary();
		$parser->match(Lexer::T_CLOSE_PARENTHESIS);
	}
}
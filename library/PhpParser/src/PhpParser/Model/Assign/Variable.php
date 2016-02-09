<?php
namespace PhpParser\Model\Assign;

use Parser\Model\Raw;
use Parser\Model\Regex;
use Parser\Model\VariableInterface;

class Variable extends Raw implements VariableInterface {
	public function __construct( $code, $vector, $name = 'Variable' ) {
		parent::__construct( $code, $vector, $name );
		$this->pattern = array( array( 'return', new Regex( '[^=\!\<\>]=[^=\>]', 3 ) ), ';' );
		$this->lookBehind = true;
	}
	public function getName() {
		return $this->name;
	}
}

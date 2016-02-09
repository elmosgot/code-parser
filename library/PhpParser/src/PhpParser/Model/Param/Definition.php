<?php
namespace PhpParser\Model\Param;

use Parser\Model\Raw;
use Parser\Model\Regex;
use Parser\Model\VariableInterface;

class Definition extends Raw implements VariableInterface {
	public function __construct( $code, $vector ) {
		parent::__construct( $code, $vector, 'Definition' );
		$this->parsable = false;
		$this->pattern = array( array( new Regex( 'public([^\$]{1,})?\$', 8 ), new Regex( 'protected([^\$]{1,})?\$', 11 ), new Regex( 'private([^\$]{1,})?\$', 9 ) ), ';' );
	}
	public function getName() {
		return $this->name;
	}
}

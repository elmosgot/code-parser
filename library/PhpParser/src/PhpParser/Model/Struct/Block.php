<?php
namespace PhpParser\Model\Struct;

use Parser\Model\Raw;

class Block extends Raw {
	public function __construct( $code, $vector ) {
		parent::__construct( $code, $vector, 'Block' );
		$this->pattern = array( '{', '}' );
	}
}

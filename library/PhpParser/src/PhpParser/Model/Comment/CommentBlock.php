<?php
namespace PhpParser\Model\Comment;

use Parser\Model\Raw;

class CommentBlock extends Raw {
	public function __construct( $code, $vector ) {
		parent::__construct( $code, $vector, 'CommentBlock' );
		$this->parsable = false;
		$this->pattern = array( '/*', '*/' );
	}
}

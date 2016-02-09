<?php
namespace PhpParser\Model\Comment;

use Parser\Model\Raw;
use Parser\Model\Regex;

class CommentLine extends Raw {
	public function __construct( $code, $vector ) {
		parent::__construct( $code, $vector, 'CommentLine' );
		$this->parsable = false;
		$this->pattern = array( '//', new Regex( '(\r\n|\n|\r)', 1 ) );
	}
}

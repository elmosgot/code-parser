<?php
namespace PhpParser\Model\Type;

use Parser\Model\Raw;
use Parser\Model\Regex;

class ArrayObj extends Raw {
	public function __construct( $code, $vector, $name = 'Array' ) {
		parent::__construct( $code, $vector, $name );
		$this->pattern = array( '=>', array( '),', ';' ) );
		$this->lookBehind = true;
	}
	public function push( $value ) {
		$code = $this->nodeCode;
		// Get var block
		$pos1 = strpos( $code, '(' ) + 1;
		$pos2 = stripos( $code, ')' );
		$varBlock = substr( $code, $pos1, $pos2-$pos1 );
		// Detect tabs
		preg_match_all( '/\t/', $varBlock, $matches );
		// Run code
		eval( sprintf( "\$exe = array( %s );", $varBlock ) );
		// Calculate indent
		$mult = floor( count( $matches[0] ) / count( $exe ) );
		$indent = str_repeat( "\t", $mult );
		$closeIndent = str_repeat( "\t", $mult-1 );
		// Add value
		array_push( $exe, $value );
		$insert = "\n$indent'" . implode( sprintf( "',\n%s'", $indent ), $exe ) . "'\n$closeIndent";
		$updated = mb_substr( $code, 0, $pos1 );
		$updated .= $insert;
		$updated .= mb_substr( $code, $pos2 );
		$this->nodeCode = $updated;
		$this->updated = true;
	}
}

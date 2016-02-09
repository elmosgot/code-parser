<?php
namespace Parser\Model;

class Regex {
	protected $pattern;
	protected $size;
	protected $caseSensitive;

	public function __construct( $pattern, $size, $caseSensitive = true ) {
		$this->pattern = $pattern;
		$this->size = (int) $size;
		$this->caseSensitive = $caseSensitive;
	}
	public function getPattern() {
		return sprintf( '/%s/%s', $this->pattern, ( $this->caseSensitive ? '' : 'i' ) );
	}
	public function getSize() {
		return $this->size;
	}
}

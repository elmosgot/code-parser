<?php
namespace Parser\Model;

class Vector {
	protected $start;
	protected $end;

	/**
	 * @param $start int
	 * @param $end int
	 */
	public function __construct( $start, $end ) {
		$this->start = $start;
		$this->end = $end;
	}
	public function getStart() {
		return $this->start;
	}
	public function getEnd() {
		return $this->end;
	}
	public function length() {
		return $this->end - $this->start;
	}
	public function getPart( &$code ) {
		return mb_substr( $code, $this->start, $this->length() );
	}
}

<?php
namespace Parser\Model;

class Path {
	protected $root;

	/**
	 * @param $raw Raw
	 */
	public function __construct( &$raw ) {
		$this->root = $raw;
	}
	public function query( $q, &$raw = null, $indent = 0 ) {
		if( $raw === null ) {
			$raw = $this->root;
		}
		$deep = false;
		if( substr( $q, 0, 2 ) === '//' ) {
			$deep = true;
			$q = substr( $q, 2 );
		}
		$route = explode( '/', $q );
		$level = array_shift( $route );
		$qNext = implode( '/', $route );
		// Match absolute route
		//echo str_repeat( "\t", $indent ) . sprintf( "%s === %s<br/>", $raw->getNodeName(), $level );
		if( $raw->getNodeName() === $level ) {
			if( empty( $qNext ) ) {
				return $raw;
			} else {
				if( is_array( $raw->getChildren() ) ) {
					foreach( $raw->getChildren() as $child ) {
						//echo str_repeat( "\t", $indent ) . sprintf( "%s === %s<br/>", $child->getNodeName(), $level );
						if( $child->getNodeName() === $level ) {
							return $this->query( $qNext, $child, $indent+1 );
						}
					}
				}
			}
		}
		// Match relative route
		if( $deep ) {
			if( is_array( $raw->getChildren() ) ) {
				foreach( $raw->getChildren() as $child ) {
					//echo str_repeat( "\t", $indent ) . sprintf( "%s === %s<br/>", $child->getNodeName(), $level );
					if( $child->getNodeName() === $level ) {
						return $this->query( '//' . $q, $child, $indent+1 );
					} else {
						$result = $this->query( '//' . $q, $child, $indent+1 );
						if( $result !== null ) {
							return $result;
						}
					}
				}
			}
		}
	}
}

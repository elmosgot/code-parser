<?php
namespace Parser\Model;

class Raw {
	protected $nodeName;
	protected $nodeCode;
	protected $vector;
	protected $length;
	protected $children;
	protected $pattern;
	protected $parsable;
	protected $lookBehind;
	protected $updated = false;

	/**
	 * @param $name string
	 * @param $code string
	 * @param $vector vector
	 */
	public function __construct( $code, $vector, $name = 'Raw' ) {
		$this->nodeCode = $code;
		$this->vector = $vector;
		$this->nodeName = $name;
		$this->parsable = true;
		$this->lookBehind = false;
	}
	/**
	 * @param $value Raw
	 */
	public function setParent( $value ) {
		$this->parent = $value;
	}
	/**
	 * @return Raw
	 */
	public function getParent() {
		return $this->parent;
	}
	/**
	 * @return string
	 */
	public function getNodeCode() {
		return $this->nodeCode;
	}
	/**
	 * @return string
	 */
	public function getNodeName() {
		return $this->nodeName;
	}
	/**
	 * @param $raw Raw
	 */
	public function addChild( $raw ) {
		$raw->setParent( $this );
		$this->children[] = $raw;
		$this->length = count( $this->children );
	}
	/**
	 * @return array
	 */
	public function getChildren() {
		return $this->children;
	}
	/**
	 * @return int
	 */
	public function newLines() {
		if( preg_match_all( '/(\r\n\|\r|\n)/', $this->nodeCode, $matches ) ) {
			return count( $matches );
		}
		return 0;
	}
	public function getPattern() {
		return $this->pattern;
	}
	/**
	 * @return Vector
	 */
	public function getVector() {
		return $this->vector;
	}
	/**
	 * @return bool
	 */
	public function isParsable() {
		return $this->parsable;
	}
	/**
	 * @return bool
	 */
	public function isUpdated() {
		return $this->updated;
	}
	/**
	 * @return bool
	 */
	public function lookBehind() {
		return $this->lookBehind;
	}
	public function printTree( $indent = 0 ) {
		echo str_repeat( "\t", $indent ) . sprintf( "%s (%s - %s)\n", get_class( $this ), $this->getNodeName(), $this->getVector()->length() );
		if( is_array( $this->children ) ) {
			foreach( $this->children as $child ) {
				$child->printTree( $indent+1 );
			}
		}
	}
	public function printClean( $print = true ) {
		$code = $this->nodeCode;
		$this->nodeCode = '';
		if( is_array( $this->children ) ) {
			foreach( $this->children as $child ) {
				$child->printClean( false );
			}
		}
		if( $print ) {
			print_r( $this );
		}
	}
	public function toString( $indent = 0 ) {
		$i = 0;
		$str = '';
		$format = '%2$s';//str_repeat( "\t", $indent ) . "%s: '''%s'''\n";
		if( !$this->updated && is_array( $this->children ) ) {
			foreach( $this->children as $child ) {
				$str .= sprintf( $format, $child->getNodeName(), $child->toString( $indent+1 ) );
			}
		} else {
			$str = sprintf( $format, $this->getNodeName(), $this->getNodeCode() );
		}
		return $str;
	}
}

<?php
namespace Parser\Model;

class Parser {
	protected $codeBlocks;

	/**
	 * @param $raw Raw
	 */
	public function parse( &$raw, $forward = 0, $indent = 0 ) {
		$textPos = 0;
		for( $i = ($forward+1); $i < $raw->getVector()->getEnd(); $i++ ) {
			$search = mb_substr( $raw->getNodeCode(), 0, $i );
			foreach( $this->codeBlocks as $codeBlock ) {
				if( $codeBlock instanceof Raw ) {
					$className = get_class( $codeBlock );
					$pattern = $codeBlock->getPattern();
					// Open
					if( ( $rewind = $this->testPattern( $pattern[0], $search ) ) !== false ) {
						$name = '';
						if( $codeBlock->lookBehind() ) {
							//echo "Backward searching...<br/>\n";
							$whitespace = 0;
							for( $j = $i; $j > -1; $j-- ) {
								$char = mb_substr( $search, $j, 1 );
								if( preg_match( '/[\r\n\t ]/', $char ) ) {
									$whitespace++;
									if( $whitespace > 1 ) {
										break;
									}
								}
							}
							$lookBehind = ($j+1);
							$name = trim( mb_substr( $search, $lookBehind, ($i+$rewind)-$lookBehind ), "\"' " );
							$offset = $lookBehind;
						} else {
							$offset = ($i+1);
						}
						if( $textPos < $offset ) {
							// Create Text node for rest of the content
							$vector = new Vector( $textPos, $i+$rewind );
							$code = $vector->getPart( $raw->getNodeCode() );
							$raw->addChild( new Raw( $code, $vector, 'Text' ) );
						}
						//echo str_repeat( "\t", $indent ) . sprintf( '<b>%s</b>: %s (%s)<br/>', $className, $name, $offset );
						for( $j = 0; $j < $raw->getVector()->getEnd(); $j++ ) {
							$buffer = mb_substr( $raw->getNodeCode(), $offset, $j );
							// Close
							if( $this->testPattern( $pattern[1], $buffer ) ) {
								$className = get_class($codeBlock);
								$vector = new Vector( $i+$rewind, $j+$offset );
								$code = $vector->getPart( $raw->getNodeCode() );
								if( empty( $name ) ) {
									$child = new $className( $code, $vector );
								} else {
									$child = new $className( $code, $vector, $name );
								}
								$raw->addChild( $child );
								$textPos = $i = $vector->getEnd();
								if( $child->isParsable() ) {
									//echo str_repeat( "\t", $indent ) . sprintf( "Parsing child '%s'...<br/>\n", get_class( $child ) );
									$this->parse( $child, (-1*$rewind), ($indent+1) );
								} else {
									//echo str_repeat( "\t", $indent ) . "Do not parse...<br/>\n";
								}
								break;
							}
						}
					}
				}
			}
		}
		if( $textPos < $raw->getVector()->getEnd() ) {
			// Create Text node for rest of the content
			$vector = new Vector( $textPos, $raw->getVector()->getEnd() );
			$code = $vector->getPart( $raw->getNodeCode() );
			$raw->addChild( new Raw( $code, $vector, 'Text' ) );
		}
	}
	protected function testPattern( $pattern, &$haystack ) {
		if( !is_array( $pattern ) ) {
			$pattern = array( $pattern );
		}
		foreach( $pattern as $patt ) {
			if( $patt instanceof Regex ) {
				$rewind = -1*$patt->getSize();
				$hit = $patt->getPattern();
			} else {
				if( $patt === '\n' ) {
					$size = 1;
					$patt = '(\r\n\|\r|\n)';
				} else {
					$size = strlen( $patt );
				}
				$patt = preg_replace( '/([\$\^\.\,\/\{\}\(\)\[\]\*\\\])/', '\\\$1', $patt );
				$rewind = -1*$size;
				$hit = sprintf( '/%s/', $patt );
			}
			$test = mb_substr( $haystack, $rewind );
			$compare = preg_match( $hit, $test );
			if( $compare ) {
				return $rewind;
			}
		}
		return false;
	}
	public function setCodeBlocks( $codeBlocks ) {
		$this->codeBlocks = $codeBlocks;
	}
}

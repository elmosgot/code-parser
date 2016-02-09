<?php
namespace Moduler\Model;

use Parser\Model\Vector;
use PhpParser\Model\Assign\Variable;
use PhpParser\Model\Comment\CommentBlock;
use PhpParser\Model\Comment\CommentLine;
use PhpParser\Model\Struct\Block;
use PhpParser\Model\Type\ArrayObj;
use PhpParser\Model\Type\DoubleString;
use PhpParser\Model\Type\SingleString;

class PhpParser extends Parser {
	public function __construct() {
		$vector = new Vector( 0, 1 );
		$codeBlocks = array(
			new CommentBlock( '', $vector ),
			new CommentLine( '', $vector ),
			new Variable( '', $vector ),
			new Block( '', $vector ),
			new ArrayObj( '', $vector ),
			new DoubleString( '', $vector ),
			new SingleString( '', $vector )
		);
		$this->setCodeBlocks( $codeBlocks );
	}
}

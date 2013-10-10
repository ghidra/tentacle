<?php
//require_once './html.php';
require_once node_root.'node_css.php';

class list_style_base extends node_css{
	
	var $position_options=array(
		'inside',
		'outside'
	);
	var $stype_options=array(
		'none',
		'disc',
		'circle',
		'square',
		'decimal',
		'decimal-leading-zero',
		'lower-roman',
		'upper-roman',
		'lower-alpha',
		'upper-alpha',
		'lower-greek',
		'lower-latin',
		'upper-latin',
		'hebrew',
		'armenian',
		'georgian',
		'cjk-ideographic',
		'hiragana',
		'katakana',
		'hiragana-iroha',
		'ktakana-iroha'
	);
	
	var $position='outside';
	var $stype='disc';

	function __construct(){
		parent::__construct();
	}
	//----------------	
}
?>
<?php
require_once node_root.'node_css.php';

class outline_base extends node_css{
	var $color_options=array(
		'invert',
		'color'
	);
	var $style_options=array(
		'none',
		'dotted',
		'dashed',
		'solid',
		'double',
		'groove',
		'ridge',
		'inset',
		'outset'
	);
	var $width_options=array(
		'thin',
		'medium',
		'thick',
		'length'
	);

	var $color='invert';
	var $cinput='';
	var $style='none';
	var $width='length';
	var $length=0;
	var $measure='px';
	
	function __construct(){
		parent::__construct();
	}
}
?>
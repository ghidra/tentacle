<?php
require_once node_root.'font_base.php';

class font_style extends font_base{
	var $type='font_style';
	var $font_style_ports=array('font style attributes'=>array(
			'style'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('font_style_ports');
		$this->append_ignore(array('variant','variant_options','weight','weight_options','size','size_options','slength','smeasure','smeasure_options','llength','lmeasure','lmeasure_options','family','family_options'));
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result'].='font-style:'.$data['style'].';';
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
<?php
require_once node_root.'font_base.php';

class font_variant extends font_base{
	var $type='font_variant';
	var $font_variant_ports=array('font variant attributes'=>array(
			'variant'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('font_variant_ports');
		$this->append_ignore(array('style','style_options','weight','weight_options','size','size_options','slength','smeasure','smeasure_options','llength','lmeasure','lmeasure_options','family','family_options'));
		parent::__construct();
	}
	function render($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		$nodes[$data['index']]['result'].='font-variant:'.$data['variant'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
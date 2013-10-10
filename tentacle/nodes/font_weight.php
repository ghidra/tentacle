<?php
require_once node_root.'font_base.php';

class font_weight extends font_base{
	var $type='font_weight';
	var $font_weight_ports=array('font weight attributes'=>array(
			'weight'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('font_weight_ports');
		$this->append_ignore(array('style','style_options','variant','variant_options','size','size_options','slength','smeasure','smeasure_options','llength','lmeasure','lmeasure_options','family','family_options'));
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='font-weight:'.$data['weight'];
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
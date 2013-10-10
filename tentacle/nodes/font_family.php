<?php
require_once node_root.'font_base.php';

class font_family extends font_base{
	var $type='font_family';
	var $font_family_ports=array('font family attributes'=>array(
			'family'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('font_family_ports');
		$this->append_ignore(array('style','style_options','variant','variant_options','weight','weight_options','size','size_options','slength','smeasure','smeasure_options','llength','lmeasure','lmeasure_options'));
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result'].='font-family:'.$this->family_options[$data['family']].';';	
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
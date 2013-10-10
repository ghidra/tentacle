<?php
//this node is seperate from the border bas properties
require_once node_root.'node_css.php';

class border_collapse extends node_css{
	var $type='border_colapse';
	var $style='collapse';
	var $style_options=array('collapse','seperate','inherit');

	var $border_collapse_ports = array('border collapse attributes'=>array(
							'style'=>array('type'=>'dropdown','exposed'=>0)
							));
	
	function __construct(){
		$this->append('border_collapse_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='border-collapse:'.$data['style'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
<?php
require_once node_root.'outline_base.php';

class outline_style extends outline_base{
	var $type='outline_style';
	var $outline_style_ports=array('outline style attributes'=>array(
			'style'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('outline_style_ports');
		$this->append_ignore(array('color','color_options','cinput','width','width_options','length','measure'));
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='outline-style:'.$data['style'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
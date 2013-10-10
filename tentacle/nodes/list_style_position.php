<?php
require_once node_root.'list_style_base.php';

class list_style_position extends list_style_base{
	var $type='list_style_position';
	var $list_style_position_ports = array('list style position attributes'=>array(
			'position'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('list_style_position_ports');
		$this->append_ignore('stype','stype_options');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='list-style-position:'.$data['position'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
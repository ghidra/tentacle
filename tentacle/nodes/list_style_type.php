<?php
require_once node_root.'list_style_base.php';

class list_style_type extends list_style_base{
	var $type='list_style_type';
	var $list_style_type_ports = array('list style type attributes'=>array(
			'stype'=>array('type'=>'dropdown','exposed'=>0)
		));

	function __construct(){
		$this->append('list_style_type_ports');
		$this->append_ignore('position','position_options');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='list-style-type:'.$data['stype'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
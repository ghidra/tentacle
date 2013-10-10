<?php
//require_once 'list_style_base.php';
require_once node_root.'list_style_base.php';

class list_style extends list_style_base{
	var $type='list_type';
	var $list_style_ports=array('list style attributes'=>array(
			'stype'=>array('type'=>'dropdown','exposed'=>0),
			'position'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('list_style_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='list-style:'.$data['stype'].' '.$data['position'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
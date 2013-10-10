<?php
require_once node_root.'background_base.php';

class background_position extends background_base{
	var $type='background_position';
	var $background_position_ports = array('background position attributes'=>array(
							'position'=>array('type'=>'dropdown','exposed'=>0)
						));

	function __construct(){
		$this->append('background_position_ports');
		$this->append_ignore(array('color','image','repeat','repeat_options','attachment','attachment_options'));//not using these right now
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='background-position:'.$data['position'].';';
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
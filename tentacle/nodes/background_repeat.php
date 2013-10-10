<?php
require_once node_root.'background_base.php';

class background_repeat extends background_base{
	var $type='background_repeat';
	var $background_repeat_ports = array('background repeat attributes'=>array(
							'repeat'=>array('type'=>'dropdown','exposed'=>0)
						));

	function __construct(){
		$this->append('background_repeat_ports');
		$this->append_ignore(array('color','image','attachment','attachment_options','position','position_options'));//not using these right now
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='background-repeat:'.$data['repeat'].';';
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
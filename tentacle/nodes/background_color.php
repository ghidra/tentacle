<?php
require_once node_root.'background_base.php';

class background_color extends background_base{
	var $type='background_color';
	var $background_color_ports = array('background color attributes'=>array(
							'color'=>array('type'=>'color','exposed'=>0)
						));

	function __construct(){
		$this->append('background_color_ports');
		$this->append_ignore(array('image','repeat','repeat_options','attachment','attachment_options','position','position_options'));//not using these right now
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='background-color:'.$data['color'].';';
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
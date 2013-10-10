<?php
require_once node_root.'background_base.php';

class background_attachment extends background_base{
	var $type='background_attachment';
	var $background_attachment_ports = array('background attachment attributes'=>array(
							'attachment'=>array('type'=>'dropdown','exposed'=>0)
						));

	function __construct(){
		$this->append('background_attachment_ports');
		$this->append_ignore(array('color','image','repeat','repeat_options','position','position_options'));//not using these right now
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='background-attachment:'.$data['attachment'].';';
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
<?php
require_once node_root.'node.php';

class text_short extends node{

	var $type='text_short';
	var $number_ports=0;
	var $string ='';

	var $text_short_ports = array('text short attributes'=>array(
							'string'=>array('type'=>'string','exposed'=>0)
						));

	function __construct(){
		$this->append('text_short_ports');
	}

	function render($data,$nodes){
		$nodes[$data['index']]['result'] = $this->get_port_data($data,$nodes,'string');
		return $nodes[$data['index']];
	}
}
?>
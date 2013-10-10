<?php
require_once node_root.'node.php';

class text_long extends node{
	var $type='text_long';
	var $number_ports=0;
	var $text_long_ports = array('text long attributes'=>array(
							'string'=>array('type'=>'string','exposed'=>0)
						));
	function __construct(){
		$this->append('text_long_ports');
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']=$data['string'];
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
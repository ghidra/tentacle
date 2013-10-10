<?php
require_once node_root.'node_html.php';

class br extends node_html{
	var $type='br';
	var $number_ports = 0;

	function render($data,$nodes){
		$nodes[$data['index']]['result']='<br>';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
<?php
require_once node_root.'node.php';

class execute extends node{
	
	var $type='execute';
	var $index = 'root';
	
	function render($data,$nodes){
		$result='';
		for($i=0;$i<=$data['number_ports']-1;$i++){
			$result.= $nodes[$data['content'.$i]['index']][$data['port_content'.$i]];
		}
		$nodes[$data['index']]['result']=$result;
		return $nodes[$data['index']];//return the entire node, with the result
	}
	
}
?>
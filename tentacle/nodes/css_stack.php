<?php
require_once node_root.'node_css.php';

class css_stack extends node_css{

	var $type='css_stack';
	var $number_ports=1;
	
	function __construct(){
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='';
		for($i=0;$i<=$data['number_ports']-1;$i++){
			$nodes[$data['index']]['result'].= $nodes[$data['content'.$i]['index']][$data['port_content'.$i]];
		}
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
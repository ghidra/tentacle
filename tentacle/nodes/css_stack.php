<?php
require_once node_root.'node_css.php';

class css_stack extends node_css{

	var $type='css_stack';
	var $number_ports=1;

	function __construct(){
		parent::__construct();
	}
	//this node is also having some issues
	function render($data,$nodes){
			$nodes[$data['index']]['result']='';
			for($i=0;$i<=$data['number_ports']-1;$i++){
				if(array_key_exists('result',$nodes[$data['index']])) {

					//$nodes[$data['index']]['result'].= $this->check_array_value($nodes, $data['content'.$i]['index'], $data['port_content'.$i] );

					$nodes[$data['index']]['result'].= $nodes[$data['content'.$i]['index']][$data['port_content'.$i]];
				}
		}
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>

<?php
require_once node_root.'node_html.php';

class head_stack extends node_html{
	var $type='head_stack';
	
	function __construct(){
		$this->append_ignore( array('id','class','title','style','accesskey','tabindex','dir','lang','xmllang') );
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
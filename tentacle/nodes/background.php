<?php
require_once node_root.'background_base.php';

class background extends background_base{

	function __construct(){
		$this->append('background_base_ports');
		parent::__construct();
	}

	function render($data,$nodes){		
		//$nodes[$data['index']]['result']='background-image:url(\''.$clean_link.'\');';
		$break_html = explode( '=', $nodes [$data['image']['index']] [$data['port_image']] );
		$clean_link=substr($break_html[1],0,-1);
		
		$nodes[$data['index']]['result'].='background:'.$data['color'].' url('.$clean_link.') '.$data['repeat'].' '.$data['attachment'].' '.$data['position'].';';
	
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
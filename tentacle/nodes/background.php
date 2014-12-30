<?php
require_once node_root.'background_base.php';

class background extends background_base{

	function __construct(){
		$this->append('background_base_ports');
		parent::__construct();
	}

	//alright, this node is fundamentally borken basically

	function render($data,$nodes){
		//$nodes[$data['index']]['result']='background-image:url(\''.$clean_link.'\');';
		//$break_html = explode( '=', $this->check_array_value($nodes, $data['image']['index'], array_key_exists('port_image',$data) ) );
		$break_html = explode( '=', $nodes[$data['image']['index']][ $data['port_image'] ] );
		//if(count($break_html)>1){
			$clean_link=substr($break_html[1],0,-1);
			$nodes[$data['index']]['result'].='background:'.$data['color'].' url('.$clean_link.') '.$data['repeat'].' '.$data['attachment'].' '.$data['position'].';';
		//}else{
		//	$nodes[$data['index']]['result'].='';
		//}
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>

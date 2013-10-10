<?php
require_once node_root.'border_base.php';

class border extends border_base{
	var $type='border';
	function __construct(){
		$this->append('border_base_ports');
		parent::__construct();
		$this->keep_measure();
	}
	function render($data,$nodes){
		$break_html=explode('=',$nodes[$data['image']['index']][$data['port_image']]);
		$clean_link=substr($break_html[1],0,-1);
		
		$nodes[$data['index']]['result']=$data['effect'].': ';//set the css tag thing border, border-bottom
		if($data['width']=='length'){
			$nodes[$data['index']]['result'].=$data['length'].$data['measure'].' ';
		}else{
			$nodes[$data['index']]['result'].=$data['width'].' ';
		}
		$nodes[$data['index']]['result'].=$data['style'].' '.$data['color'].';';
		//$nodes[$data['index']]['result'].='background:'.$data['color'].' url('.$clean_link.') '.$data['repeat'].' '.$data['attachment'].' '.$data['position'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
<?php
require_once node_root.'outline_base.php';

class outline_width extends outline_base{
	var $type='outline_width';
	var $outline_width_ports = array('outline width'=>array(
			'width'=>array('type'=>'dropdown','exposed'=>0),
			'length'=>array('type'=>'scalar','exposed'=>0),
			'measure'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('outline_width_ports');
		$this->append_ignore(array('color','color_options','cinput','style','style_options'));
		parent::__construct();
		$this->keep_measure();
	}
	function render($data,$nodes){
		$s='outline-width:';
		if($data['width']=='length'){
			$s.=$data['length'].$data['measure'].';';
		}else{
			$s.=$data['width'].';';
		}
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
<?php
require_once node_root.'outline_base.php';

class outline extends outline_base{
	var $type='outline';
	var $outline_ports=array('outline attributes'=>array(
			'color'=>array('type'=>'dropdown','exposed'=>0),
			'cinput'=>array('type'=>'color','exposed'=>0),
			'style'=>array('type'=>'dropdown','exposed'=>0),
			'width'=>array('type'=>'dropdown','exposed'=>0),
			'length'=>array('type'=>'scalar','exposed'=>0),
			'measure'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('outline_ports');
		parent::__construct();
		$this->keep_measure();
	}
	function render($data,$nodes){
		$s='outline:';
		if($data['color']=='color'){
			$s.=$data['cinput'].' ';
		}
		$s.=$data['style'].' ';
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
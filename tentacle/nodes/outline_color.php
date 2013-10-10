<?php
require_once node_root.'outline_base.php';

class outline_color extends outline_base{
	var $type='outline_color';
	var $outline_color_ports=array('outline color ports'=>array(
			'color'=>array('type'=>'dropdown','exposed'=>0),
			'cinput'=>array('type'=>'color','exposed'=>0)
		));
	function __construct(){
		$this->append('outline_color_ports');
		$this->append_ignore(array('style','style_options','width','width_options','length','measure'));
		parent::__construct();
	}
	function render($data,$nodes){
		$s='outline-color:';
		if($data['color']=='color'){
			$s.=$data['cinput'].';';
		}else{
			$s.=$data['color'].';';
		}
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
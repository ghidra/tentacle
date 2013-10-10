<?php
require_once node_root.'border_base.php';

class border_color extends border_base{
	var $type='border_color';
	var $border_color_ports = array('border collapse attributes'=>array(
							'effect'=>array('type'=>'dropdown','exposed'=>0),
							'color'=>array('type'=>'color','exposed'=>0)
							));

	function __construct(){
		$this->append('border_color_ports');
		$this->append_ignore(array('border_base_ports','width','width_options','length','style','style_options','measure'));//not using these right now
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']=$data['effect'].'-color:'.$data['color'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
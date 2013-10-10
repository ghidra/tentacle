<?php
//require_once 'border_base.php';
require_once node_root.'border_base.php';

class border_style extends border_base{
	var $type='border_style';
	var $border_style_ports=array('border style attributes'=>array(
							'effect'=>array('type'=>'dropdown','exposed'=>0),
							'style'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('border_style_ports');
		$this->append_ignore(array('border_base_ports','width','width_options','length','color','measure'));

		parent::__construct();
	}
	function render($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		$nodes[$data['index']]['result']=$data['effect'].'-style:'.$data['style'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
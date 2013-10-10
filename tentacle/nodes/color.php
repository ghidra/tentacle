<?php
require_once node_root.'node_css.php';

class color extends node_css{
	var $type='color';
	var $color='';

	var $color_ports = array('color attributes'=>array(
			'color'=>array('type'=>'color','exposed'=>0)
		));
	
	function __construct(){
		$this->append('color_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='color:'.$data['color'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
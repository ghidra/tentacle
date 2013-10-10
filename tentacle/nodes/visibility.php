<?php
require_once node_root.'node_css.php';

class visibility extends node_css{
	var $type='visibility';
	
	var $visibility_options=array('visible','hidden','collapse');
	var $visibility='visible';
	
	var $visibility_ports=array('visibility attributes'=>array(
			'visibility'=>array('type'=>'dropdown','exposed'=>0)
		));

	function __construct(){
		$this->append('visibility_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='visibility:'.$data['visibility'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
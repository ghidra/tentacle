<?php
require_once node_root.'node_css.php';

class float extends node_css{
	var $type='float';
	
	var $float_options=array('left','right','none');
	var $float='none';

	var $float_ports = array('float attributes'=>array(
			'float'=>array('type'=>'dropdown','exposed'=>0)
		));
	
	function __construct(){
		$this->append('float_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='float:'.$data['float'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
<?php
require_once node_root.'node_css.php';

class position extends node_css{
	var $type='position';
	
	var $position_options=array('static','relative','absolute','fixed');
	var $position='static';

	var $position_ports=array('position attributes'=>array(
			'position'=>array('type'=>'dropdown','exposed'=>0)
		));
	
	function __construct(){
		$this->append('position_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='position:'.$data['position'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
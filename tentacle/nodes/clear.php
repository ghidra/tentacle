<?php
require_once node_root.'node_css.php';

class clear extends node_css{
	var $type='clear';
	
	var $clear_options=array('left','right','both','none');	
	var $clear='none';

	var $clear_ports = array('clear attributes'=>array(
			'clear'=>array('type'=>'dropdown','exposed'=>0)
		));
	
	function __construct(){
		$this->append('clear_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='clear:'.$data['clear'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
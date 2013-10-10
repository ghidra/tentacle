<?php
require_once node_root.'node_css.php';

class overflow extends node_css{
	var $type='overflow';
	
	var $overflow_options=array(
		'visible',
		'hidden',
		'scroll',
		'auto'
	);
	
	var $overflow='visible';
	
	var $overflow_ports=array('overflow attributes'=>array(
			'overflow'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('overflow_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='overflow:'.$data['overflow'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
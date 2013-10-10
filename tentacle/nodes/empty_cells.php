<?php
//require_once 'table_properties_base.php';
require_once node_root.'table_properties_base.php';

class empty_cells extends table_properties_base{
	var $type='empty_cells';
	var $empty_cells_ports=array('empty cells attributes'=>array(
			'cell'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('empty_cells_ports');
		$this->append_ignore(array('collapse','collapse_options','hlength','hmeasure','vlength','vmeasure','side','side_options','layout','layout_options'));
		parent::__construct();
	}
	function render($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		$nodes[$data['index']]['result']='empty-cells:'.$data['cell'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
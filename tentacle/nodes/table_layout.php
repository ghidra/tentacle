<?php
require_once node_root.'table_properties_base.php';

class table_layout extends table_properties_base{
	var $type='table_layout';
	var $table_layout_ports = array('table layout attributes'=>array(
			'layout'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('table_layout_ports');
		$this->append_ignore(array('collapse','collapse_options','hlength','hmeasure_options','hmeasure','vlength','vmeasure_options','vmeasure','side','side_options','cell','cell_options'));
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='table-layout:'.$data['layout'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
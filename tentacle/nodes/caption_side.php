<?php
//require_once 'table_properties_base.php';
require_once node_root.'table_properties_base.php';

class caption_side extends table_properties_base{
	var $type='caption_side';

	var $caption_side_ports = array('caption side attributes'=>array(
							'side'=>array('type'=>'dropdown','exposed'=>0)
							));

	function __construct(){
		$this->append('caption_side_ports');
		$this->append_ignore(array('collapse','collapse_options','hlength','hmeasure','vlength','vmeasure','cell','cell_options','layout','layout_options'));
		
		parent::__construct();
	}
	function render($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		$nodes[$data['index']]['result']='caption-side:'.$data['side'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
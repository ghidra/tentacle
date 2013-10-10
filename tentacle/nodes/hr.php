<?php
//require_once 'html_base.php';
require_once node_root.'node_html.php';

class hr extends node_html{
	var $type='hr';
	var $number_ports=0;
	function __construct(){
		parent::__construct();
	}
	function render($data,$nodes){
		//$nodes[$data['index']]['result']='<hr'.$this->get_base_attributes($data,$nodes).' />';
		//return $nodes[$data['index']];//return the entire node, with the result	
		return parent::render($data,$nodes);
	}
}
?>
<?php
require_once node_root.'node_html.php';

class map extends node_html{
	var $type='map';
	var $map_ports = array('map attributes'=>array(
			'name'=>array('type'=>'string','exposed'=>1)
		));
	function __construct(){
		$this->append('map_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		return parent::render( $data,$nodes,$this->get_attribute_assembled($data,$nodes,'name') );
	}
}
?>
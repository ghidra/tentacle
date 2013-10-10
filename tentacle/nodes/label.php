<?php
require_once node_root.'node_html.php';

class label extends node_html{
	var $type='label';
	var $label='';
	var $for='';
	var $number_ports=0;
	var $label_ports=array('label attributes'=>array(
			'label'=>array('type'=>'string','exposed'=>1),
			'for'=>array('type'=>'string','exposed'=>1)
		));
	function __construct(){
		$this->append('label_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		return parent::render( $data,$nodes,$this->get_attribute_assembled($data,$nodes,'for'),$this->get_port_data($data,$nodes,'label') );
	}
}
?>
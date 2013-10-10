<?php
//require_once 'html_base.php';
require_once node_root.'node_html.php';

class legend extends node_html{
	var $type='legend';
	var $legend='';
	var $number_ports=0;
	var $legend_ports=array('legend attributes'=>array(
			'legend'=>array('type'=>'string','exposed'=>1)
		));
	function __construct(){
		$this->append('legend_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		return parent::render( $data,$nodes,'',$this->get_port_data($data,$nodes,'legend') );
	}
}
?>
<?php
require_once node_root.'node_html.php';
class div extends node_html{
	var $type='div';
/*	var $test_ports = array('test_ports'=>array(
									'haha'=>array(
										'type'=>'string',
										'exposed'=>0
									)
								));*/
	/*function __construct(){
		//parent::__construct();
		$this->in_ports = array_merge($this->in_ports,$this->test_ports); 
	}*/
	/*function assemble(){
		//$this->index='root';
		return parent::assemble();
	}*/
	//render the node
	function render($data,$nodes){
		return parent::render($data,$nodes);
	}
}





//require_once 'html_base.php';
/*require_once node_root.'html_base.php';

class div extends html_base{
	var $type='div';
	function div(){
	}
	function assemble_node(){
		$s= $this->assemble_base_node();
		$s.=$this->content_ports();
		$s.=node_inwait('content.',$this->index,$this->type);
		return $s;
	}
	function assign_values($values){
		$this->assign_base_values($values);
	}
	function render_node($data,$nodes){
		return $this->get_tag_assembled( $data,$nodes,'');
	}
	function inspect($data){
		$data= $this->translate($data);//translate the data into an array that can be more easily used
		$s= property_header($this->index,$this->type);
		$s.=$this->inspect_base($data);
		return $s;
	}
}*/
?>
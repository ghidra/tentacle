<?php
require_once node_root.'node_html.php';

class form extends node_html{
	var $type='form';
	var $action='';
	var $path='local';
	var $method='get';
	var $path_options=array(
		'http://',
		'http://www.',
		'local'
	);
	var $method_options=array(
		'get',
		'post'
	);
	var $form_ports=array('form attributes'=>array(
			'action'=>array('type'=>'string','exposed'=>1),
			'path'=>array('type'=>'dropdown','exposed'=>0),
			'method'=>array('type'=>'dropdown','exposed'=>0),
			'name'=>array('type'=>'string','exposed'=>1)
		));
	function __construct(){
		$this->append('form_ports');
		parent::__construct();
	}
	function render($data,$nodes,$local_attributes = '', $local_inner = ''){
		//return $this->get_tag_assembled( $data,$nodes,	$this->get_attributes($data,$nodes) );
		return parent::render($data,$nodes,$this->get_attributes($data,$nodes) );
	}
	//--------
	function get_attributes($data){
		global $nodes;// this variable comes from the render.php execute function

		$s= $this->get_link($data);
		$s.=$this->get_attribute_assembled($data,'method');
		$s.=$this->get_attribute_assembled($data,'name');

		return $s;

	}
	function get_link($data){//this is basically a duplicate of parent onject get attribute asembled
		global $nodes;// this variable comes from the render.php execute function
		$s='';
		if (strlen($data['action'])>0) {
			$s.=' action="';
			if($data['path']!='local'){
				$s.=$data['path'];
			}
			if(is_string($data['action'])){
				$s.=$data['action'].'"';
			}else{
				$s.=$nodes[$data['action']['index']][$data['port_action']].'"';
			}
		}
		return $s;
	}
}
?>

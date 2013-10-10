<?php
require_once node_root.'node_html.php';

class button extends node_html{
	var $type='button';
	var $disabled='enabled';
	var $buttontype='button';
	var $value='';
	
	var $buttontype_options=array(
		'button',
		'reset',
		'submit'
	);
	var $disabled_options=array(
		'enabled',
		'disabled'
	);
	
	var $button_ports = array('button attributes'=>array(
							'disabled'=>array('type'=>'dropdown','exposed'=>0),
							'buttontype'=>array('type'=>'dropdown','exposed'=>0),
							'value'=>array('type'=>'string','exposed'=>0),
							'tabindex'=>array('type'=>'integer','exposed'=>0),
							'accesskey'=>array('type'=>'string','exposed'=>0)
						));

	function __construct(){
		$this->append('button_ports');
		$this->append('html_in_ports');
		$this->append_ignore(array('dir','lang','xmllang'));//not using these right now
		//i am doing what is nodemally done in the node html php file, but I want to keep some of these ignored vaules
		//i could probably just remove them from the ignore array, but this is how i'll do it for now
	}
	function render($data,$nodes){
		return parent::render( $data,$nodes, $this->get_attributes($data,$nodes) );
	}
	//--------
	function get_attributes($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		
		if($data['disabled']!='enabled') $s.=$this->get_attribute_assembled($data,$nodes,'disabled');
		$s.=$this->get_attribute_assembled($data,$nodes,'name');
		$s.=' type="'.$data['buttontype'].'"';
		$s.=$this->get_attribute_assembled($data,$nodes,'value');
		if($data['tabindex']!='int') $s.=$this->get_attribute_assembled($data,$nodes,'tabindex');
		$s.=$this->get_attribute_assembled($data,$nodes,'accesskey');
		
		return $s;
	
	}
}
?>
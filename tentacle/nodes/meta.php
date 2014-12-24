<?php
//require_once 'html_base.php';
require_once node_root.'node_html.php';

class meta extends node_html{
	var $type='meta';
	var $name='none';
	var $content='';
	var $httpequiv='none';

	var $name_options=array(
		'none',
		'author',
		'copyright',
		'description',
		'distribution',
		'generator',
		'keywords',
		'progid',
		'rating',
		'resource-type',
		'revisit-after',
		'robots'
	);
	var $httpequiv_options=array(
		'none',
		'content-tyoe',
		'content-style-type',
		'expires',
		'refresh',
		'set-cookie'
	);

	var $meta_ports=array('meta attributes'=>array(
			'name'=>array('type'=>'dropdown','exposed'=>0),
			'content'=>array('type'=>'string','exposed'=>1),
			'httpequiv'=>array('type'=>'dropdown','exposed'=>0),
		));

	function __construct(){
		$this->append('meta_ports');
		parent::__construct();
	}
	function render($data,$nodes,$local_attributes='',$local_inner=''){
		$nodes[$data['index']]['result']='<meta'.$this->get_attributes($data,$nodes).'/>';
		return $nodes[$data['index']];//return the entire node, with the result
	}
	//------
	function get_attributes($data,$nodes){
		$s='';
		if($data['name']!='none') $s.=' name="'.$data['name'].'"';
		if($data['httpequiv']!='none') $s.=' http-equiv="'.$data['httpequiv'].'"';
		$s.=' content="'.$this->array_value($data,'content').'"';//$data['content'] was returning errors

		return $s;

	}
}
?>

<?php
require_once node_root.'node_html.php';

class base extends node_html{
	var $type='base';
	var $href='';
	var $path='http://www.';
	var $target='_self';
	
	var $path_options=array(
		'http://',
		'http://www.',
		'local'
	);
	var $target_options=array(
		'_blank',
		'_self',
		'_parent',
		'_top',
		'framename'
	);

	var $base_ports = array('base attributes'=>array(
							'href'=>array('type'=>'string','exposed'=>1),
							'path'=>array('type'=>'dropdown','exposed'=>0),
							'target'=>array('type'=>'dropdown','exposed'=>0)
						));

	function __construct(){
		$this->append('base_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		//global $nodes;
		$s='<base';
		if(strlen($data['href'])>0){
			$s.=' href="';
			if($data['path']!='local'){
				$s.=$data['path'];
			}
			$s.=$data['href'].'"';
		}
		if($data['target']!='self') $s.=' target="'.$data['target'].'"';
		$s.='/>';
		$nodes[$data['index']]['result']=$s;
		//return $this->get_tag_assembled( $data,	$this->get_attributes($data) );
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
<?php
require_once node_root.'node_html.php';

class a extends node_html{
	var $type = 'a';
	var $href='';
	var $path='http://www.';
	var $target='self';
	var $framename='';
	var $rel='none';
	var $rev='none';

	var $path_options=array(
		'http://',
		'http://www.',
		'local'
	);
	var $target_options=array(
		'new window'=>'_blank',
		'self'=>'_self',
		'parent'=>'_parent',
		'top'=>'_top',
		'frame name'=>'framename'
	);
	var $rel_options=array(
		'none',
		'alternate',
		'stylesheet',
		'start',
		'next',
		'prev',
		'contents',
		'index',
		'glosarry',
		'copyright',
		'chapter',
		'section',
		'subsection',
		'appendix',
		'help',
		'bookmark',
		'nofollow',
		'licence',
		'tag',
		'friend'
	);

	var $a_ports = array('a attributes'=>array(
							'href'=>array('type'=>'string','exposed'=>1),
							'path'=>array('type'=>'dropdown','exposed'=>0),
							'target'=>array('type'=>'dropdown','exposed'=>0),
							'framename'=>array('type'=>'string','exposed'=>1),
							'rel'=>array('type'=>'dropdown','exposed'=>0),
							'rev'=>array('type'=>'string','exposed'=>1)
						));

	function __construct(){
		$this->append('a_ports');
		parent::__construct();
	}
	function render($data,$nodes,$local_attributes = '', $local_inner = ''){
		return parent::render($data,$nodes,$this->get_attributes($data,$nodes) );
	}

	//------------------------------
	//the following functions build the attributes that along with the base attributes in the html tag
	//------------------------------
	function get_attributes($data,$nodes){
		$s= $this->get_link($data,$nodes);
		if($data['target']!='_self') $s.=$this->get_attribute_assembled($data,$nodes,'target');
		$s.=$this->get_attribute_assembled($data,$nodes,'framename');
		$s.=$this->get_attribute_assembled($data,$nodes,'name');
		if($data['tabindex']!='int') $s.=$this->get_attribute_assembled($data,$nodes,'tabindex');
		$s.=$this->get_attribute_assembled($data,$nodes,'accesskey');
		if($data['rev']!='none') $s.=$this->get_attribute_assembled($data,$nodes,'rev');
		if($data['rel']!='none') $s.=$this->get_attribute_assembled($data,$nodes,'rel');

		return $s;
	}
	//this is some magic for parsing out a url proper
	//--------------------
	function get_link($data,$nodes){//this is basically a duplicate of parent onject get attribute asembled
		$s='';
		if (strlen($data['href'])>0) {
			$s.=' href="';
			if($data['path']!='local'){
				$s.=$data['path'];
			}
			$s.=$this->get_port_data($data,$nodes,'href').'"';
		}
		return $s;
	}
	//------------------------------
}
?>

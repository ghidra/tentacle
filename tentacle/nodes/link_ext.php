<?php
require_once node_root.'node_html.php';

class link_ext extends node_html{
	var $type='link_ext';
	var $href='';
	var $path='local';
	var $ftype='css';
	var $rel='stylesheet';
	var $rev='none';
	
	var $media_options=array(
		'screen',
		'tty',
		'tv',
		'projection',
		'handheld',
		'print',
		'braille',
		'aural',
		'all'
	);
	var $path_options=array(
		'http://',
		'http://www.',
		'local'
	);
	var $rel_options=array(
		'none',
		'icon',
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
	var $rev_options=array(
		'none',
		'icon',
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
	var $ftype_options=array(
		'css'=>'text/css',
		'xml'=>'text/xml',
		'plain text'=>'text/plain',
		'rich text'=>'text/richtext',
		'icon'=>'image/x-icon'
	);
	var $link_ext_ports=array(array(
			'href' =>array('type'=>'string','exposed'=>1),
			'path' =>array('type'=>'dropdown','exposed'=>0),
			'ftype' =>array('type'=>'dropdown','exposed'=>0),
			'rel' =>array('type'=>'dropdown','exposed'=>0),
			'rev' =>array('type'=>'dropdown','exposed'=>0),
		));
	function __construct(){
		$this->append('link_ext_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='<link '.$this->get_attributes($data,$nodes).$this->get_base_attributes($data,$nodes).' />';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
	//------
	function get_attributes($data,$nodes){		
		$s= $this->get_link($data,$nodes);
		if($data['target']!='_self') $s.=$this->get_attribute_assembled($data,$nodes,'target');//undifined index 'target'
		//$s.=$this->get_attribute_assembled($data,$nodes,'target');
		$s.=' type="'.$this->type_options[$data['ftype']].'"';
		if($data['rev']!='none') $s.=$this->get_attribute_assembled($data,$nodes,'rev');
		if($data['rel']!='none') $s.=$this->get_attribute_assembled($data,$nodes,'rel');
		return $s;
	}
	function get_link($data,$nodes){//this is basically a duplicate of parent onject get attribute asembled
		$s='';
		if (strlen($data['href'])>0) {
			$s.=' href="';
			if($data['path']!='local'){
				$s.=$data['path'];
			}
			if(is_string($data['href'])){
				$s.=$data['href'].'"';
			}else{
				$s.=$nodes[$data['href']['index']][$data['port_href']].'"';	
			}
		}
		return $s;
	}
}
?>
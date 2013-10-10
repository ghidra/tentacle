<?php
require_once node_root.'node_html.php';

class lists extends node_html{
	var $type='lists';
	var $lists='definition';
	var $lists_options=array(
		'definition'=>'dl',
		'ordered'=>'ol',
		'unordered'=>'ul'
	);
	var $lists_ports=array('lists attributes'=>array(
			'lists'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('lists_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$s='<'.$this->list_options[$data['lists']].$this->get_base_attributes($data,$nodes).'>';
		
		for($i=0;$i<=$data['number_ports']-1;$i++){//from ports
			$s.= $nodes[$data['content'.$i]['index']][$data['port_content'.$i]];
		}
		$s.='</'.$this->list_options[$data['lists']].'>';
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result		
	}
}
?>
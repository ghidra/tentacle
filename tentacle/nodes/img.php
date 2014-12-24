<?php
require_once node_root.'node_html.php';

class img extends node_html{
	var $type='img';

	var $href='';
	var $path='local';

	var $alt='';

	var $path_options=array(
		'http://',
		'http://www.',
		'local'
	);
	var $number_ports=0;

	var $img_ports=array('img attributes'=>array(
			'href'=>array('type'=>'string','exposed'=>1),
			'path'=>array('type'=>'dropdown','exposed'=>0),
			'alt'=>array('type'=>'string','exposed'=>1)
		));

	function __construct(){
		$this->append('img_ports');
		parent::__construct();
	}
	function render($data,$nodes,$local_attributes = '', $local_inner = ''){
		$attr=$this->get_base_attributes($data,$nodes);
		$nodes[$data['index']]['result']='<img'.$attr.' src="'.$this->get_link($data,$nodes).'" alt="'.$data['alt'].'">';
		return $nodes[$data['index']];
	}
	//--------
	function get_link($data,$nodes){//this is basically a duplicate of parent onject get attribute asembled
		$s='';
		if (strlen($data['href'])>0) {
			if($data['path']!='local'){
				$s.=$data['path'];
			}
			if(is_string($data['href'])){
				$s.=$data['href'];
			}else{
				$s.=$nodes[$data['href']['index']][$data['port_href']];
			}
		}
		return $s;
	}
}
?>

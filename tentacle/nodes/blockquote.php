<?php
require_once node_root.'node_html.php';

class blockquote extends node_html{
	var $type='blockquote';
	var $cite='';
	var $path='http://www.';

	var $path_options=array(
		'http://',
		'http://www.',
		'local'
	);

	var $blockquote_ports = array('blockquote attributes'=>array(
							'cite'=>array('type'=>'string','exposed'=>1),
							'path'=>array('type'=>'dropdown','exposed'=>0)
						));

	function __construct(){
		$this->append('blockquote_ports');
		parent::__construct();
	}
	function render($data,$nodes,$local_attributes = '', $local_inner = ''){
		//$this->get_tag_assembled( $data, $this->get_link($data,$nodes) );
		return parent::render($data,$nodes,$this->get_link($data,$nodes) );

		//return $nodes[$data['index']];//return the entire node, with the result
	}
	//--------------
	function get_link($data,$nodes){//this is basically a duplicate of parent onject get attribute asembled
		$s='';
		if (strlen($data['cite'])>0) {
			$s.=' cite="';
			if($data['path']!='local'){
				$s.=$data['path'];
			}
			if(is_string($data['cite'])){
				$s.=$data['cite'].'"';
			}else{
				$s.=$nodes[$data['cite']['index']][$data['port_cite']].'"';
			}
		}
		return $s;
	}
}
?>

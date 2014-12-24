<?php
require_once node_root.'node_html.php';

class script extends node_html{
	var $type='script';
	var $src='';
	var $stype='javascript';
	var $body='';

	var $number_ports=0;

	var $stype_options=array(
		'javascript'=>'text/javascript'
	);

	var $script_ports = array('script attributes'=>array(
			'src'=>array('type'=>'string','exposed'=>1),
			'stype'=>array('type'=>'dropdown','exposed'=>0),
			'body'=>array('type'=>'string','exposed'=>1)
		));

	function __construct(){
		$this->append('script_ports');
		$this->append_ignore(array('id','class','title','style','accesskey','tabindex','dir','lang','xmllang'));
		//parent::__construct();
	}
	function render($data,$nodes,$local_attributes = '', $local_inner = ''){
		if(strlen($data['src'])>0){
			$s=' src="'.$data['src'].'"';
		}else{
			$s='';
		}
		$nodes[$data['index']]['result']='<script type="'.$this->stype_options[$data['stype']].'"'.$s.'>'.$data['body'].'</script>';
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>

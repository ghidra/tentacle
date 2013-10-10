<?php
require_once node_root.'node_html.php';

class abbr extends node_html{
	var $type='abbr';
	var $full='';
	
	var $abbr_ports = array('abbr attributes'=>array(
							'full'=>array('type'=>'string','exposed'=>1)
						));

	function __construct(){
		$this->append('abbr_ports');
		parent::__construct();
	}

	function render($data,$nodes){
		return parent::render($data,$nodes,'',$this-get_port_data($data,$nodes,'full'));
	}

}
?>
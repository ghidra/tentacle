<?php
require_once node_root.'node_html.php';

class acronym extends node_html{
	var $type='acronym';
	var $full='';

	var $acronym_ports = array('acronym attributes'=>array(
							'full'=>array('type'=>'string','exposed'=>1)
						));

	function __construct(){
		$this->append('acronym_ports');
		parent::__construct();
	}

	function render($data,$nodes){
		return parent::render($data,$nodes,'',$this-get_port_data($data,$nodes,'full'));
	}
	
}
?>
<?php
require_once node_root.'node_html.php';

class fieldset extends node_html{
	var $type='fieldset';
	
	function render($data,$nodes){
		return parent::render($data,$nodes);
		//return $this->get_tag_assembled($data,$nodes,'');
	}
}
?>
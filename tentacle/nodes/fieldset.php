<?php
require_once node_root.'node_html.php';

class fieldset extends node_html{
	var $type='fieldset';

	function render($data,$nodes,$local_attributes = '', $local_inner = ''){
		return parent::render($data,$nodes);
		//return $this->get_tag_assembled($data,$nodes,'');
	}
}
?>

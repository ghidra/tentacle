<?php
require_once node_root.'node_html.php';

class address extends node_html{
	var $type='address';

	function render($data,$nodes,$local_attributes = '', $local_inner = ''){
		return parent::render($data,$nodes);
	}

}
?>

<?php
require_once node_root.'background_base.php';

class background_image extends background_base{
	var $type='background_image';
	var $background_image_ports = array('background image attributes'=>array(
							'image'=>array('type'=>'string','exposed'=>1)
						));

	function __construct(){
		$this->append('background_image_ports');
		$this->append_ignore(array('color','repeat','repeat_options','attachment','attachment_options','position','position_options'));//not using these right now
		parent::__construct();
	}
	function render($data,$nodes){
		$break_html=explode('=',$nodes[$data['image']['index']][$data['port_image']]);
		$clean_link=substr($break_html[1],0,-1);//cut that last character off, the ">"аа
		$nodes[$data['index']]['result']='background-image:url(\''.$clean_link.'\');';

		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
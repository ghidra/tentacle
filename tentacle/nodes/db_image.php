<?php
//i used to have a way to show the thumbnail on the node, but now I don't 
//i need to look into that
require_once node_root.'node_html.php';

class db_image extends node_html{

	var $type='db_image';
	var $link='';
	
	var $db_image_ports = array('database image attributes'=>array(
			'link'=>array('type'=>'string','exposed'=>1)
			));

	function __construct(){
		$this->append('db_image_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='<img'.$this->get_base_attributes($data,$nodes).' src="media/images/'.$data['link'].'">';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
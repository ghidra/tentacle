<?php
//require_once tentacle_root.'html.php';
require_once node_root.'node_html.php';

class db_movie extends node_html{
	var $type='db_movie';
	var $link='';
	var $width='720';
	var $height='480';

	var $db_movie_ports = array('database movie attributes'=>array(
			'link'=>array('type'=>'string','exposed'=>1),
			'width'=>array('type'=>'integer','exposed'=>1),
			'heigth'=>array('type'=>'integer','exposed'=>1)
			));

	function __construct(){
		$this->append('db_movie_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$tag_data=$this->get_base_attributes($data,$nodes);//this assembles the tag data, but I'm not actually using it here yet

		$link=explode('/',$data['link']);
		//build the wrap part
		$s='<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width=320 height=256 >';
		$s.='<param name="src" value=../media/mov/'.$link[1].' >';
		$s.='<param name="autoplay" value="false">';
		$s.='<param name="controller" value="true">';
		$s.='<embed src=../media/mov/'.$link[1].' width=320 height=256 autoplay="false" controller="true" border="0" pluginspage="http://www.apple.com/quicktime/download/""></embed>';		
		$s.='</object>';
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
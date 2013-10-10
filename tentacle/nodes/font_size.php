<?php
require_once node_root.'font_base.php';

class font_size extends font_base{
	var $type='font_size';
	var $font_size_ports=array('font size attributes'=>array(
			'size'=>array('type'=>'dropdown','exposed'=>0),
			'slength'=>array('type'=>'scalar','exposed'=>0),
			'smeasure'=>array('type'=>'dropdown','exposed'=>0),
		));
	function __construct(){
		$this->append('font_size_ports');
		$this->append_ignore(array('style','style_options','variant','variant_options','weight','weight_options','llength','lmeasure','lmeasure_options','family','family_options'));
		parent::__construct();
	}
	function render($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		
		$nodes[$data['index']]['result']='font-size:';
		if($data['size']=='length'){
			$nodes[$data['index']]['result'].=$data['slength'].$data['smeasure'].';';
		}else{
			$nodes[$data['index']]['result'].=$data['size'].';';
		}
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
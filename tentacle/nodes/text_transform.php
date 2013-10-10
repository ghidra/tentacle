<?php
require_once node_root.'text_base.php';

class text_transform extends text_base{
	var $type='text_transform ';
	var $text_transform_ports=array('text transform attributes'=>array(
			'transform'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('text_transform_ports');
		$this->append_ignore(array('color','direction','direction_options','lineheight','lineheight_options','llength','lmeasure_options','lmeasure','letterspacing','letterspacing_options','lslength','lsmeasure_options','lsmeasure','align','align_options','decoration','decoration_options','indent','imeasure_options','imeasure','white','white_options','wordspacing','wordspacing_options','wlength','wmeasure_options','wmeasure'));
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='text-transform:'.$data['transform'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
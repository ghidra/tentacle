<?php
require_once node_root.'text_base.php';

class text_decoration extends text_base{
	var $type='text_decoration';
	var $text_decoration_ports=array('text decoration attributes'=>array(
			'decoration'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('text_decoration_ports');
		$this->append_ignore(array('color','direction','direction_options','lineheight','lineheight_options','llength','lmeasure_options','lmeasure','letterspacing','letterspacing_options','lslength','lsmeasure_options','lsmeasure','align','align_options','indent','imeasure_options','imeasure','transform','transform_options','white','white_options','wordspacing','wordspacing_options','wlength','wmeasure_options','wmeasure'));
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='text-decoration:'.$data['decoration'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
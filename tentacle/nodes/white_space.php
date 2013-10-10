<?php
require_once node_root.'text_base.php';

class white_space extends text_base{
	var $type='white_space';
	var $white_space_ports=array('white space attributes'=>array(
			'white'=>array('type'=>'dropdown','exposed'=>0)
		));

	function __construct(){
		$this->append('white_space_ports');
		$this->append_ignore(array('color','direction','direction_options','lineheight','lineheight_options','llength','lmeasure_options','lmeasure','letterspacing','letterspacing_options','lslength','lsmeasure_options','lsmeasure','align','align_options','decoration','decoration_options','indent','imeasure_options','imeasure','transform','transform_options','wordspacing','wordspacing_options','wlength','wmeasure_options','wmeasure'));
		parent::__construct();
	}
	function render($data,$nodes){		
		$s.='white-space:'.$data['white'].';';
		
		$nodes[$data['index']]['result']=$s;//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result
	
	}
}
?>
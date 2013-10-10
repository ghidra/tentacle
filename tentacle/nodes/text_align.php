<?php
require_once node_root.'text_base.php';

class text_align extends text_base{
	var $type='text_align';
	var $text_align_ports=array('rext align attributes'=>array(
			'align'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('text_align_ports');
		$this->append_ignore(array('color','direction','direction_options','lineheight','lineheight_options','llength','lmeasure_options','lmeasure','letterspacing','letterspacing_options','lslength','lsmeasure_options','lsmeasure','decoration','decoration_options','indent','imeasure_options','imeasure','transform','transform_options','white','white_options','wordspacing','wordspacing_options','wlength','wmeasure_options','wmeasure'));
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='text-align:'.$data['align'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
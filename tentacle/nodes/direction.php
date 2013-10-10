<?php
require_once node_root.'text_base.php';

class direction extends text_base{
	var $type='direction';
	var $direction='rtl';//force it to only usealbe option

	var $direction_ports=array('direction attributes'=>array(
			'direction'=>array('type'=>'dropdown','exposed'=>0)
		));

	function __construct(){
		$this->append('direction_ports');
		$this->append_ignore(array('color','lineheight','lineheight_options','llength','lmeasure_options','lmeasure','letterspacing','letterspacing_options','lslength','lsmeasure_options','lsmeasure','align','align_options','decoration','decoration_options','indent','imeasure_options','imeasure','transform','transform_options','white','white_options','wordspacing','wordspacing_options','wlength','wmeasure_options','wmeasure'));
		parent::__construct();
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']='direction:'.$data['direction'].';';	
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
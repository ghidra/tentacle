<?php
require_once node_root.'text_base.php';

class line_height extends text_base{
	var $type='line_height';
	var $line_height_ports=array('line height attributes'=>array(
			'lineheight'=>array('type'=>'dropdown','exposed'=>0),
			'llength'=>array('type'=>'scalar','exposed'=>0),
			'lmeasure'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('line_height_ports');
		$this->append_ignore(array('color','direction','direction_options','letterspacing','letterspacing_options','lslength','lsmeasure_options','lsmeasure','align','align_options','decoration','decoration_options','indent','imeasure_options','imeasure','transform','transform_options','white','white_options','wordspacing','wordspacing_options','wlength','wmeasure_options','wmeasure'));
		parent::__construct();
	}
	function render($data,$nodes){		
		$s='line-height:';
		
		if($data['lineheight']!='normal'){	
			$s.=$data['llength'];
			if($data['lineheight']=='length'){
				$s.=$data['lmeasure'];
			}
			$s.=';';
		}else{
			$s.='normal;';
		}
		
		$nodes[$data['index']]['result']=$s;//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
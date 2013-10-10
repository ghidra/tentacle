<?php
require_once node_root.'text_base.php';

class text_properties extends text_base{
	var $type='text_properties';
	var $text_properties_ports=array('text properties attributes'=>array(
			'color'=>array('type'=>'color','exposed'=>0),
			'direction'=>array('type'=>'dropdown','exposed'=>0),
			'lineheight'=>array('type'=>'dropdown','exposed'=>0),
			'llength'=>array('type'=>'scalar','exposed'=>0),
			'lmeasure'=>array('type'=>'dropdown','exposed'=>0),
			'letterspacing'=>array('type'=>'dropdown','exposed'=>0),
			'lslength'=>array('type'=>'scalar','exposed'=>0),
			'align'=>array('type'=>'dropdown','exposed'=>0),
			'decoration'=>array('type'=>'dropdown','exposed'=>0),
			'indent'=>array('type'=>'scalar','exposed'=>0),
			'imeasure'=>array('type'=>'dropdown','exposed'=>0),
			'transform'=>array('type'=>'dropdown','exposed'=>0),
			'white'=>array('type'=>'dropdown','exposed'=>0),
			'wordspacing'=>array('type'=>'dropdown','exposed'=>0),
			'wlength'=>array('type'=>'scalar','exposed'=>0),
			'wmeasure'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('text_properties_ports');
		parent::__construct();
	}
	function render($data,$nodes){		
		$s='';
		
		if(strlen($data['color'])>0){
			$s.='color:'.$data['color'].';';
		}
		if($data['direction'] != 'ltr'){
			$s.='direction:'.$data['direction'].';';
		}
		if($data['lineheight']!='normal'){	
			$s.='line-height:'.$data['llength'];
			if($data['lineheight']=='length'){
				$s.=$data['lmeasure'];
			}
			$s.=';';
		}
		if($data['letterspacing']!='normal'){
			$s.='letter-spacing:'.$data['lslength'].$data['lsmeasure'].';';
		}
		if($data['align']!='left'){
			$s.='text-align:'.$data['align'].';';
		}
		
		$s.='text-decoration:'.$data['decoration'].';';
		
		if($data['indent']>0){
			$s.='text-indent:'.$data['indent'].$data['imeasure'].';';
		}
		if($data['transform']!='none'){
			$s.='text-transform:'.$data['transform'].';';
		}
		if($data['white']!='normal'){
			$s.='white-space:'.$data['white'].';';
		}
		if($data['wordspacing']!='normal'){
			$s.='word-spacing:'.$data['wlength'].$data['wmeasure'].';';
		}
		
		$nodes[$data['index']]['result']=$s;//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
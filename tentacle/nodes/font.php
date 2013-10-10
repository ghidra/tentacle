<?php
require_once node_root.'font_base.php';

class font extends font_base{
	var $font_ports=array('font attributes'=>array(
			'style'=>array('type'=>'dropdown','exposed'=>0),
			'variant'=>array('type'=>'dropdown','exposed'=>0),
			'weight'=>array('type'=>'dropdown','exposed'=>0),
			'size'=>array('type'=>'dropdown','exposed'=>0),
			'slength'=>array('type'=>'scalar','exposed'=>0),
			'smeasure'=>array('type'=>'dropdown','exposed'=>0),
			'llength'=>array('type'=>'scalar','exposed'=>0),
			'lmeasure'=>array('type'=>'dropdown','exposed'=>0),
			'family'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('font_ports');
		parent::__construct();
	}
	function render($data,$nodes){		
		$s='font: ';//set the css tag thing border, border-bottom
		
		if($data['style']!='normal'){
			$s.=$data['style'].' ';
		}
		if($data['variant']!='normal'){
			$s.=$data['variant'].' ';
		}
		if($data['weight']!='normal'){
			$s.=$data['weight'].' ';
		}
		if($data['size']=='length'){
			$s.=$data['slength'].$data['smeasure'].' ';
		}else{
			$s.=$data['size'].' ';
		}
		//i'm leaving out lineheight length for now
		$s.=$this->family_options[$data['family']].';';
		
		$nodes[$data['index']]['result'].=$s;
		//$nodes[$data['index']]['result'].='background:'.$data['color'].' url('.$clean_link.') '.$data['repeat'].' '.$data['attachment'].' '.$data['position'].';';
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
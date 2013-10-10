<?php
require_once node_root.'node_css.php';

class clip extends node_css{
	
	var $type = 'clip';

	var $tmeasure_options=array();
	var $tmeasure='px';
	var $tlength=0;

	var $rmeasure_options=array();
	var $rmeasure='px';
	var $rlength=0;

	var $bmeasure_options=array();
	var $bmeasure='px';
	var $blength=0;

	var $lmeasure_options=array();
	var $lmeasure='px';
	var $llength=0;

	var $clip_ports = array('clip attributes'=>array(
							'tlength'=>array('type'=>'scalar','exposed'=>0),
							'tmeasure'=>array('type'=>'dropdown','exposed'=>0),
							'rlength'=>array('type'=>'scalar','exposed'=>0),
							'rmeasure'=>array('type'=>'dropdown','exposed'=>0),
							'blength'=>array('type'=>'scalar','exposed'=>0),
							'bmeasure'=>array('type'=>'dropdown','exposed'=>0),
							'llength'=>array('type'=>'scalar','exposed'=>0),
							'lmeasure'=>array('type'=>'dropdown','exposed'=>0)
		));
	
	function __construct(){
		$this->tmeasure_options=$this->clone_measure();
		$this->rmeasure_options=$this->clone_measure();
		$this->bmeasure_options=$this->clone_measure();
		$this->lmeasure_options=$this->clone_measure();

		$this->append('clip_ports');
		
		parent::__construct();
	}
	//----------------
	function render($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function

		$s='clip:rect(';
		if ($data['tmeasure'] == $data['rmeasure'] && $data['tmeasure'] == $data['bmeasure'] && $data['tmeasure'] == $data['lmeasure']){//all the measures are the same
			if ($data['tlength'] == $data['rlength'] && $data['tlength'] == $data['blength'] && $data['tlength'] == $data['rlength']){//all the measures are the same
				$s.=$data['tlength'].$data['tmeasure'].');';//set them all at once
			}else{
				$s.=$data['tlength'].' '.$data['rlength'].' '.$data['blength'].' '.$data['llength'].$data['tmeasure'].');';//only put the measure once
			}
		}else{
			$s.=$data['tlength'].$data['tmeasure'].' '.$data['rlength'].$data['rmeasure'].' '.$data['blength'].$data['bmeasure'].' '.$data['llength'].$data['lmeasure'].');';
		} 
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result	
	}
	//----------------	
}
?>
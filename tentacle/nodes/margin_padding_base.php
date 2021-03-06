<?php
require_once node_root.'node_css.php';

class margin_padding_base extends node_css{	
	var $tmeasure='px';
	var $tlength=0;
	var $tmeasure_options=array();

	var $rmeasure='px';
	var $rlength=0;
	var $rmeasure_options=array();

	var $bmeasure='px';
	var $blength=0;
	var $bmeasure_options=array();

	var $lmeasure='px';
	var $llength=0;
	var $lmeasure_options=array();

	var $margin_padding_ports = array('maring padding attributes'=>array(
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
		$this->append('margin_padding_ports');

		$this->tmeasure_options = $this->clone_measure();
		$this->rmeasure_options = $this->clone_measure();
		$this->bmeasure_options = $this->clone_measure();
		$this->lmeasure_options = $this->clone_measure();

		parent::__construct();
	}
	//----------------
	function render($data,$nodes){
		$s=$this->type.':';
		if ($data['tmeasure'] == $data['rmeasure'] && $data['tmeasure'] == $data['bmeasure'] && $data['tmeasure'] == $data['lmeasure']){//all the measures are the same
			if ($data['tlength'] == $data['rlength'] && $data['tlength'] == $data['blength'] && $data['tlength'] == $data['llength']){//all the measures are the same
				$s.=$data['tlength'].$data['tmeasure'].';';//set them all at once
			}else{
				$s.=$data['tlength'].' '.$data['rlength'].' '.$data['blength'].' '.$data['llength'].$data['tmeasure'].';';//only put the measure once
			}
		}else{
			$s.=$data['tlength'].$data['tmeasure'].' '.$data['rlength'].$data['rmeasure'].' '.$data['blength'].$data['bmeasure'].' '.$data['llength'].$data['lmeasure'].';';
		} 
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
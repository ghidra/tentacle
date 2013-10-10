<?php
require_once node_root.'node_css.php';

class vertical_align extends node_css{
	var $type='vertical_align';
	
	var $align_options=array(
		'baseline',
		'sub',
		'super',
		'top',
		'text-top',
		'middle',
		'bottom',
		'text-bottom',
		'length'
	);
	var $measure='px';

	var $align='baseline';
	var $length=0;
	
	var $vertical_align_ports=array('vertical align attributes'=>array(
			'align'=>array('type'=>'dropdown','exposed'=>0),
			'length'=>array('type'=>'scalar','exposed'=>0),
			'measure'=>array('type'=>'dropdown','exposed'=>0)
		));

	function __construct(){
		$this->append('vertical_align_ports');
		parent::__construct();
		$this->keep_measure();
	}
	function render($data,$nodes){
		
		$s='vertical-align:';
		if($data['align']=='length'){
			$s.=$data['length'].$data['measure'].';';
		}else{
			$s.=$data['align'].';';
		}
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	
	}
}
?>
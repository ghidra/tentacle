<?php
require_once node_root.'node_css.php';

class dimension extends node_css{
	var $type='dimension';

	var $dimension_options=array(
		'width',
		'height',
		'top',
		'right',
		'bottom',
		'left',
		'max-width',
		'max-height',
		'min-width',
		'min-height',
		'line-height',
		'letter-spacing',
		'text-indent',
		'word-spacing'	
	);
	var $dimension='width';
	var $length=0;
	var $measure='px';

	var $dimension_ports = array('dimension attributes'=>array(
			'dimension'=>array('type'=>'dropdown','exposed'=>0),
			'length'=>array('type'=>'scalar','exposed'=>1),
			'measure'=>array('type'=>'dropdown','exposed'=>0)

			));
	
	function __construct(){
		$this->append('dimension_ports');
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']=$data['dimension'].':'.$data['length'].$data['measure'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
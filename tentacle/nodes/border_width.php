<?php
require_once node_root.'border_base.php';

class border_width extends border_base{
	var $type='border_width';
	var $border_width_ports=array('border width attributes'=>array(
							'effect'=>array('type'=>'dropdown','exposed'=>0),
							'width'=>array('type'=>'dropdown','exposed'=>0),
							'length'=>array('type'=>'scalar','exposed'=>0),
							'measure'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('border_width_ports');
		$this->append_ignore(array('border_base_ports','style','style_options','color'));

		parent::__construct();

		$this->keep_measure();
	}
	function render($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		$nodes[$data['index']]['result']=$data['effect'].'-width:';
		if($data['width']=='length'){
			$nodes[$data['index']]['result'].=$data['length'].$data['measure'].';';
		}else{
			$nodes[$data['index']]['result'].=$data['width'].';';
		}
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
<?php
require_once node_root.'table_properties_base.php';

class table_properties extends table_properties_base{
	var $type='table_properties';
	var $table_properties_ports=array('table properties attributes'=>array(
			'collapse'=>array('type'=>'dropdown','exposed'=>0),
			'hlength'=>array('type'=>'scalar','exposed'=>0),
			'hmeasure'=>array('type'=>'dropdown','exposed'=>0),
			'vlength'=>array('type'=>'scalar','exposed'=>0),
			'vmeasure'=>array('type'=>'dropdown','exposed'=>0),
			'side'=>array('type'=>'dropdown','exposed'=>0),
			'cell'=>array('type'=>'dropdown','exposed'=>0),
			'layout'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('table_properties_ports');
		parent::__construct();
	}
	function render($data,$nodes){		
		$s='';
		if($data['collapse'] != 'collapse'){
			$s.='border-collapse:'.$data['collapse'].';';//set the css tag thing border, border-bottom
		}
		if($data['hlength'] > 0 && $data['vlength'] > 0){
			$s.='border-spacing:';
			if ($data['hmeasure'] == $data['vmeasure']){//all the measures are the same
				if ($data['hlength'] == $data['vlength']){//all the measures are the same
					$s.=$data['hlength'].$data['hmeasure'].');';//set them all at once
				}else{
					$s.=$data['hlength'].' '.$data['vlength'].';';//only put the measure once
				}
			}else{
				$s.=$data['hlength'].$data['hmeasure'].' '.$data['vlength'].$data['vmeasure'].';';
			} 
		}
		if($data['side'] != 'top'){
			$s.='caption-side:'.$data['side'].';';//set the css tag thing border, border-bottom
		}
		if($data['cell'] != 'hide'){
			$s.='empty-cells:'.$data['cell'].';';//set the css tag thing border, border-bottom
		}
		if($data['layout']!='auto'){
			$s.='table-layout:'.$data['layout'].';';//set the css tag thing border, border-bottom	
		}
		
		$nodes[$data['index']]['result']=$s;//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
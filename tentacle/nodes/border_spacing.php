<?php
require_once node_root.'table_properties_base.php';

class border_spacing extends table_properties_base{
	var $type='border_spacing';

	var $hmeasure_options=array();
	var $vmeasure_options=array();//$this->clone_measure();//array('%','px','em','pt','pc','ex','cm','mm','in');

	var $border_spacing_ports = array('border spacing attributes'=>array(
							'hlength'=>array('type'=>'scalar','exposed'=>0),
							'hmeasure'=>array('type'=>'dropdown','exposed'=>0),
							'vlength'=>array('type'=>'scalar','exposed'=>0),
							'vmeasure'=>array('type'=>'dropdown','exposed'=>0)
							));

	function __construct(){
		$this->hmeasure_options = $this->clone_measure();
		$this->vmeasure_options = $this->clone_measure();

		$this->append('border_spacing_ports');
		$this->append_ignore(array('collapse','collapse_options','side','side_options','cell','cell_options','layout','layout_options'));

		parent::__construct();

	}
	//----------------
	function render($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function

		$s='border-spacing:';
		if ($data['hmeasure'] == $data['vmeasure']){//all the measures are the same
			if ($data['hlength'] == $data['vlength']){//all the measures are the same
				$s.=$data['hlength'].$data['hmeasure'].');';//set them all at once
			}else{
				$s.=$data['hlength'].' '.$data['vlength'].';';//only put the measure once
			}
		}else{
			$s.=$data['hlength'].$data['hmeasure'].' '.$data['vlength'].$data['vmeasure'].';';
		} 
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
	//----------------	
}
?>
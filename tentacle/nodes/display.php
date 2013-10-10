<?php
require_once node_root.'node_css.php';

class display extends node_css{
	var $type='display';
	
	var $cursor_options=array('none','block','inline','list-item','run-in','compact','marker','table','inline-table','table-row-group','table-header-group','table-footer-group','table-row','table-column-group','table-column','table-cell','table-caption');
	var $cursor='block';
	
	var $display_ports = array('display attributes'=>array(
			'cursor'=>array('type'=>'dropdown','exposed'=>0)
		));

	function __construct(){
		$this->append('display_ports');
		parent::__construct();
	}

	function render($data,$nodes){
		if($data['cursor']=='url'){//break it on url, cause i ain't ready for that yet
			$data['cursor']='auto';
		}
		$nodes[$data['index']]['result']='display:'.$data['cursor'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
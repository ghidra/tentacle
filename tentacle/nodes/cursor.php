<?php
require_once node_root.'node_css.php';

class cursor extends node_css{
	var $type='cursor';
	
	var $cursor_options=array('url','default','auto','crosshair','pointer','move','e-resize','ne-resize','nw-resize','n-resize','se-resize','sw-resize','s-resize','w-resize','text','wait','help');
	var $cursor='auto';

	var $cursor_ports = array('cursor attributes'=>array(
			'cursor'=>array('type'=>'dropdown','exposed'=>0)
		));
	
	function __construct(){
		$this->append('cursor_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		if($data['cursor']=='url'){//break it on url, cause i ain't ready for that yet
			$data['cursor']='auto';
		}
		$nodes[$data['index']]['result']='cursor:'.$data['cursor'].';';//set the css tag thing border, border-bottom
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
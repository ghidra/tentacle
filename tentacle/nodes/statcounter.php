<?php
//require_once './html.php';
require_once node_root.'node.php';

class statcounter extends node{

	var $type='statcounter';
	
	var $project='';
	var $security='';

	$var $statscounter_ports=array('statcounter attributes'=>array(
			'project'=>array('type'=>'string','exposed'=>1),
			'security'=>array('type'=>'string','exposed'=>1)
		));
		
	function __construct(){
			$this->append('statcounter_ports');
	}
	function render($data,$nodes){
		$nodes[$data['index']]['result']=$data['project'];
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
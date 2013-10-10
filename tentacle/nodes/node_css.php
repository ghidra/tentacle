<?php
require_once node_root.'node.php';

class node_css extends node{
 
	var $color_main = '#CD6074';
	var $color_alt = '#D93F5B';
	
	var $measure_options=array('%','px','em','pt','pc','ex','cm','mm','in');

	var $number_ports=0;

	function __construct(){
		$this->append_ignore(array('measure_options'));//not using these right now
	}

	function clone_measure(){
		/*var $new=array();
		while(list($k,$v)=each($this->measure_options)){
		//foreach ($this->measure_options as $k => $v) {
    		$new[$k] = clone $v;
		}*/
		//return $new;
		return array('%','px','em','pt','pc','ex','cm','mm','in');
	}
	function keep_measure(){
		$this->ignore = array_diff($this->ignore, array('measure_options'));
	}

}

?>
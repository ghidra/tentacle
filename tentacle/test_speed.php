<?php
class test_speed{
	var $start_time=0.0;
	var $end_time=0.0;
	var $elapsed_time=0.0;
	function test_speed(){
		$this->start_time=$this->test_time();
	}
	function test_time(){
		$timer=explode(' ',microtime());
		$timer=$timer[1]+$timer[0];
		return $timer;
	}
	function get_time(){
		$this->end_time=$this->test_time();
		$elapsed_time=round($this->end_time - $this->start_time,4);
		return $elapsed_time;
	}
}

?>
<?php
//this is the base class that all nodes need to inherit from.
//Has all the base functions to represnt the node int the editor
class node{

	var $index=0;
	var $label='';
	var $result='';
	var $type='';
	var $mode=0;
	var $left=0;
	var $top=0;
	var $number_ports=1;
	var $ports='content';//the name of the extendable port, the content port so to speak
	var $out_ports = array('result');//array to hold the ports that are exposed as outputs
	var $in_ports = array();//array to hold the ports that are inputs

	var $color_main = '#CD8C4E';
	var $color_alt = '#E1771E';

	var $core_attributes = array('result','type','mode','index','left','top','number_ports');
	var $ignore = array('ignore','core_attributes','attributes');

	var $attributes = array();//empty object to send to javascript

	//-----------------------------------------------
	// This function is for building the nade in the tentacle editor
	//-----------------------------------------------

	function assemble(){//get the data needed for javascript to build the node with
		//returns an object with the attributes and other data needed to construct the node in javascript for the editor
		$this->attributes = array();//empty object
		foreach( $this as $key => $value ){//loop through the objects variables to expose them
			if(!in_array($key,$this->ignore)){ //&& !is_object($value) && !is_array($value) //as long as they aren't in the ignore array, of an object of an array
				$this->attributes[$key] = $value;
			}
		}

		return $this->attributes;
		//$json = new Services_JSON();
		//return  $json->encode($this->attributes);
	}

	//-----------------------------------------------
	// This function assigns values coming from xml
	//-----------------------------------------------
	function assign($data){
		foreach( $data as $key => $value ){//loop through the objects variables //if( !is_object($value) && !is_array($value) ){ //as long as they aren't in the ignore array, of an object of an array
			if(!in_array($key,$this->ignore)){//shold i keep this here
				if( is_object($value) || is_array($value) ){
					$this->$key = ( $key == 'result' ) ? $this->result : $value[$key];
				}else{
					$this->$key = ( $key == 'result' ) ? $this->result : $value;
				}
			}
		}
	}

	//-----------------------------------------------
	// This is the render function, that should be overridden in the child classes, this is here to keep it from having a fatal error if this is not done
	//-----------------------------------------------
	function render($data,$nodes){
		return $nodes[$data['index']];//return the actual node with result, which in this case is nothing
	}
	//-----------------------------------------------
	// These functions are called from children classes
	// during node creation
	//-----------------------------------------------
	//functions to add to arrays for sending data to javascript
	//for example add an array to the ignore group, so that I am not sending data that doesn't need to be send to javascript
	function append_ignore($a){
		$this->ignore = array_merge($this->ignore,$a);
	}
	/*function append_in_ports($a){
		$this->in_ports = array_merge($this->in_ports,$a);
	}*/
	function append($s){
		//$this->append_in_ports($this->$s);
		$this->in_ports = array_merge($this->in_ports,$this->$s);
		$this->append_ignore(array($s));
	}
	//---------------------------------------------
	//---------------------------------------------
	function get_port_data($data,$nodes,$attr){//get the data from a port, if it is a plugged in value or typed right in
		$s='';
		if ($data[$attr]) {//i need to use isset here somehow, to avoid the php notice error
			if(is_string($data[$attr])){
				$s=$data[$attr];
			}else{
				$s=$nodes[$data[$attr]['index']][$data['port_'.$attr]];
			}
		}
		return $s;
	}
	//---this function get all the 'content ports data mostly'
	function get_ports_data($data,$nodes){
		$s='';
		for($i=0;$i<=$data['number_ports']-1;$i++){//from ports
			$s.= $nodes[$data[$this->ports.$i]['index']][$data['port_'.$this->ports.$i]];
		}
		return $s;
	}
	//-----------------------------------------------
	//utilities
	//-----------------------------------------------
	function check_array_value($a,$key,$value){
		//first we need to see if the index even exists on the array
		//then we can check if it is set to the given value
		$r = false;
		if (array_key_exists($key, $a)) {
			$r = ($a[$key] = $value);
		}
		return $r;
	}
	function array_value($a,$key){
		//first we need to check that the index exists before returning the value
		$r='';
		if (array_key_exists($key,$a)){
			$r = $a[$key];
		}
		return $r;
	}
	//-----------------
	function array_to_string($a){//temp utility function to convert an array to a string I can use
		$s = '';
		while(list($k,$v)=each($a)){
			if(is_array($v)){
				$s.='<br>';
				$s.=$v.':'.$k.':<br>';
				$s.=$this->array_to_string($v);
			}else{
				$s.=$k.':'.$v.'<br>';
			}
		}
		return $s;
	}
}


?>

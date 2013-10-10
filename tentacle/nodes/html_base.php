<?php
//This node will be made OBSOLETE after the refactor of nodes is done and uses the node.php class
//require_once './html.php';
require_once tentacle_root.'html.php';

class html_base{

	var $result='';
	var $type='';
	var $mode=0;
	var $index=0;
	var $left=0;
	var $top=0;
	var $number_ports=1;
	
	var $id='';
	var $class='';
	var $style='';
	var $title='';
	var $name='';
	
	var $accesskey='';
	var $tabindex=0;
	var $dir='';
	var $lang='';
	var $xmllang='';	
	
	var $core_attr=array(
		'id',
		'title',
		'class',
		'style'
	);
	function html_base(){
	}
	function assemble_base_node($ignore=array()){
		$s= node_header($this->index,$this->type,$this->mode);
		$s.=node_output('result',$this->result,$this->index);
		
		for($i=0;$i<=sizeof($this->core_attr)-1;$i++){
			$attribute=$this->core_attr[$i];
			if(!in_array($attribute,$ignore)) $s.=node_input($attribute,$this->$attribute,$this->index);
		}
		/*$s.=node_input('id',$this->id,$this->index);
		$s.=node_input('title',$this->title,$this->index);
		$s.=node_input('class',$this->class,$this->index);
		$s.=node_input('style',$this->style,$this->index);*/
		return $s;
	}
	function assign_base_values($values){
		$this->result='';
		$this->index=$values['index'];
		$this->type=$values['type'];
		$this->mode=$values['mode'];
		$this->left=$values['left'];
		$this->top=$values['top'];
		$this->number_ports=$values['number_ports'];
	
		$this->id=$values['id'];
		$this->class=$values['class'];
		$this->style=$values['style'];
		$this->title=$values['title'];
		$this->name=$values['name'];
		
		$this->accesskey=$values['accesskey'];
		$this->tabindex=$values['tabindex'];
		$this->dir=$values['dir'];
		$this->lang=$values['lang'];
		$this->xmllang=$values['xmllang'];
	}
	function inspect_base($data,$ignore=array()){
		//$s= property_header($this->index,$this->type);
		for($i=0;$i<=sizeof($this->core_attr)-1;$i++){
			$attribute=$this->core_attr[$i];
			if(!in_array($attribute,$ignore)) {
				if($data[$attribute]!='_in')	$s.= property_text_input($attribute,$data[$attribute]);
			}
		}
		/*if($data['id']!='_in')    $s.=property_text_input('id',$data['id']);
		if($data['title']!='_in') $s.=property_text_input('title',$data['title']);
		if($data['class']!='_in') $s.=property_text_input('class',$data['class']);
		if($data['style']!='_in') $s.=property_text_input('style',$data['style']);*/

		return $s;
	}
	//------------------------------------
	//	 get_attribute_assembled : builds the attribute, called from get_base_attibutes
	//   get_base_attributes : assemble all the base attributes from data
	//	
	//	get_tag_assembled : assembles all the shit together
	//
	//   called from render
	//------------------------------------
	function get_options_key($array){
		$a=array();
		while(list($key,$val) = each($array)) {
			array_push($a, $key);
		}
		return $a;
	}
	function get_port_data($data,$nodes,$attr){//this is for one off content, that can be content or plugged that doesn't just go into attributes
		//global $nodes;
		$s='';
		//if (strlen($data[$attr])>0) {
		if ($data[$attr]) {
			if(is_string($data[$attr])){
				$s.=$data[$attr];
			}else{
				$s.=$nodes[$data[$attr]['index']][$data['port_'.$attr]];	
			}
		}
		return $s;
	}
	function get_attribute_assembled($data,$nodes,$attr){
		//global $nodes;// this variable comes from the render.php execute function
		$s='';
		//if (isset($data[$attr])) {
		if ($data[$attr]) {
			$s.=' '.$attr.'="';
			if(is_string($data[$attr])){
				$s.=$data[$attr].'"';
			}else{
				$s.=$nodes[$data[$attr]['index']][$data['port_'.$attr]].'"';	
			}
		}
		return $s;
	}
	function get_base_attributes($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		$s='';//hold the tag data that is pligged in i guess
		
		$s.=$this->get_attribute_assembled($data,$nodes,'id');
		$s.=$this->get_attribute_assembled($data,$nodes,'title');
		$s.=$this->get_attribute_assembled($data,$nodes,'class');
		$s.=$this->get_attribute_assembled($data,$nodes,'style');
		
		return $s;
	}
	function get_tag_assembled($data,$nodes,$local_attributes='',$local_inner=''){
		//global $nodes;// this variable comes from the render.php execute function
		$s='<'.$data['type'].$this->get_base_attributes($data,$nodes).' '.$local_attributes.'>';
		
		for($i=0;$i<=$data['number_ports']-1;$i++){//from ports
			$s.= $nodes[$data['content'.$i]['index']][$data['port_content'.$i]];
		}
		$s.=$local_inner;
		$s.='</'.$data['type'].'>';
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the actual node with result 
	}
	//------------------------------------
	//   content_ports : this is the loop that makes content ports on node creation
	//   add_content : used to add a new port
	//------------------------------------
	function content_ports(){
		$p='';
		for($i=0;$i<=$this->number_ports-1;$i++){
			$p.=node_input('content'.$i,'',$this->index);
		}
		return $p;
	}
	function add_content(){
		return new_input('content'.$this->number_ports,'',$this->index);
	}
	//------------------------------------
	//   translate : used the data sent from javascript via node_handler
	//   and puts all the values into an associative array for easy grabing
	// 	 called from INSPECT 
	//------------------------------------
	function translate($data){
		$a=array();
		$d=explode(',',$data);
		for ($i=0;$i<=count($d)-1;$i++){
			$d_s=explode(":",$d[$i]);
			//-----------fix the bug with the : split
			$all_data='';
			for($j=1;$j<=sizeof($d_s)-1;$j++){
				$all_data.=$d_s[$j];
				if($j!=sizeof($d_s)-1){
					$all_data.=':';
				}
			}
			//-----------
			$a[$d_s[0]]=$all_data;//set the associate array values for each thing
		}
		return $a;//now return the final array
	}
	//------------------------------------
	//------------------------------------
	function open_node(){
		return open_node_html($this->index,$this->left,$this->top,$this->type);
	}
	function close_node(){
		return close_node_html();
	}
}
?>
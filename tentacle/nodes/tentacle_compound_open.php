<?php
//this is a proxie node that is mostly used from the tentacle node editor it seems

require_once node_root.'node.php';
require_once tentacle_root.'html.php';

class tentacle_compound_open extends node{

	var $type='tentacle_compound_open';
	var $index = 'root';
	//var $compound_name='';
	var $out_ports=array();//empty out the default 'result'
	var $compound_in_ports=array();

	var $native=array('read_type','index','type','mode','left','top','name','result_out','read_data','number_ports','native','compound_name');
	
	function __construct(){
		$this->append_ignore(array('read_data','native'));//dont need to send that along for the ride
	}
	//function assemble_node(){//called when openeing a compound. This is a compound version of a execute node. Maybe I merge them ot something later.
		/*$s='';//string input
		//------------------temp arrays to hold ports and data		
		$i_a=array();//input array
		$o_a=array();//out out array;
		//--------------------get port values
		reset($this);
		while( list($k,$v)=each($this) ){//loop over the assigne values, and find the none defualt ones. They are the in and outputs
			if(!in_array($k,$this->native)){
				if($v[0]=='in') array_push($i_a,$k);
				if($v[0]=='out') array_push($o_a,$k);
				//$s.=$k.' '.$v;//$s.=node_input( $k , '' , $this->index);
			}
			
		}
		//------------------now loop over temp arrays to build the port towers
		$s.= top_bun_compound_html($this->index,$this->compound_name);//get the next tower ready
		
		reset($i_a);
		while(list($k,$v)=each($i_a)){
			//$s.=$v;
			$s.=compound_output($v,'',$this->index);
			//$s.=node_output($v,'',$this->index);
		}
		
		$s.=compound_out_inwait('',$this->index);
		$s.=middle_bun_compound_html();
		
		reset($o_a);
		while(list($k,$v)=each($o_a)){
			//$s.=node_input($v,'',$this->index);
			$s.=compound_input($v,'',$this->index);
			//$s.=$v;
		}
		$s.=compound_in_inwait('',$this->index);
		//--------------------
		return $s;*/
		//return '*******************************fak***';
	//}
	function assign($data){//called from xml_file_handler on open of xml file. Reads data from file and assigns values
		//ok, so here is where I need to set the data for the ports to be read when it goes through the 
		//javascript html building process

		//go through the connections to find the exposed ports
			/*
		reset($values);
		while(list($k,$v)=each($values)){
			$this->$k = $v;//$this->index=$values['index'];
		}
		*/

		parent::assign($data);

		$this->label = $data['name'];//put the name into the node label
		
		if(isset($data['read_data'])){//this should basically  come in with the connections data array
			//okay so the data us coming in now
			//$this->trash = $data['read_data'].'****************************boooo';
			//go through the connections to find the exposed ports
			$in_collect = array();//empty array to hold in in ports
			foreach( $data['read_data']->connections as $key => $value ){
				$conn = $data['read_data']->connections[$key];//shortcut
				
				if($conn['to_node']=='root'){//this is an outward port
					array_push($this->out_ports,$conn['to_port']);
				}elseif($conn['from_node']=='root'){//this is all the input ports, exposed to the inspect page
					$in_collect[$conn['from_port']]=array();
					//i need to go into the node (load it) and see what the values to these are, right now these are just defaults
					//the above statement is true for tentacle_compound.php not here
					$in_collect[$conn['from_port']]['type']='string';
					$in_collect[$conn['from_port']]['exposed']=1;
				}
			}
			$this->compound_in_ports['compound attributes']=$in_collect;
			$this->append('compound_in_ports');

			//this makes it visible in javascript because I am sending some bogus data
			//$this->trash = $this->array_to_string($data['read_data']->connections).'****************************';//.$this->array_to_string($data['read_data']);
			//$this->trash = $data['trash_data'].'****************************';//.$this->array_to_string($data['read_data']);

		}
		//i need to know what the out and in ports are 

		//i need to start looking and see what values I am getting in here before I can build out the data I need
		//$this->label='*******************************fak';
	}
	function inspect($data){
		//it doesn't look like I need this 'bug fix' whatever the fuck it does anyway
		/*$d=explode(':',$data);
		//-----------fix the bug with the : split
		$all_data='';
		for($j=1;$j<=sizeof($d)-1;$j++){
			$all_data.=$d[$j];
			if($j!=sizeof($d)-1){
				$all_data.=':';
			}
		}*/
		//-----------
		
		//$data=$this->translate($data);
		
		$this->type = $data->compound_name;
		$s=property_header($this->index,$this->type);
		
		foreach($data as $k => $v) {//go through the input data.
			if($k!='compound_name'){//ignore the compound name object
				include_once (node_root.$v->data_node_type.'.php');												//import the file that the node is gonna be creataed with the help with
				$package = new $v->data_node_type;
				$attr = $v->data_node_port;//the atribute that I want to look at
				
				switch(gettype($package->$attr)){//get the data type of the variable so we know how to handle it
					case 'boolean':
						break;
					case 'integer':
						break;
					case 'double'://scalar
						break;
					case 'string':
						//since drop down values are also strings, I need to check if there is a drop down option, by looking for the option array
						/*$options = $attr.'_options';//this should be the name of the array to get the options from
						if($package->$options){
							$s.=property_drop_down($v->data_comp_port,$package->$options,$v->data_comp_valu)
						}else{
							//this is a basic string input
							$s.= property_text_input($v->data_comp_port,$v->data_comp_valu);
						}*/
						//turns out that the drop down attributes aren't even exposed to get piped out...
						$s.= property_text_input($v->data_comp_port,$v->data_comp_valu);
						break;
					case 'array':
						
						break;
					case 'object':
						break;
				}
				
				
			}
    	}
		
		//$s.=property_text_input_nl('string',$all_data);
		//$s.=property_text_input_nl('string',$data);
		return $s;
	}
	function render($data,$nodes){
		//rendering compounds, is basically a pass through. there are 2 ndoes for each compound in the xml.
		//the first node is the output of the compound
		//the second node are the inputs, and pass throughs of plugged in nodes	
		/*reset($data);
		while(list($k,$v)=each($data)){//loop through all data
			if( !in_array($k,$this->native)  && substr($k,0,5)!='port_' ){//ignore basic values, ignore 'port_'    && substr($k,0,5)!='port0_'
					$nodes[$data['index']][$k] = $nodes[ $data[$k]['index'] ][ $k ];//otherwise $nodes[$data['index']]['result']=$s;
			}
		}		
		return $nodes[$data['index']];//return the entire node, with the result
		*/
	}
	//--------------
	//--------------
	//    add_in_port is called from editor_node_handler when we are trying to add a new input port
	//    editor_node_handler sets some varialbes for us before calling this. It sets our id, to the node we are connecting to.
	//    it also sets a variable called "number_ports" to the actual name of the port we are connecting to also.
	function add_port($kind){
		return new_compound_port($kind,$this->number_ports);//they are switched in the dame HTML for a good reason maybe i don't know
	}
	function options(){//this function is called from editor_node_handler, options function. Which is called from compound.options when the options bar is clicked.
		$s= property_header_compound($this->index,$this->type);
		$s.=property_text_input('name',$this->id);
		//$s.= compound_options();
		return $s;
	}
	//------utlilities
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
	//--
}
?>
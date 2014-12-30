<?php
//this node builds the compound node in the tentacle editor. NOT the expanded compound. But the actual node in the graph.
//because of this, i have to build a dynamic object to append as the inspecting data
//normally i build an array that already has all that hard coded, and sent at the initilization of this node.
require_once node_root.'node.php';
require_once tentacle_root.'html.php';

class tentacle_compound extends node{

	var $number_ports=0;

	var $type='tentacle_compound';

	var $read_type='';
	var $render_type='standard';
	var $compound_id='';//this can be the embedded id in the array, of the name of the compound file
	var $read_data='';//empty waiting on shit from xml_file_handler,  I MAY NOT NEED TO EVEN DECALRE IT< AND JUST MAKE IT DISPOSABLE FROM XML_FILE_HANDLER
		//var $read_data = '';

	//i don't think ill need this variable after a get done fixing this node
	var $native=array('read_type','render_type','index','type','mode','left','top','name','ports','read_data','number_ports','native');

	var $compound_in_ports=array();//waiting for the in ports data
	var $out_ports=array();//empty out the default 'result'

	/*var $tentacle_compound_ports=array('tentacle compound attributes'=>array(
			'name'=>array('type'=>'string','exposed'=>0)
		));*/
	function __construct(){
		//$this->append('tentacle_compound_ports');
		$this->append_ignore(array('read_data','native'));//dont need to send that along for the ride
	}
	function assign($data){
		//this function normally just sets the data of ports. Since this is a custom node, we have to add a bit more, and
		//basically build the node. Since this is a compound, it doesnt know what data exists beyond th default
		//for an embedded compound, xml_file_hanlder send this node the embedded compound data, so that I can reconstruct what I need
		//to make the node, which means even the ports arrays so that the node has the input and output ports

		//fust just assign the basic data that does need to be set.
		parent::assign($data);

		//we are making a embedded node so far. if this property exists
		//$this->temp_read_data_out=$data['embedded_id'];
		if(isset($data['read_data'])){
		//if(property_exists($data,'read_data')){
			//this read_data was added to the ignore array. So it isn't set when the parent funtion is called.
			//if it did, it breaks everything, since the read_data is more complex than a simple object or array
			//it is the whole compounds representation from the xml_reader.
			// i need to build the data i need from all that

			//set the label from the embedded compound name
			$this->label = $data['read_data']->name;
			//$this->label = count($data['read_data']->nodes[0]);
			//$this->label = $data['read_data'];
			//$this->label = $data['read_data']->connections[0]['to_node'];


			//go through the connections to find the exposed ports
			$in_collect = array();//empty array to hold in in ports
			foreach( $data['read_data']->connections as $key => $value ){
				$conn = $data['read_data']->connections[$key];//shortcut

				if($conn['to_node']=='root'){//this is an outward port
					array_push($this->out_ports,$conn['to_port']);
				}elseif($conn['from_node']=='root'){//this is all the input ports, exposed to the inspect page
					$in_collect[$conn['from_port']]=array();
					//i need to go into the node (load it) and see what the values to these are, right now these are just defaults
					$in_collect[$conn['from_port']]['type']='string';
					$in_collect[$conn['from_port']]['exposed']=1;
				}
			}
			//$this->compound_in_ports['embedded compound attributes']=$in_collect;
			$this->compound_in_ports['compound attributes']=$in_collect;
			$this->append('compound_in_ports');
		}else{
			$this->label = 'no data';
		}

	}
	/*function assemble_node(){//called from open, so has to build a dynamic node. also called from new node, needs to handle uncoming data too I guess
		$s=compound_header($this->index,$this->name,$this->mode);
		//out ports are from the 'result_out' array.
		reset($this->ports);
		while( list($k,$v)=each($this->ports) ){
			if(!in_array($k,$this->native)) $s.=node_output( $k , '' , $this->index);
		}
		//$s.=$this->result_out['index'];
		//in ports part of the main compound data
		reset($this);
		while( list($k,$v)=each($this) ){
			if(!in_array($k,$this->native)) $s.=node_input( $k , $v , $this->index);
		}
		//$s.=node_input('content'.$i,'',$this->index);
		//now embed some data. if it is embedded, what embedded array id is it.
		//and the input port data, what type of data it is for inspecting
		if($this->read_type=='embedded') $s.=embedded_tag( $this->index,substr($this->type,-2,1) );//pass in the embedded id number

		return $s;
	}*/
	//function inspect($data){
		/*$s=property_header($this->index,$this->type);

		$d=explode(':',$data);
		//-----------fix the bug with the : split
		$all_data='';
		for($j=1;$j<=sizeof($d)-1;$j++){
			$all_data.=$d[$j];
			if($j!=sizeof($d)-1){
				$all_data.=':';
			}
		}
		//-----------
		$s.=property_text_input_nl('string',$all_data);
		//$s.=property_text_input_nl('string',$data);
		return $s;*/
	//}
	function render($data,$nodes){
		//rendering compounds, is basically a pass through. there are 2 ndoes for each compound in the xml.
		//the first node is the output of the compound
		//the second node are the inputs, and pass throughs of plugged in nodes

		//i need to know if the phantom is coming in, or the compound for rendering. cause different things need to hapen
		reset($data);
		while(list($k,$v)=each($data)){//loop through all data
			if( !in_array($k,$this->native)  && substr($k,0,5)!='port_' ){//ignore basic values, ignore 'port_'    && substr($k,0,5)!='port0_'
				//some changes in here-------------seems this if isn't needed
				if($this->array_value($data,'render_type') != 'phantom'){//if this is the compound with the values to pass onto interior nodes
					//this is the part where the tentacle_compound is plucgged into the the interior nodes. So if there is asomething plugged in here, I need to do something else
					//$nodes[$data['index']][$k] = $v;//.' _ '.$data['render_type'];//just pass over the valur from the node, this may not account for what is plugged in.
					if(is_string($v)){
						$nodes[$data['index']][$k] = $v;
					}else{
						$nodes[$data['index']][$k] = $nodes[ $data[$k]['index'] ][ $k ];//$nodes[ $data[$attr]['index'] ][ $data['port_'.$attr] ];
					}
				}else{
					//this code works for the phantom node. It is a very simple pass through.
				//$data[$k]['index'] gives basically the same value. But I am assuming
					$nodes[$data['index']][$k] = $nodes[ $data[$k]['index'] ][ $k ];//otherwise $nodes[$data['index']]['result']=$s;
				}
			}
		}

		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>

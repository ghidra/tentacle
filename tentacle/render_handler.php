<?php
include_once 'path.php';
include_once 'xml_reader.php';
include_once 'xml_file_browser.php';


class render_handler{
	
	var $connections=array();
	var $nodes=array();
	var $result=array();
	var $input = array();
	var $embedded=array();
	
	function render_handler(){}
	//final call, actual render output 
	function output($node){
		echo $this->get_output($node);
	}
	function get_output($node){
		return $this->nodes[$node['index']]['result'];
	}
	//recursion
	function execute($node){
		reset ($node);												//reset the pointer (i don't know)
		while(list($key,$val) = each($node)) {						//for each of the values in this node, seperate into key and val
			//														//key are, id, type, class, top, left, etc.
			//echo "1)".$key.":".$val."<br />";						//also includes the ports plugged into. usually named content0, content1, etc	
			if(is_array($val)){										//if the val is an array
				//													//the first time it is the execute node, with nothing but a number and an array
				//													//each time after that, it is a node, plugged into current node	
				reset($this->connections);
				while(list($key_b,$val_b) = each($this->connections)) {	//look at the connections, and put what is connected into the 
					//												//looop through connections
					if($this->connections[$key_b]['to_node']===$val['index']){//if to node pointer points to node that we are on, we need to append
						//echo '2)**append_connection:'.$key_b.'<br />';
						//-------
						$val[$this->connections[$key_b]['to_port']]    			=   $this->nodes[$this->connections[$key_b]['from_node']];//load node into connected port **aka content0=node1
						$val['port_'.$this->connections[$key_b]['to_port']]		=	$this->connections[$key_b]['from_port'];//send the port that data comes from to this node **aka port_content0=result
						//}
					}
				}
				//echo '4)recurse the connections<br /><br />';	
				$this->execute($val);										//recurse, now that connections are loaded in
				//echo '5)render';													//this will crawl all thw way down the tree and plug in all the nodes				
				if( substr($val['type'],0,1)=='[' && substr($val['type'],-1,1)==']' ){//if this is an embedded compound
					include_once node_root.'tentacle_compound.php';
					//^^^^^^^^^^^^^^^^^^^^^^^^^
					$newnode = new tentacle_compound();
				}else{
					include_once node_root.$val['type'].'.php';			//now make sure the node code is avilable., and create the means to make the code 
					//^^^^^^^^^^^^^^^^^^^^^^^^^
					$newnode= new $val['type'];							//make an object to build the data
																		//send the current node, and get means to make code
				}
				$this->nodes[$val['index']]=$newnode->render($val,$this->nodes);//set the node array at the index (root,0,1,2,etc) and render is and return it.		
			}
		}
	}
	function browse($with_header=1){
		$browser=new xml_file_browser();
		return $browser->browse_to_read($browser->file_list,'',0);
	}
	//------------------------------
	//---------------------compounds
	function unfold($compound){//recursive function to fold embedded compounds into the main loop
		$node_count = count($this->nodes);//the count of main nodes
		$phantom_id = $node_count-1;//the previously created phantom node
		$embedded_id = $compound['index'];
		$embedded_set = substr( $compound['type'] ,-2,1);//set the embedded array id
		
		//i need to set connections going TO the root node to the phantom node
		//i need to set connections going FROMthe root node to the compound		
		
		//loop through connections
		reset( $this->embedded[$embedded_set]->connections );//begin looping through compound connections to update the connection
		while( list($k,$v)=each($this->embedded[$embedded_set]->connections) ){
			if( $this->embedded[$embedded_set]->connections[$k]['to_node'] == 'root' ){//going TO the root
				$this->embedded[$embedded_set]->connections[$k]['to_node'] = $phantom_id;
				//'result' is what this  makes. I already have it $this->nodes[$phantom_id][ $this->embedded[$embedded_set]->connections[$k]['to_port'] ] = '';//make the value on the phantom node that we are plugged into
				$this->embedded[$embedded_set]->connections[$k]['from_node'] += $node_count;//account for that new id embedded nodes will have
			}elseif( $this->embedded[$embedded_set]->connections[$k]['from_node'] == 'root'){//coming FROM the root
				$this->embedded[$embedded_set]->connections[$k]['from_node'] = $embedded_id;
				$this->embedded[$embedded_set]->connections[$k]['to_node'] += $node_count;
			}else{
				$this->embedded[$embedded_set]->connections[$k]['from_node'] += $node_count;
				$this->embedded[$embedded_set]->connections[$k]['to_node'] += $node_count;
			}
			//now add the connections to the main array
			array_push( $this->connections, array('from_node'=>$this->embedded[$embedded_set]->connections[$k]['from_node'],'to_node'=>$this->embedded[$embedded_set]->connections[$k]['to_node'],'from_port'=>$this->embedded[$embedded_set]->connections[$k]['from_port'],'to_port'=>$this->embedded[$embedded_set]->connections[$k]['to_port']));	
		}
		//-----now loop thorught the embedded node to include it
		reset( $this->embedded[ $embedded_set ]->nodes );
		while( list($k,$v) = each($this->embedded[ $embedded_set ]->nodes) ){//loop over each embedded compound to fold into main node list
			reset( $this->embedded[ $embedded_set ]->nodes[$k] );
			while( list($kb,$vb) = each( $this->embedded[ $embedded_set ]->nodes[$k] ) ){//loop over the values to copy to main loop, change index numbers
				$this->nodes[ $this->embedded[ $embedded_set ]->nodes[$k]['index']+$node_count ][$kb] = $vb;//copy value
				if($kb=='index') $this->nodes[ $this->embedded[ $embedded_set ]->nodes[$k]['index']+$node_count ]['index']+=$node_count;//correct the index number
			}
		}
	}
	//------------------------------
	function find_compounds($network){//find the compound in the network
		reset($network->nodes);
		while( list($k,$v) = each($network->nodes) ){//loop network
			if( $network->nodes[$k]['read_type'] == 'embedded' ){//if embedded
				
				//make a phantom node
				$pid = count($this->nodes);
				$this->nodes[$pid]['read_type']='node';
				$this->nodes[$pid]['render_type']='phantom';
				$this->nodes[$pid]['type'] = 'tentacle_compound';
				$this->nodes[$pid]['index'] = $pid;
				//re route connections from compoud to be from this phantom node
				reset($network->connections);
				while( list($kb,$vb) = each($network->connections) ){
					if( $network->connections[$kb]['from_node'] == $network->nodes[$k]['index'] ){ 
						$network->connections[$kb]['from_node'] = $pid;
						$this->nodes[$pid][ $network->connections[$kb]['from_port'] ]='';//make the port that is ceoming from most likely result
						//$network->connections[$kb]['from_port']=$network->connections[$kb]['from_port'].'_string';//rewire where the data comes from
						//$this->nodes[$pid][ $network->connections[$kb]['from_port'].'_string' ]='';//make the new port
					}
				}
				//unfold
				$this->unfold( $network->nodes[$k] );//unfold the embedded compound into main loop
				//find more compounds
				$this->find_compounds( $this->embedded[ substr($network->nodes[$k]['type'] ,-2,1) ] );//find compounds in the embedded compound
			
				//unset($this->nodes[$k]);
				
			}
		}
	}
	//------------------------------
	//------------------------------
	function set_file($file){
		/*******************************************************************************************************************************************/
		//the xml data and variable pointers
		$node_data= new xml_reader(page_root.$file.'.xml');					//the xml
		$this->connections = $node_data->connections;						//the connections
		$this->nodes = $node_data->nodes;									//the nodes
		$this->result = $node_data->result;
		$this->input = $node_data->input;
		$this->embedded = $node_data->embedded;
		/*******************************************************************************************************************************************/
		//now reassemble data to include the compounds into the line up
		if( sizeof($this->embedded)>0 ){//only do it if there are embedded nodes, no sense in wasting cycles
			$this->find_compounds($this);//send the main network to be looped
		}
		/*******************************************************************************************************************************************/
	}
	//------------------------------
	//------------------------------
}
?>

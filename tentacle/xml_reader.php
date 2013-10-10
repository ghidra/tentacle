<?php
/*
This class is made specifically to read tentacle formatted XML files.

-when initiated, a file path and file name must be sent when creating a new xml_reader.

attributes include:
->nodes = () //holds the nodes
->connections = () //holds the connections
->result = () //holds the result or the execute node
*/
include_once 'xml_file_browser.php';//using this to check for compounds and nodes. using the browse function

class xml_reader{

	var $nodes = array();
	var $connections = array();
	var $result = array();
	var $input = array();
	
	var $embedded = array();//array to hold more xml reader objects
	var $embedded_switch = false;//switch so the reader knows to put embedded files in embedded objects
	var $embedded_count = 0;
	
	var $node_index=0;//array to count up the nodes, need it global so it can be seen from these damn functions
	var $result_index=0;
	
	//var after searching folders of nodes and compounds. the found files
	var $found_nodes=array();
	var $found_compounds=array();
	
	function xml_reader($filename){
		//now search the nodes and compounds directories
		$browse = new xml_file_browser();
		$this->found_nodes = $browse->browse_dir('tentacle/nodes');
		$this->found_compounds = $browse->browse_dir('tentacle/compounds');
		//now set the file
		if (!($fp=fopen($filename,"r"))){die("cannot open ".$filename);}//are we able to open it, otherwise die.
		if (!($this->xmlparser=xml_parser_create())){die("Cannot create parser");}//create a new xml parser
		xml_set_object($this->xmlparser,$this); 
		xml_set_element_handler($this->xmlparser,'start_tag','end_tag');//what to do with the data at start and end tags
		//xml_set_character_data_handler($xmlparser, "tag_contents");//what to do with the content of tag
		while ($data = fread($fp, 4096)){
 	  		//$data=eregi_replace(">"."[[:space:]]+"."<","><",$data);//this gets rid of any extra white space.
   			//new wont throw notice
   			$data = preg_replace( '/\s+/', ' ', $data );

   			if (!xml_parse($this->xmlparser,$data,feof($fp))) {//this is error collecting
      			$reason=xml_error_string(xml_get_error_code($this->xmlparser));
      			$reason.=xml_get_current_line_number($this->xmlparser);
      			die($reason);
   			}
		}
		xml_parser_free($this->xmlparser);//clear out the data
		
	}
	
	function start_tag($parser,$name,$attribs) {
	   	switch ($name){
			//----------------------------file data
			case 'NODE_FILE':
			break;
			case 'DESCRIPTION':
			break;
			//-----------------------------
			case 'DEFINITION':
			break;
			case 'NODES':
			break;
			case 'CONNECTIONS':
			break;
			//---------------------------node data
			case 'NODE':
				//--------find out if this is a compound or node
				$type_embedded = ( substr($attribs['TYPE'],0,1)=='[' && substr($attribs['TYPE'],-1,1)==']' )?true:false;//is true if the name is the name of an embedded compound
				$type_compound = ( false )?true:false;
				$type_node = ( in_array($attribs['TYPE'],$this->found_nodes) )?true:false;
				
				$read_type = '';
				if($type_node){
					$read_type = 'node';
				}elseif($type_compound){
					$read_type = 'compound';
				}elseif($type_embedded){
					$read_type = 'embedded';
				}
				
				$this->node_index=$attribs['INDEX'];
				
				if( $type_node || $type_embedded || $type_compound){//node
					
					if( $this->embedded_switch ){//embedded
						$this->embedded[ $this->embedded_count ]->nodes[$this->node_index]= array();
						$this->embedded[ $this->embedded_count ]->nodes[$this->node_index]['read_type']=$read_type;//we need to send what type of node this is, so that we know how to handle it in other classes. compounds or embeeded compounds, or just standard nodes
						//$this->embedded[ $this->embedded_count ]->nodes[$this->node_index]['name']= $attribs['TYPE'];
						$this->embedded[ $this->embedded_count ]->nodes[$this->node_index]['index']=$attribs['INDEX'];
						$this->embedded[ $this->embedded_count ]->nodes[$this->node_index]['type']= $attribs['TYPE'];
						$this->embedded[ $this->embedded_count ]->nodes[$this->node_index]['mode']=$attribs['MODE'];
					}else{//normal node
						$this->nodes[$this->node_index]= array();
						$this->nodes[$this->node_index]['read_type']=$read_type;
						$this->nodes[$this->node_index]['index']=$attribs['INDEX'];
						$this->nodes[$this->node_index]['type']= $attribs['TYPE'];
						$this->nodes[$this->node_index]['mode']=$attribs['MODE'];
					}
				}
				
			break;
			case 'PARAM':
				if( $this->embedded_switch ){//embedded
					$this->embedded[ $this->embedded_count ]->nodes[$this->node_index][$attribs['NAME']] = $attribs['VALUE'];
				}else{//normal
					$this->nodes[$this->node_index][$attribs['NAME']] = $attribs['VALUE'];
				}
			break;
			case 'GUIDEF':
				if( $this->embedded_switch ){//embedded
					$this->embedded[ $this->embedded_count ]->nodes[$this->node_index]['left']=$attribs['LEFT'];
					$this->embedded[ $this->embedded_count ]->nodes[$this->node_index]['top']=$attribs['TOP'];
					$this->embedded[ $this->embedded_count ]->nodes[$this->node_index]['number_ports']=$attribs['NUMBER_PORTS'];
				}else{//normal
					$this->nodes[$this->node_index]['left']=$attribs['LEFT'];
					$this->nodes[$this->node_index]['top']=$attribs['TOP'];
					$this->nodes[$this->node_index]['number_ports']=$attribs['NUMBER_PORTS'];
				}	
			break;
			//case 'PORTDEF':
				//$this->nodes[$this->node_index]['number_ports']+=1;
				//$this->nodes[$this->node_index]['port_'.$attribs['NAME']]='';
				//break;
			//----------------------------execute port
			case 'EXPOSED_PORTS':
				$this->result_index=0;
				if( $this->embedded_switch ){//embedded
					//this is just used when opening compounds from the editor
					$this->embedded[ $this->embedded_count ]->result[0]['index']='root';
					$this->embedded[ $this->embedded_count ]->result[0]['read_type']='node';
					$this->embedded[ $this->embedded_count ]->result[0]['type'] = 'tentacle_compound_open';//was tentacle_compound_open
				}else{//normal
					$this->result[0]['index']='root';
					$this->result[0]['type']='execute';
				}
			break;
			case 'PORT'://exposed ports
				//result ports, ot ports.
				if($attribs['TYPE']=='out'){
					if($this->embedded_switch){//embedded
						//$this->embedded[ $this->embedded_count ]->result[0]['number_ports'] = $this->result_index+1;
						//$this->embedded[ $this->embedded_count ]->result[0]['content'.$this->result_index] = '';
						$this->embedded[ $this->embedded_count ]->result[0][ $attribs['NAME'] ]=array('out'); //give it an attribute named right
						array_push( $this->embedded[ $this->embedded_count ]->connections, array('from_node'=>$attribs['INDEX'],'to_node'=>'root','from_port'=>$attribs['NAME'],'to_port'=>$attribs['NAME']));//add the connection to the lsit
					}else{//normal
						$this->result[0]['number_ports']=$this->result_index+1;
						$this->result[0]['content'.$this->result_index]='';
						array_push( $this->connections, array('from_node'=>$attribs['INDEX'],'to_node'=>'root','from_port'=>$attribs['NAME'],'to_port'=>'content'.$this->result_index));//add the connection to the lsit
					}
					$this->result_index+=1;//iterate number by 1
				//if ports. Ports that are compound values.
				}elseif($attribs['TYPE']=='in'){
					if($this->embedded_switch){//embedded
						$this->embedded[ $this->embedded_count ]->result[1][ $attribs['NAME'] ]=array('in'); //give it an attribute with the data type
						array_push( $this->embedded[ $this->embedded_count ]->connections, array('from_node'=>'root','to_node'=>$attribs['INDEX'],'from_port'=>$attribs['LABEL'],'to_port'=>$attribs['NAME']));//add the connection to the lsit
					}else{//chances are if it isn't embedded it is note a compound. since embedded will probably how I force none embeed to be at some stage
						//array_push( $this->connections,  array('from_node'=>'root','to_node'=>$attribs['INDEX'],'from_port'=>$attribs['LABEL'],'to_port'=>$attribs['NAME']));//add the connection to the lsit
					}
				}
			break;
			case 'EXEDEF'://where the terminal node is
				if($this->embedded_switch){//embedded
					$this->embedded[ $this->embedded_count ]->result[0]['left']=$attribs['LEFT'];
					$this->embedded[ $this->embedded_count ]->result[0]['top']=$attribs['TOP'];
				}else{//normal
					$this->result[0]['left']=$attribs['LEFT'];
					$this->result[0]['top']=$attribs['TOP'];
				}
			break;
			//----------------------------connections
			case 'CNX':
				if($this->embedded_switch){//embedded
					array_push( $this->embedded[ $this->embedded_count ]->connections, array('from_node'=>$attribs['FROM_NODE'],'to_node'=>$attribs['TO_NODE'],'from_port'=>$attribs['FROM_PORT'],'to_port'=>$attribs['TO_PORT']));	
				}else{//normal
					array_push( $this->connections, array('from_node'=>$attribs['FROM_NODE'],'to_node'=>$attribs['TO_NODE'],'from_port'=>$attribs['FROM_PORT'],'to_port'=>$attribs['TO_PORT']));	
				}
			break;
			case 'EMBEDDED_'.$this->embedded_count:
				$this->embedded_switch = true;//switch
				
				$this->embedded[ $this->embedded_count ]->name=$attribs['NAME'];
				$this->embedded[ $this->embedded_count ]->nodes=array();
				$this->embedded[ $this->embedded_count ]->connections=array();
				$this->embedded[ $this->embedded_count ]->results=array();
				$this->embedded[ $this->embedded_count ]->input=array();
			break;
	   }
	   //list_param($attribs);
	}
	
	function end_tag($parser, $name) {
	   switch ($name) {
			case 'NODE_FILE':
			break;
			case 'DESCRIPTION':
			break;
			case 'DEFINITION':
			break;
			case 'NODES':
			break;
			case 'EXPOSED_PORTS':
			break;
			case 'CONNECTIONS':
			break;
			case 'NODE':
			break;
			case 'PARAM':
			break;
			case 'GUIDEF':
			break;
			case 'PORT':
			break;
			case 'CNX':
			break;
			case 'EMBEDDED_'.$this->embedded_count:
				$this->embedded_count+=1;
			break;
	   }
	}
}
/*function tag_contents($parser,$data){echo "Content: ".$data;}*/
?>
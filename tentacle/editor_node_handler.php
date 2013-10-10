<?php
include 'path.php';
require_once "json.php";//bring in the php 4 helper for JSON calls
//**********************************
// This file is called from editor.php
// It handles the ajax call to insert the data for the selected node being inserted into the editor
// It needs the 'node' value of the the call to be an availalb node from the folder
//**********************************
$act = (isset($_GET['act']) && $_GET['act'] != '') ? $_GET['act'] : 'broke';			//get the passed node variable
$node = (isset($_GET['node']) && $_GET['node'] != '') ? $_GET['node'] : '';			//get the passed node variable
$index= (isset($_GET['index']) && $_GET['index'] != '') ? $_GET['index'] : 0;
$data = (isset($_GET['data']) && $_GET['data'] != '') ? $_GET['data'] : false;			//get the passed node variable

if(strlen($node)>0){																//again be sure that there is a varible first
	
	include_once (node_root.$node.'.php');												//import the file that the node is gonna be creataed with the help with
	
	$package = new $node;															//create the object of the node
	
	$package->index=$index;
	//$package->type=$node;
	
	if($act=='node'){
		if($data){	
			$json = new Services_JSON();
			$data_object = $json->decode($data);
			/*if(!is_object($data_object)){
				$package->assign($data_object);
			}else{//this is for creating compounds mostly. Data coming in from jason
				$array_conversion=array();
				//turning object parameres into assiciative arrays.
				foreach($data_object as $k=>$v){
					if(is_object($v)){
						foreach($v as $k2=>$v2){
							$array_conversion[$k][$k2]=$v2;
						}
					}else{
						$array_conversion[$k]=$v;
					}
				}
				$package->assign($array_conversion);
			}*/
			$package->assign( convert($data_object) );											//build out the html data nessisary, from the class object
		}
		//this function gets the data from php to give to javascipt to build a node
		$json = new Services_JSON();//we are going to make the jason pbject here instead of in the node
		print $json->encode($package->assemble());//this is the new name of the function
	}


//----------------------------
	if($act=='inspect_compound'){
		$port_data=(isset($_GET['data']) && $_GET['data'] != '') ? $_GET['data'] : '';

		$json = new Services_JSON();
		$data_object = $json->decode($port_data);
	
		print $package->inspect($data_object);//was port_data
	}
	if($act=='compound_in_port'){
		print $package->add_port('out');
	}
	if($act=='compound_out_port'){
		print $package->add_port('in');
	}
	if($act=='compound_options'){//this is called when we are trying to modify compound settings from compound editor
		$data = (isset($_GET['data']) && $_GET['data'] != '') ? $_GET['data'] : false;
		//all this if code is used above exaclty the same as this, it needs to be foldeed in eventually, and made simplet
		//all it does is iterate an object or an array. kinf of lane.
		if($data){	
			$json = new Services_JSON();
			$data_object = $json->decode($data);
			if(!is_object($data_object)){
				$package->assign($data_object);
			}else{//this is for creating compounds mostly. Data coming in from jason
				$array_conversion=array();
				//turning object parameres into assiciative arrays.
				foreach($data_object as $k=>$v){
					if(is_object($v)){
						foreach($v as $k2=>$v2){
							$array_conversion[$k][$k2]=$v2;
						}
					}else{
						$array_conversion[$k]=$v;
					}
				}
				$package->assign($array_conversion);
			}
		}
		//----------------
		//back to the basic stuff
		print $package->options();
	}
}else{																				//error
	echo 'Some how you hve managed to call this page with out a node variable,
		  which seems imposible, because we have looked through the folder
		  that builds the buttons that call this file.';
}

function convert($data_object){
	$new_array = array();
	if(!is_object($data_object)){
		$new_array = $data_object;
	}else{//this is for creating compounds mostly. Data coming in from json, i need to specifically convert data here
		$new_array = convert_object($data_object);

		//specific to embedded compounds at the moment. I need to convert some of the objects into associaative arrays
		if(isset($new_array['read_data'])){
			$count = 0;//force the key to be a number, and not a string of a number, incase that matters
			foreach($new_array['read_data']->nodes as $k=>$v){
				$new_array['read_data']->nodes[$count]=convert_object($v);
				$count+=1;
			}
			$count = 0;
			foreach($new_array['read_data']->connections as $k=>$v){
				$new_array['read_data']->connections[$count]=convert_object($v);
				$count+=1;	
			}
		}

	}
	return $new_array;
	//return convert_object($data_object);
}
function convert_object($obj){
	$new_array = array();
	foreach($obj as $k=>$v){
		$new_array[$k]=$v;
	}
	return $new_array;
}
?>
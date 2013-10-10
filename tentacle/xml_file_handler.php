<?php
include_once 'path.php';//I ned this so that I can open files. since pre assemble node function goes through tht enodes

$act = (isset($_GET['act']) && $_GET['act'] != '') ? $_GET['act'] : 'broke';			//get the passed node variable
//require_once 'html.php';															//open directory
require_once 'xml_reader.php';
require_once 'json.php';
require_once 'xml_file_browser.php';

	
$act();

function open(){
	$file_name = (isset($_GET['file']) && $_GET['file'] != '') ? $_GET['file'] : 'broke';
	$location = (isset($_GET['location']) && $_GET['location'] != '') ? $_GET['location'] : 'broke';
	
	$file= '../'.$location.'/'.$file_name.'.xml';//get the fancy passed in path

	if(file_exists($file)){
		include_once ('nodes/execute.php');
		//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		$execute_node = new execute();
		$node_file= new xml_reader($file);

		print pre_assemble_network($node_file,$execute_node->assemble());
	}else{
		print notify_no_file($file_name);//send data that says there is no file of that name
	}
}
//-------------
function open_embedded_compound(){//called from compounds
	$embedded_data = (isset($_GET['data']) && $_GET['data'] != '') ? $_GET['data'] : 'broke';//get the data
	$node_count = (isset($_GET['count']) && $_GET['count'] != '') ? $_GET['count'] : 0;//get the data
	$connection_count = (isset($_GET['ccount']) && $_GET['ccount'] != '') ? $_GET['ccount'] : 0;//get the data
	$cid = (isset($_GET['cid']) && $_GET['cid'] != '') ? $_GET['cid'] : 0;//get the data
	$nid = (isset($_GET['nid']) && $_GET['nid'] != '') ? $_GET['nid'] : 0;//get the data
	
	//$json = new Services_JSON();
	//$data_object = $json->decode($embedded_data);
	//php prior to 5.2 or 5.3 maybe does not have the built in JSON hanler. anything after, i dont have to use the above code???

	//$data_object = json_decode($embedded_data);
	//if(json_decode( $preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $embedded_data);, true ); == NULL){

	/*$embedded_data = str_replace('&quot;', '"', $embedded_data);
	$embedded_data = mb_convert_encoding($embedded_data, 'UTF-8', 'ASCII,UTF-8,ISO-8859-1');
    
    //Remove UTF-8 BOM if present, json_decode() does not like it.
    if(substr($embedded_data, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) $embedded_data = substr($embedded_data, 3);
    $embedded_data=preg_replace('/:\s*\'(([^\']|\\\\\')*)\'\s*([},])/e', "':'.json_encode(stripslashes('$1')).'$3'", $embedded_data);
    
    $embedded_data='['.$embedded_data.']';*/

    //$embedded_data = '{\"a\":1,"b":2,"c":3,"d":4,e":5}';

    $data_object = json_decode($embedded_data);

	/*if(json_decode( $embedded_data ) == NULL){
    	//print("not valid json!".json_last_error());
    	switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }
	}else{
		print("is valid json");
	}*/
	
	$data_object = json_decode($embedded_data);
	$data_object = rebuild_network($data_object,$node_count,$connection_count,$cid,$nid);//,$connection_count);	//i have to rebuild the object. JSON managed to strip the arrays, and replace them with objects. To save time here, I am gonna just turn them back into arrays
	
	include_once('nodes/tentacle_compound_open.php');//this is the node we need to open
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

	//here i am actually making the tentacle compound open node.
	//this is important
	$execute_node = new tentacle_compound_open();//
	$execute_node->assign($data_object->result[0]);//i need to give the node, the ports that are exposed somehow

	//------------------------

	print pre_assemble_network($data_object,$execute_node->assemble());
	
}
/*function open_compound(){
	
}*/
function save_compound(){
	$file_name = (isset($_GET['file']) && $_GET['file'] != '') ? $_GET['file'] : '';			//get the passed node variable
	$file_path = (isset($_GET['path']) && $_GET['path'] != '') ? $_GET['path'] : '';			//get the passed node variable

	$save_menu=open_save_window_html();//from the html.php
	$save_menu.=$file_path.':'.$file_name.'.xml<br>';
	$save_menu.=menu_text_input_nl('file_name_input',$file_name);
	$save_menu.=close_compound_save_window_html();
	print $save_menu;
}
//------------
function browse(){
	$browser=new xml_file_browser();
	echo $browser->browse_to_open($browser->file_list);
}
//--------------------

//---these are the function that make the arrays that I send to javascript to make the nodes, and connections
function pre_assemble_network($data,$terminal){
	//nodes
	$nodes=array();//'';//start capturing html data REVISION. capture the node data for javascript
	while(list($key,$val) = each( $data->nodes )) {//for each node,  

		if($val['read_type']=='embedded'){//handle the embedded compound data initiation			
			preg_match_all("/[0-9]+/", $val['type'], $embedded_id);//THIS STRIPS OUT THE ID NUMBER FROM THE STRING...i have to do this first, because I am going to overwrite 'type' later
			$val['compound_id']=$embedded_id[0][0];//--- setting the varialbe so that javascript will know which embedded node to reference
			$val['read_data']=$data->embedded[$embedded_id[0][0]];//i need to give it the embedded data, so it can know how to make the ports array for the inspect page.
			$val['type'] = 'tentacle_compound';//im manually set this value because it is set as [embedded_0*], and needs to open a tentacle compound node
		}

		//THIS IS WHERE I AM ACTUALLY LOOKING IN THE SPECIFIC FOLDER TO FIND THE NODES PHP FILE.
		//I WANT TO KNOW THIS BECAYSE IF I WANT TO BE ABLE TO NEST NODES< I"M GONNA HAVE TO MODIFIY THIS

		include_once ('nodes/'.$val['type'].'.php');

		//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

		$newnode= new $val['type'];
		$newnode->assign($val);
		$nodes[count($nodes)] = $newnode->assemble();
	}

	//connection
	$connections=array();
	while(list($key,$val) = each( $data->connections )) {//for each connection, build out the lines
		$connections[count($connections)]=$val;
	}

	//wrap it up
	$json = new Services_JSON();
	$n_data= array();
	$n_data[0]=$nodes;
	$n_data[1]=$connections;
	$n_data[2]=$terminal;
	$n_data[3]=$data->embedded;//pass the embedded date to javascript

	$data_object=$json->encode($n_data);

	return $data_object;

}

function rebuild_network($network,$count_offset,$connection_count_offset,$cid,$nid){//called after javascript sends us embedded data.

	//do nodes first
	$n = array();
	reset($network->nodes);
	while( list($k,$v)=each($network->nodes) ){
		reset($v);
		while(list($kb,$vb)=each($v)){//loop thorugh each value that shoule be an array. ie nodes->type = nodes['type]
			$n[$k][$kb]=$vb;
			if($kb=='index') $n[$k][$kb] = $vb+$count_offset; 
		}
	}
	$network->nodes=$n;
	//--------
	//---i need to put result[1] in there too for the compound open to know how to do.
	//do result[0] next
	/*$r=array();
	reset( $network->result[0] );
	while(list($k,$v)=each($network->result[0])){
		$r[$k]=$v;
	}*/
	/*reset( $network->result[1] );
	while(list($k,$v)=each($network->result[1])){
		$r[$k]=$v;
	}*/
	//$r['fuck']='';

	

	//connections
	//-----------

	//
//there is soething happeneing here, that is causeing some issues when re-openeing an embedded compound anyway
	//
	//$uuu='';

	$c=array();

	//-------
	$tmp_ports_out=array();//need these to save them for later from this loop
	$tmp_ports_in=array();
	//
	reset($network->connections);
	while( list($k,$v)=each($network->connections) ){
		reset($v);
		while( list($kb,$vb)=each($v) ){
			$c[$k][$kb]=$vb;
			if($kb=='from_node' || $kb=='to_node'){ 
				//$nnn='false';
				//if( $vb!='root') {
				if( is_numeric($vb) ) { //checking that it is 'root' does not seem to work for 0 not sure why
					$c[$k][$kb]=$vb+$count_offset;//offset the number for everything but the root node
					//$nnn='true';
				}
				//$uuu.=$vb.'@'.$nnn.'*';
			}


		}
	}
	$network->connections = $c;

	//do the root node now since I have done the connections and should know what the in and out ports are now
	//making the data for the root node, the tentacle compound open
	//tentacle compound open
	// dont even need this stuff right now
	/*reset( $network->result[0] );
	while(list($k,$v)=each($network->result[0])){
		$r[$k]=$v;
	}*/	

	$r=array();

	$r['name']=$network->name;
	$r['read_data']->connections=$c;//['connections']=$c;//just sending along the connection data as something that I can read from the node later
	
	$r['compound_node_offset'] = $count_offset;
	$r['compound_connection_offset'] = $connection_count_offset;
	$r['compound_id']=$cid;
	$r['compound_node_id']=$nid;

	//$r['trash_data']=' '.$uuu.'--------';

	$network->result[0] = $r;
	
	//-----------
	return $network;
}
//---
function notify_no_file($file){
	$json = new Services_JSON();
	$data=array( 'no_file',$file);
	$data_object=$json->encode($data);
	return $data_object;
}
?>

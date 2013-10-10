<?php

/*function stripslashes_deep($value)
{
$value = is_array($value) ?
array_map('stripslashes_deep', $value) :
stripslashes($value);

return $value;
}

if((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) || (ini_get('magic_quotes_sybase') && (strtolower(ini_get('magic_quotes_sybase'))!="off")) ){
stripslashes_deep($_GET);
stripslashes_deep($_POST);
stripslashes_deep($_COOKIE);
}*/




require_once "json.php";//bring in the php 4 helper for JSON calls

$data = (isset($_POST['data']) && $_POST['data'] != '') ? $_POST['data'] : '';			//get the passed node variable


function echo_nodes($node_array){
	$node_string='';
	$tab = "\t\t";
	$node_string.= $tab.'<nodes>'."\n";
	foreach($node_array as $var=>$val){//for each node
		$node_string.=$tab."\t".'<node type="'.$node_array[$var]->type.'" index="'.$node_array[$var]->index.'" mode="'.$node_array[$var]->mode.'">'."\n";
		foreach($node_array[$var]->param as $p=>$v){//for paramater
			$node_string.=$tab.$tab.'<param ';
			foreach($v as $pn=>$vn){
				$node_string.='name="'.$pn.'" value="'.$vn.'"></param>'."\n";
			}
		}
		$node_string.=$tab.$tab.'<guidef left="'.$node_array[$var]->guidef->left.'" top="'.$node_array[$var]->guidef->top.'" number_ports="'.$node_array[$var]->guidef->number_ports.'"></guidef>'."\n";//gui info
		$node_string.=$tab."\t".'</node>'."\n";
	}
	$node_string.= $tab.'</nodes>'."\n";
	return $node_string;
}
function echo_exposed($exposed_array){
	$exposed_string='';
	$tab = "\t\t";
	$tag = 'port';//this is so i can switch the port on the last one for the exedef
	$exposed_string.=$tab.'<exposed_ports>'."\n";	
	for($i=0;$i<=sizeof($exposed_array)-1;$i++){
		if($i==sizeof($exposed_array)-1) $tag = 'exedef';
		$exposed_string.=$tab."\t".'<'.$tag.' ';
		foreach($exposed_array[$i] as $var=>$val){
			$exposed_string.=$var.'="'.$val.'" ';
		}
		$exposed_string.='></'.$tag.'>'."\n";
	}
	$exposed_string.=$tab.'</exposed_ports>'."\n";
	return $exposed_string;
}
function echo_connections($connection_array){
	$connection_string='';
	$tab = "\t\t";
	$connection_string.= $tab.'<connections>'."\n";
	for($i=0;$i<=sizeof($connection_array)-1;$i++){
		$connection_string.=$tab."\t".'<cnx ';
		foreach($connection_array[$i] as $var=>$val){
			$connection_string.=$var.'="'.$val.'" ';
		}
		$connection_string.='></cnx>'."\n";
	}
	$connection_string.= $tab.'</connections>'."\n";
	return $connection_string;
}

//------------------embedded writing methods
function echo_nodes_e($node_array){
	$node_string='';
	$tab = "\t";
	$node_string.= $tab.$tab.'<nodes>'."\n";
	foreach($node_array as $var=>$val){//for each node
		$node_string.=$tab.$tab.$tab.'<node type="'.$node_array[$var]->type.'" index="'.$node_array[$var]->index.'" mode="'.$node_array[$var]->mode.'">'."\n";
		$exclude_array=array('read_type','index','type','mode','left','top','number_ports');//exlude these when building new node
		foreach($node_array[$var] as $p=>$v){//for paramater
			if(!in_array($p,$exclude_array)){//if it is not in the exlude array
				$node_string.=$tab.$tab.$tab.$tab.'<param ';
				//foreach($v as $pn=>$vn){
					$node_string.='name="'.$p.'" value="'.$v.'"></param>'."\n";
				//}
			}
		}
		$node_string.=$tab.$tab.$tab.$tab.'<guidef left="'.$node_array[$var]->left.'" top="'.$node_array[$var]->top.'" number_ports="'.$node_array[$var]->number_ports.'"></guidef>'."\n";//gui info
		$node_string.=$tab.$tab.$tab.'</node>'."\n";
	}
	$node_string.= $tab.$tab.'</nodes>'."\n";
	return $node_string;
}
function echo_exposed_e($connections_array,$result_array){
	$exposed_string='';
	$tab="\t";
	$exposed_string.=$tab.$tab.'<exposed_ports>'."\n";
	foreach($connections_array as $c=>$c_data){
		if($connections_array[$c]->to_node=='root' || $connections_array[$c]->from_node=='root'){//going through connections, only use the ones that are to or from the root
			$exposed_string.=$tab.$tab.$tab.'<port ';
			if($connections_array[$c]->to_node=='root'){
				$exposed_string.='index="'.$connections_array[$c]->from_node.'" ';
			}else{
				$exposed_string.='index="'.$connections_array[$c]->to_node.'" ';
			}
			
			$exposed_string.='name="'.$connections_array[$c]->from_port.'" ';
			$exposed_string.='label="'.$connections_array[$c]->to_port.'" ';
			if($connections_array[$c]->to_node=='root'){
				$exposed_string.='type="out" ';
			}else{
				$exposed_string.='type="in" ';
			}
			$exposed_string.='></port>'."\n";
		}
	}
	
	$exposed_string.=$tab.$tab.$tab.'<exedef left="'.$result_array[0]->left.'" top="'.$result_array[0]->top.'" ></exedef>'."\n";
	$exposed_string.=$tab.$tab.'</exposed_ports>'."\n";
	return $exposed_string;
}
function echo_connections_e($connections_array){
	$connection_string='';
	$tab = "\t";
	$connection_string.= $tab.$tab.'<connections>'."\n";
	foreach($connections_array as $c=>$c_data){
		if($connections_array[$c]->to_node!='root' && $connections_array[$c]->from_node!='root'){
			$connection_string.=$tab.$tab.$tab.'<cnx ';
			$connection_string.='from_node="'.$connections_array[$c]->from_node.'" ';
			$connection_string.='from_port="'.$connections_array[$c]->from_port.'" ';
			$connection_string.='to_node="'.$connections_array[$c]->to_node.'" ';
			$connection_string.='to_port="'.$connections_array[$c]->to_port.'"';
			$connection_string.='></cnx>'."\n";
		}
	}
	$connection_string.= $tab.$tab.'</connections>'."\n";
	return $connection_string;
}
function echo_embedded($embedded_array){
	$embedded_string='';
	foreach($embedded_array as $c=>$c_data){
		$embedded_string.="\t".'<embedded_'.$c.' name="'.$c_data->name.'">'."\n";
		$embedded_string.="\t\t".'<description></description>'."\n";
		$embedded_string.=echo_nodes_e($c_data->nodes);
		$embedded_string.=echo_exposed_e($c_data->connections,$c_data->result);//i have to do some rearranging here
		$embedded_string.=echo_connections_e($c_data->connections);
		$embedded_string.="\t".'</embedded_'.$c.'>'."\n";
	}
	return $embedded_string;
}
//pre php 5
$json = new Services_JSON();
$data_object=$json->decode($data);
//php 5, still don't work
//$data_object=json_decode($data);

$f='../'.$data_object->save_as.'.xml';//file name
$file= fopen($f, 'w')or die(print_r($data_object,1));

//foreach($data_object as $var=>$val){
	//echo 'var:'.$var;
//}
//echo 'specific:'.$data_object->value;
//print_r($data_object);

//if we are saving an embedded compound scene, we need to replace the names with the relative embedded array
if (sizeof($data_object->embedded)>0){
	foreach($data_object->nodes as $var_n=>$val_n){//for each node
		foreach( $data_object->embedded as $var_e=>$val_e ){
			if( $data_object->nodes[$var_n]->type == $data_object->embedded[$var_e]->name ) $data_object->nodes[$var_n]->type = '[embedded_'.$var_e.']'; 
		}
	}
}
//
$xml_string = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
$xml_string.= '<node_file type="tentacle" group="page" name="latest" tasks="build latest page" author="jimmy gass" version="1.0" bgcolor="">'."\n"; 
$xml_string.= "\t".'<description>This is a custom built page for the latest page.</description>'."\n";
$xml_string.= "\t".'<definition>'."\n";
//--------data
$xml_string.= echo_nodes($data_object->nodes);
$xml_string.= echo_exposed($data_object->exposed_ports);
$xml_string.= echo_connections($data_object->connections);
//---------------
$xml_string.= "\t".'</definition>'."\n";
//----------
if (sizeof($data_object->embedded)>0){
	$xml_string.= echo_embedded($data_object->embedded);
}
//----------
$xml_string.= '</node_file>';


//echo '<xmp>';
//iterate_data($data_object);
//echo $xml_string;
//echo '</xmp>';
if(is_writable($f)){
	$xml_string = mb_convert_encoding($xml_string, 'UTF-8', 'auto');
	fwrite($file, $xml_string);
	fclose($file);
	print $f.':written sucessfully';
}else{
	print 'unsucessful writting:'.$f;
}

//echo "XML has been written.  <a href=\"latest.xml\">View the XML.</a>";
?>
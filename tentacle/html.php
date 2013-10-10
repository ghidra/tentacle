<?php
include_once 'allowed_nodes.php';//need to to get color for nodes creation
/*******************************/
//nodes
/*function open_node_html($index,$left,$top,$type){
	return '<div id="node_'.$index.'" class="aBox" style="left:'.$left.';top:'.$top.'">';
}
function close_node_html(){
	return '</div>';
}
function node_header($index,$type,$mode=0){
	//i shouldnt need mode anymore
	//allowed nodes to get the group of which node belongs.
	$node_list=new allowed_nodes();
	$node_group=$node_list->get_group($type);
	$color_a=$node_list->get_color($type,0);
	$color_b=$node_list->get_color($type,1);
	//-----------------------------------------
	$s= '<div id="node_bar_color_'.$index.'" class="node_bar_'.$node_group.'" onmousedown="tentacle.selection.add(event,\''.$index.'\',\''.$color_a.'\',\''.$color_b.'\');" onDblclick="tentacle.nodes.inspect(\''.$index.'\')">';//node.drag(event, \''.$index.'\');
	if($index!='root'){
		$s.= '<div class="close_out" onmousedown="tentacle.nodes.remove('.$index.')"></div>';	 
		$s.='<div class="close_capsule" onmousedown="tentacle.nodes.toggle_capsule('.$index.')"><div class="close_capsule_shape"></div></div>';
		$s.='<div id="node_read_type_'.$index.'" style="display:none;">standard</div>';
	}
	$s.= '<div id="node_'.$index.'_type">'.$type.'</div>';
	$s.= '</div>';// the \ exscapes before the ' so I can have more than one quote in quotes
	return $s;
}
//-------------------COMPOUNDS STUFF
function compound_header($index,$name,$mode=0,$colors=array('#ff0000','#00ff00')){
	$color_a=$colors[0];
	$color_a=$colors[1];
	$s= '<div id="node_bar_color_'.$index.'" class="node_bar_etc" onmousedown="tentacle.selection.add(event,\''.$index.'\',\''.$color_a.'\',\''.$color_b.'\');" onDblclick="compound.inspect(event)">';//node.drag(event, \''.$index.'\');
	if($index!='root'){
		$s.= '<div class="close_out" onmousedown="tentacle.nodes.remove('.$index.')"></div>';	 
		$s.='<div class="close_capsule" onmousedown="tentacle.nodes.toggle_capsule('.$index.')"><div class="close_capsule_shape"><div id="node_capsule_'.$index.'" style="display:none;">'.$mode.'</div></div></div>';
		$s.='<div class="close_capsule" onmousedown="compound.open('.$index.')"></div>';
		$s.='<div id="node_read_type_'.$index.'" style="display:none;">embedded</div>';
	}
	$s.= '<div id="node_'.$index.'_type">'.$name.'</div>';
	$s.= '</div>';// the \ exscapes before the ' so I can have more than one quote in quotes
	return $s;
}
function embedded_tag($index,$id){
	return '<div id="embedded_id_'.$index.'" style="display:none;">'.$id.'</div>';
}
*/
//-----these are only used for openeing of a compound to edit in editor
function open_compound_html($index,$left,$top,$type){
	return '<div id="node_'.$index.'" style="position:fixed;top:32px;left:0px;width:100%;z-index:9997">';//<div class="aBox" style="position:fixed;left:2px;top:30px;">
}
function top_bun_compound_html($index,$name){
	return '<div id="compound_id_label" style="background-color:#333333;width:100%;text-align:center;margin-bottom:2px" onmousedown="compound.options(event)">'.$name.'</div>
			<div id="compound_out_ports" style="width:100px;float:left">';

//<div class="close_compound"></div>
	//return '</div><div class="aBox" style="position:fixed;right:2px;top:30px;">';
}
function middle_bun_compound_html(){
	return '</div>
			<div id="compound_in_ports" style="width:100px;float:right">'.compound_commands();
}
function close_compound_html(){
	return '</div></div>';//</div>
}
//--------------
//------right side
function compound_commands(){
	$s='<div class="in_string" style="width:100%;background-color:#D1670E;margin-bottom:2px">
			<div class="close_capsule" style="margin-left:2px;float:left;" onmousedown="compound.save_and_close()"></div>
			<div class="close_out" style="margin-left:2px;float:left;" onmousedown="compound.close()"></div><br>
		</div>';
	return $s;
}
function compound_input($type,$value,$index){//this id the node to compound
	$s='<div class="in_string" style="margin-bottom:2px">';
	$s.= '<div class="in_port" id="'.$type.'_'.$index.'" ></div>';
	$s.= '<div class="port_string">'.$type.':'.$value.'</div>';
	$s.= '</div>';
	return $s;
}
function compound_in_inwait($type,$index){
	$s= '<div id="in_'.$index.'_port" class="in_string">&nbsp';
	$s.= '<div class="compound_inwait_port" id="in_'.$index.'"></div>';
	//$s.= '<div style="float:left;">&nbsp'.$type.'</div>';
	$s.= '</div>';
	return $s;
}
//----left side
function compound_output($type,$value,$index){//this is the compound to node
	$s= '<div class="out_string" style="margin-bottom:2px">';
	$s.= '<div class="out_port" id="'.$type.'_'.$index.'"></div>';
	$s.= '<div class="port_string">'.$type.':'.$value.'</div>';
	$s.= '</div>';
	return $s;
}
function compound_out_inwait($type,$index){
	$s= '<div id="out_'.$index.'_port" class="in_string">&nbsp';
	$s.= '<div class="inwait_port" style="float:right" id="out_'.$index.'" onmousedown="compound.find_in_port(event)"></div>';
	//$s.= '<div style="float:right;">&nbsp'.$type.'</div>';
	$s.= '</div>';
	return $s;
}
//--------
function new_compound_port($kind,$label){//$kind allows this to be both in and out
	$s= '<div class="'.$kind.'_port" id="'.$label.'_root"></div>';
	$s.= '<div class="port_string">'.$label.':</div>';
	return $s;
	//return compound_output($label,'','root');
}
function compound_options(){
	$s='<div>options</div>';
	return $s;
}
//----------------
//----------------
/*
function node_output($type,$value,$index){
	$s= '<div class="out_string">';
	$s.= '<div class="out_port" id="'.$type.'_'.$index.'" onmousedown="tentacle.connections.create(event,\''.$index.'\',\''.$type.'\')"></div>';//connection.create(event,\''.$index.'\',\''.$type.'\'),
	$s.= '&nbsp;&nbsp;'.$type.':'.$value.'<br>';
	$s.= '</div>';
	return $s;
}
function node_input($type,$value,$index){
	$s= '<div id="'.$type.'_'.$index.'_port" class="in_string">';
	if($index=='root'){//root is a string and needs to be sent as suck in the popconnection javascript call
		$s.= '<div class="in_port" id="'.$type.'_'.$index.'" onmousedown="tentacle.connections.pop(event,\''.$index.'\',\''.$type.'\')"></div>';
	}else{
		$s.= '<div class="in_port" id="'.$type.'_'.$index.'" onmousedown="tentacle.connections.pop(event,'.$index.',\''.$type.'\')"></div>';
	}
	$s.= '<div id="'.$type.'_'.$index.'_type" style="float:left;">'.$type.':</div>';
	$s.= '<div id="'.$type.'_'.$index.'_data">'.$value.'</div>';
	//echo '&nbsp;&nbsp;'.$type.':'.$value.'<br>';
	$s.= '</div>';
	return $s;
}
function hidden_input($type,$value,$index){
	$s= '<div id="'.$type.'_'.$index.'_port" class="in_string" style="clear:left;">';//style="display:none;"
	$s.= '<div id="'.$type.'_'.$index.'" style="float:left;">'.$type.':</div>';//class="in_port"
	$s.= '<div id="'.$type.'_'.$index.'_data" style="float:left;">'.$value.'</div>';
	//echo '&nbsp;&nbsp;'.$type.':'.$value.'<br>';
	$s.= '</div>';
	return $s;
}
function new_input($type,$value,$index){//the only difference is the instring wrapper, cause javascript makes it
	if($index=='root'){//root is a string and needs to be sent as suck in the popconnection javascript call
		$s= '<div class="in_port" id="'.$type.'_'.$index.'" onmousedown="tentacle.connections.pop(event,\''.$index.'\',\''.$type.'\')"></div>';
	}else{
		$s= '<div class="in_port" id="'.$type.'_'.$index.'" onmousedown="tentacle.connections.pop(event,'.$index.',\''.$type.'\')"></div>';
	}
	$s.= '<div id="'.$type.'_'.$index.'_type" style="float:left;">'.$type.':</div>';
	$s.= '<div id="'.$type.'_'.$index.'_data">'.$value.'</div>';
	return $s;
}
function node_inwait($type,$index,$node_name){
	//$port_part=explode(" ",$type);//break off the name of the port. Meaning that the "newport" port must be named accordingly
	$s= '<div id="'.$type.'_'.$index.'_port" class="in_string">';
	//echo '<div class="inwait_port" id="'.$index.'_'.$type.'" onmouseup="insert_port(\''.$index.'\',\''.$port_part[1].'\',\''.$node_name.'\')"></div>';
	$s.= '<div class="inwait_port" id="'.$type.'_'.$index.'"></div>';
	$s.= '<div style="float:left;">'.$type.'</div>';
	$s.= '</div>';
	return $s;
}

function node_thumb($location){
	$file_raw=explode('.',$location);
	
	$s='<img src="../media/images/thumbnails/'.$file_raw[0].'.jpg">';
	return $s;
}
*/
/*******************************/
//property window
/*
function property_header($index,$type){
	//the abox, is created in javascript
	$s= '<div class="aBar" onmousedown="floating_window.drag(event, \'property_'.$index.'\')">'.$type.':'.$index;// the \ exscapes before the  so I can have more than one quote in quotes
	
	//echo '<div class="close_out" onmouseup="removeLayer(\'property_'.$index.'\')"></div></div>';
	$s.= '<div class="close_out" onmouseup="tentacle.nodes.update(\''.$index.'\')"></div></div>';

	$s.= '<form name="property_'.$type.'_'.$index.'" action="" method="GET" style="margin-bottom:0px;">';//action is enpty cause javascript is handling the stuff. GET is basically default, but also useless here
	return $s;
}
function property_header_compound($index,$type){
	//the abox, is created in javascript
	$s= '<div class="aBar" onmousedown="floating_window.drag(event, \'property_'.$index.'\')">'.$type.':'.$index;// the \ exscapes before the  so I can have more than one quote in quotes
	
	//echo '<div class="close_out" onmouseup="removeLayer(\'property_'.$index.'\')"></div></div>';
	$s.= '<div class="close_out" onmouseup="compound.update_options(';
	if($index=='root'){
		$s.='\'root\'';//need to do this so that the rende window can close
	}else{
		$s.=$index;
	}
	$s.=',\''.$type.'\')"></div></div>';
	$s.= '<form name="property_'.$type.'_'.$index.'" action="" method="GET" style="margin-bottom:0px;">';//action is enpty cause javascript is handling the stuff. GET is basically default, but also useless here
	return $s;
}
function open_prop_attr_html($id){
	$s= '<div class="bBar" onmousedown="tentacle.utilities.show_hide(\'attr_'.$id.'\')" style="margin-top:1px;">'.$id.':attributes</div>';
	$s.='<div id="attr_'.$id.'" style="display:none;">';
	return $s;
}
function close_prop_attr_html(){
	return '</div>';
}
function property_close(){
	return '</form>';
}
function property_drop_down($param,$options,$selected){
	$s='<select name="'.$param.'">';
	for($i=0;$i<=sizeof($options)-1;$i++){
		$s.='<option value="'.$options[$i].'"';
		if($options[$i]==$selected){
			$s.=' selected';
		}else{
		
		}
		$s.='>'.$options[$i].'</option>';
	}
	$s.='</select>:'.$param;
	return $s;
	//return '<input name="'.$param.'" type="text" size="20" value="'.$value.'">:'.$param.'<br>';
}
function property_boolean($param,$value){
	$s= '<input  name="'.$param.'" type="checkbox" value="true"';
	if($value=='true'){
		$s.=' checked="checked"';
	}
	$s.='/>:'.$param.'<br>';
	return $s;
}
function property_text_input($param,$value){
	return '<input name="'.$param.'" type="text" size="20" value="'.$value.'">:'.$param.'<br>';
}
function property_text_input_nl($param,$value){//nl is for no label
	return '<input name="'.$param.'" type="text" size="20" value="'.$value.'"><br>';
}
function property_textarea_input($param,$value){//s if for short
	return '<textarea name="'.$param.'" cols="40" rows="10" style="width:100%">'.$value.'</textarea><br>';
}
*/
/*******************************/
//menu
function open_menu_bar(){
	$s= '<div class="fixed_menu">
			<div class="dd_container">
				<div class="menu_bar_stripe">
					<div class="menu_button_right" onmouseover="tentacle.drop_down.open(\'dd_nodes\',0);tentacle.console.toggle(\'rollover\')" onmouseout="tentacle.drop_down.close();" style="background-color:black;float:left;display:none;" id="node_menu">nodes</div>
					<div id="compound_menu_bar" style="float:left"><div class="menu_button_right" onmousedown="tentacle.selection.compound();" style="background-color:black;float:left;display:none;" id="create_compound">c</div></div>
					<div class="menu_button_right" onmouseover="tentacle.drop_down.open(\'dd_tentacle\',0)" onmouseout="tentacle.drop_down.close()" style="background-color:black;">tentacle</div>
					<div class="menu_button_right" onmousedown="tentacle.i_o.save_comp()" style="background-color:black;display:none;" id="console_file" > </div>
					<div style="clear:both;"></div>
		 		</div>';
	//$s.= '<a href="logout.php"><div class="menu_button_right">logout</div></a>';
	//$s.= '<div class="menu_button_right" onmousedown="browse_xml_files()">open</div>';
	//$s.= '<div class="menu_button_right" onmousedown="new_comp()">new</div>';	
	//$s.= '<div class="menu_button_left" onmousedown="add_db_nav_window()">new page</div>';
	return $s;
}

function close_menu_bar(){
	$s= '	</div>
		</div>';
	
	$s.= '<div id="debug_out" class="debug_out">';
	$s.= '	<div id="variables_out" class="variables_out" style="margin-top:30px;display:none"> variables (v)</div>';
	$s.= '	<div id="console_out" class="console_out" style="margin-top:30px;display:none"> console (c)</div>';
	$s.= '</div>';

	return $s;
}
function right_menu_button(){
	
}
function open_db_menu_html($open=false){
	$visible = ($open) ? 'block' : 'none';//shorthand if then
		
	$s= '<div id="dd_tentacle" class="menu_dd_nav" onmouseover="tentacle.drop_down.cancel_close()" onmouseout="tentacle.drop_down.close()">
			<div id="database_buttons">
			<div class="menu_button_right" style="margin-right:2px;" onmouseover="tentacle.drop_down.open(\'dd_index\',1)">index</div>
			<div id="dd_index" class="menu_dd_nav">';
	$s.= open_page_button_html('media/pages','index');
	$s.='	</div>';

	return $s;
}
function close_db_menu_html(){
	return '</div>
			<div class="nav_button_option" style="clear:right;margin-right:20px;" onmouseover="tentacle.drop_down.level_close(1)" onmousedown="tentacle.database.add_nav_window()">new page</div>
		</div>';
}
function database_menu_buttons($nav_data){//$title,$id,$link,$type
	$id=$nav_data['id'];
	$title=$nav_data['title'];
	$type=$nav_data['type'];
	$link=$nav_data['link'];
	$visibility=$nav_data['visibility'];
	
	if($type=='album_images'){
		$class = ($visibility) ? 'menu_button_image_album' : 'menu_button_image_album_invisible' ;
		//$s= '<div class="menu_button_image_album" style="clear:right;margin-right:20px;" onmouseover="tentacle.drop_down.open(\'dd_'.$id.'\',1)" onmouseup="tentacle.database.inspect_page('.$id.')">'.$title.'</div>';
	}else if($type=='album_movies'){
		$class = ($visibility) ? 'menu_button_movie_album' : 'menu_button_movie_album_invisible' ;
		//$s= '<div class="menu_button_movie_album" style="clear:right;margin-right:20px;" onmouseover="tentacle.drop_down.open(\'dd_'.$id.'\',1)" onmouseup="tentacle.database.inspect_page('.$id.')">'.$title.'</div>';
	}else{
		$class = ($visibility) ? 'menu_button_right' : 'menu_button_right_invisible' ;
	}
	
	$s= '<div class="'.$class.'" style="clear:right;margin-right:20px;" onmouseover="tentacle.drop_down.open(\'dd_'.$id.'\',1)" onmouseup="tentacle.database.inspect_page('.$id.')">'.$title.'</div>';

	//$s= '<div class="menu_button_right" style="clear:right;margin-right:20px;" onmouseover="tentacle.drop_down.open(\'dd_'.$id.'\',1)" onmouseup="tentacle.database.inspect_page('.$id.')">'.$title.'</div>';
	
	$s.='<div id="dd_'.$id.'" class="menu_dd_nav">';
	
	if($type!='ind') $s.= upload_element_button_html($type,$link);
	$s.= open_page_button_html('media/pages/tentacle_navigation',$link);
	$s.= remove_nav_button_html($id);
	
	$s.='</div>';

	return $s; 
}
/*******************************/
//build save menu
function open_save_window_html(){
	$s= '<div class="bBar" onmousedown="floating_window.drag(event, \'save_window\')">save:<div class="close_out" onmousedown="tentacle.utilities.remove_element(\'save_window\')"></div></div>';
	$s.= '<form name="save_form" action="" method="GET" style="margin-bottom:0px;">';//action is enpty cause javascript is handling the stuff. GET is basically default, but also useless here
	return $s;
}
function close_save_window_html(){
	$s= '</form>';
	$s.='<div class="menu_button_left" style="margin-top:2px;" onmouseup="tentacle.i_o.write_comp();">save</div>';
	return $s;
}
function close_compound_save_window_html(){
	$s= '</form>';
	$s.='<div class="menu_button_left" style="margin-top:2px;" onmouseup="compound.save();">save</div>';
	return $s;
}
function menu_text_input_nl($param,$value){//nl is for no label
	return '<input name="'.$param.'" type="text" size="20" style="width:300px;" value="'.$value.'"><br>';
}
/*******************************/
//build browse menu
function open_browse_window_html(){
	return '<div class="bBar" onmousedown="floating_window.drag(event, \'browse_xml_window\')">files:<div class="close_out" onmousedown="tentacle.utilities.remove_element(\'browse_xml_window\')"></div></div>';//names browse xml in javasciprt
}
function close_browse_window_html(){
	//return '</div>';
}
function open_browse_menu_fold($type,$open=true){
	$visible = ($open) ? 'block' : 'none';//shorthand if then

	$s= '<div class="node_box"><div class="bBar" onmousedown="tentacle.utilities.show_hide(\'browse_menu_'.$type.'\')">'.$type.'</div>';
	$s.='<div id="browse_menu_'.$type.'" style="display:'.$visible.';">';
	return $s;
}
function close_browse_menu_fold(){
	return '</div></div>';
}
function browse_file($file,$path){
	$s=  '<div class="menu_button_left" onmousedown="tentacle.i_o.open_comp(\''.$file.'\',\''.$path.'\')" style="margin-top:2px;">';
	$s.= $file.'<br>';
	$s.= '</div>';
	return $s;
}
/*******************************/
//build node menu
function open_node_menu_html($type){
	$s= '<div class="node_button_'.$type.'" style="clear:left;">'.$type.'</div>';
	$s.='<div class="node_box" style="margin-left:20px;">';
	return $s;
}
function close_node_menu_html(){
	return '</div>';
}
function menu_node($node,$type){
	
	$s=  '<div class="node_button_'.$type.'" onmousedown="tentacle.nodes.insert(\''.$node.'\')">';
	$s.= $node.'<br>';
	$s.= '</div>';
	return $s;
}
/*******************************/
//database goodness
function open_navdata_html($title,$id){
	return '<div class="bBar" onmousedown="floating_window.drag(event, \'navigation_'.$id.'\')">'.$title.':<div class="close_out" onmousedown="tentacle.utilities.remove_element(\'navigation_'.$id.'\')"></div></div>';
}
function close_navdata_html(){
	return '</div>';
}
function new_db_entry_html(){
	$s='<form name="new_table_form" action="" method="GET" enctype="multipart/form-data" name="frmAlbum" id="frmAlbum">
	<input name="txtName" type="text" id="txtName" value="title"><br>
	<!--<input name="fleImage" type="text" id="fleImage" value="link">-->
	
	<select name="mtxDesc">
	<option value="ind">page</option>
	<option value="album_images">image album</option>
	<option value="album_movies">movie album</option>
	</select><br>
	
	<!--<div class="BG">
	<input type="radio" name="mtxDesc" value="ind" checked> independent page<br>
	<input type="radio" name="mtxDesc" value="album_images" > image album<br>
	<input type="radio" name="mtxDesc" value="album_movies" > movie album
	</div>-->
	
	<input type="checkbox" name="visibility" value="0">:hidden<br>
	
	<input name="btnAdd" type="button" onclick="tentacle.database.add_nav()" id="btnAdd" value="add new page">
	</form>';
	return $s;
}

function open_page_button_html($link,$page){
	return  '<div class="nav_button_option" onmousedown="tentacle.i_o.open_comp(\''.$page.'\',\''.$link.'\')">open</div>';
}
function remove_nav_button_html($id){
	return  '<div class="nav_button_option" onmousedown="datatbase.remove_nav('.$id.')">delete</div>';
}
function upload_element_button_html($type,$link){
	if($type=='album_images') $t='image';
	if($type=='album_movies') $t='movie';
	//return '<div class="menu_button_right" style="margin-top:1px;" onmousedown="upload_dialogue(\''.$type.'\',\''.$link.'\')">upload '.$t.'</div>';
	return '<div class="nav_button_option" onmousedown="file_handler.upload_dialogue(\''.$type.'\',\''.$link.'\')">upload</div>';
}
function modify_nav_html($nav){
	$s='<div class="bBar" style="margin-top:1px;" onmousedown="tentacle.utilities.show_hide(\'modify_nav_'.$nav['id'].'\')">modify navigation data:</div>';
	$s.='<div id="modify_nav_'.$nav['id'].'" style="display:none;">';
	$s.='<form action="" method="GET" name="frmAlbum" id="frmAlbum" style="margin-bottom:0px;clear:left;">';
	$s.='<input name="txtName" type="text" id="txtName" value="'.$nav['title'].'"><br>';
	$s.='<input name="mtxDesc" type="text" id="mtxDesc" value="'.$nav['type'].'"><br>';
	$s.='<input name="fleImage" type="text" id="fleImage" value="'.$nav['link'].'"><br>';
	$s.='<input name="btnAdd" type="submit" id="btnAdd" value="Modify Nav"><input name="hidnId" type="hidden" id="hidnId" value="'.$nav['id'].'">';
	$s.='</form>';
	$s.='</div>';
	
	return $s;
}
//------upload stuff
function upload_dialogue_html_oldanduseless($t,$a,$data_entry){//type (images,movies) album (link to album) data entry true or false
	$s='<div id="upload">
		<form id="uploadform" action="upload-filehandler.php" enctype="multipart/form-data" method="POST" target="filehandler">
			<input type="file" name="upload" id="upload_file"/>';
	
	if($t=='album_images') {
		if($data_entry) $s.='<input type="text" name="mtxDesc"id="mtxDesc" value="title"><br><input type="hidden" name="data_input" value="true"/>';
		$s.='<input type="hidden" name="album_type" value="'.$t.'"/><input type="hidden" name="folder" value="images"/><input type="hidden" name="next" id="upload_next" value="thumb_me" />';
	}elseif($t=='album_movies'){
		if($data_entry) $s.='<input type="text" name="moviewidth" id="moviewidth" value="width"><input type="text" name="movieheight" id="movieheight" value="height"><br><input name="movieDesc" type="text" id="movieDesc" value="title"><br><input type="hidden" name="data_input" value="true"/>';
	 	$s.='<input type="hidden" name="album_type" value="'.$t.'"/><input type="hidden" name="folder" value="mov"/><input type="hidden" name="next" id="upload_next" value="image_me" />';
	}
	
	$s.='	
			<input type="hidden" name="album" id="upload_album" value="'.$a.'" />
			<input type="submit" value="upload file" onClick="uploadprogress(\'\')" />
		</form>
		<div>upload status:</div><div id="uploadstatus">choose file</div>
		<iframe id="filehandler" name="filehandler" style="display:none;"></iframe>
	</div>';
	return $s;
}
function upload_dialogue_html($t,$a,$data_entry){//type (images,movies) album (link to album) data entry true or false
	//ini_get('file_uploads');
	$s='<div id="upload">
	
	 	<div>
	 		upload_max_filesize: '.ini_get("upload_max_filesize").'<br>
	 		post_max_size: '.ini_get("post_max_size").'
	 	</div>
 
 
	
		<form id="uploadform" enctype="multipart/form-data" method="POST">';
	
	if($t=='album_images') {
		if($data_entry){
			$s.='<input type="text" name="mtxDesc" id="mtxDesc" value="title"><br><input type="hidden" name="data_input" id="upload_data" value="true"/>';
		}else{
			$s.='<input type="hidden" name="data_input" id="upload_data" value="false"/>';
		}
		$s.='<input type="hidden" name="folder" id="upload_folder" value="images"/><input type="hidden" name="next" id="upload_next" value="thumb_me" />';
	}elseif($t=='album_movies'){
		if($data_entry){ 
			$s.='<input type="text" name="moviewidth" id="moviewidth" value="width"><input type="text" name="movieheight" id="movieheight" value="height"><br><input name="movieDesc" type="text" id="movieDesc" value="title"><br><input type="hidden" name="data_input" id="upload_data" value="true"/>';
		}else{
			$s.='<input type="hidden" name="data_input" id="upload_data" value="false"/>';
		}
	 	$s.='<input type="hidden" name="folder" id="upload_folder" value="mov"/><input type="hidden" name="next" id="upload_next" value="image_me" />';
	}
	
	$s.='	<input type="hidden" name="album_type" id="upload_type" value="'.$t.'"/>
			<input type="hidden" name="album" id="upload_album" value="'.$a.'" />
		</form>
		<!--<input type="file" name="upload" id="upload_file"/>-->
		<div class="menu_button_left" id="upload_file">upload</div>
		<div>upload status:</div><div id="uploadstatus">version2:choose file</div>
	</div>';
	return $s;
}
function create_thumb_html($path,$file){
$thumb_width = "80";						// Width of thumbnail image
$thumb_height = "60";						// Height of thumbnail image

	return '
		<div align="center">
			<img src="../media/images/'.$path.'/'.$file.'" style="float: left; margin-right: 10px;" id="thumbnail_display" alt="Create Thumbnail" />	
			
		  <div id="thumbnail_preview"></div>
		  
		</div>
		<br style="clear:both;"/>
		<form name="thumbnail" action="" method="GET">
			<input type="hidden" name="x1" value="" id="x1" />
			<input type="hidden" name="y1" value="" id="y1" />
			<input type="hidden" name="x2" value="" id="x2" />
			<input type="hidden" name="y2" value="" id="y2" />
			<input type="hidden" name="w" value="" id="w" />
			<input type="hidden" name="h" value="" id="h" />
			<input type="hidden" name="tpath" value="'.$path.'" id="tpath" />
			<input type="hidden" name="tfile" value="'.$file.'" id="tfile" />
			<input type="button" name="upload_thumbnail" value="save thumbnail" id="save_thumb" onclick="file_handler.save_thumbnail()"/>
		</form>';
	//return '<img src="../media/images/'.$path.'/'.$file.'"/>';
}
//-----------
function upload_element_html($nav){
	if($nav['type']=='album_images'){
		$s='<div class="menu_button_left" style="margin-top:1px;">';
		$s.='upload an image';
		$s.='</div>';
	
		/*$s='<div class="bBar" style="margin-top:1px;" onmousedown="tentacle.utilities.show_hide(\'upload_'.$nav['id'].'\')">upload an image:</div>';
		$s.='<div id="upload_'.$nav['id'].'" style="display:none;">';
		$s.='<form action="" method="GET" name="frmAlbum" id="frmAlbum" style="margin-bottom:0px;clear:left;">';
		$s.='<input type="file" name="image" size="30" /><br>';
		$s.='<input type="text" name="mtxDesc"id="mtxDesc" value="title"><br>';
		//$s.='<input type="text" name="page"id="page"><br>';
		$s.='<input type="submit" name="upload" value="Upload" /><br>';
		$s.='</form>';
		$s.='</div>';*/
		return $s;
	}
	if($nav['type']=='album_movies'){
		$s='<div class="bBar" style="margin-top:1px;" onmousedown="tentacle.utilities.show_hide(\'upload_'.$nav['id'].'\')">upload a movie:</div>';
		$s.='<div id="upload_'.$nav['id'].'" style="display:none;">';
		
		$s.='<form action="" method="GET" name="frmAlbum"  style="margin-bottom:0px;clear:left;">';
		$s.='<input name="moviefile" type="file" id="moviefile">';
		$s.='<input type="text" name="moviewidth" id="moviewidth" value="width"><input type="text" name="movieheight" id="movieheight" value="height">';
		$s.='<input name="movieDesc" type="text" id="movieDesc" value="title"><br>';
		//$s.='<input name="page" type="text" id="page"><br>';
		$s.='<input type="submit" name="upload_movie" value="Upload Movie" />';
		$s.='</form>';
		
		$s.='</div>';
		return $s;
	}
}
function open_thumb_scroll_html(){
	$s='<div class="bBar" style="clear:left;margin-top:1px;">library:</div>';
	$s.='<div style="height:200px;overflow-y: auto;">';
	return $s;
}
function close_thumb_scroll_html($nav_id){
	$s='</div>';
	$s.= '<div id="element_edit_'.$nav_id.'"></div>';//this is a place holder for the image data when clicked. neeeds id to be unique somehow
	return $s;
}
function album_thumb_html($data,$nav){
	if($nav['type']=='album_images'){
		return '<img class="im_thumb" src="../media/images/thumbnails/'.$nav['link'].'/' .$data['filename']. '" onmousedown="tentacle.database.inspect_image(\''.$nav['id'].'\',\''.$nav['link'].'\',\''.$data['filename'].'\')">';
	}
	if($nav['type']=='album_movies'){
		$file_name=substr($data['filename'], 0, - 4);//cut off the mv part
		return '<img class="im_thumb" src="../media/images/thumbnails/'.$nav['link'].'/' .$file_name. '.jpg" onmousedown="tentacle.database.inspect_movie(\''.$nav['id'].'\',\''.$nav['link'].'\',\''.$data['filename'].'\')">';
	}
}
function modify_image_html($location,$album){
	$s= '<img src="../media/images/thumbnails/'.$location.'">';

	//$s.='<div class="menu_button_left")">remove from db</div>';
	//$s.='<div class="menu_button_left" onmousedown="view_image(\''.$location.'\')">view image</div>';
	$s.='<div class="menu_button_left" onmousedown="tentacle.nodes.insert(\'db_image\',\''.$location.'\') ">add node</div>';

	return $s;
	
	/*$s='<div class="bBar" style="margin-top:1px;" onmousedown="tentacle.utilities.show_hide(\'modify_image_'.$album.'\')">modify image data:</div>';
	$s.='<div id="modify_image_'.$album.'" style="display:none;">';
	$s.='<div class="menu_button_left")">remove from db</div>';
	$s.='<div class="menu_button_left" onmousedown="view_image(\''.$location.'\')">view image</div>';
	$s.='<div class="menu_button_left")">add node</div>';
	$s.='</div>';
	return $s;*/
}
function open_imagedata_html(){
	$s='<div class="bBar" style="margin-top:1px;">image:</div>';
	return $s;
}
/*function close_imagedata_html($location){
	$s= '<img src="../media/images/thumbnails/'.$location.'">';
	$s.='<div class="bBar" onmousedown="node.insert(\'db_image\',\''.$location.'\') ">add node</div>';
	return $s;
}*/
function modify_movie_html($location,$album){
	$s= '<img src="../media/images/thumbnails/'.$location.'">';
	//$s.='<div class="menu_button_left")">remove from db</div>';
	//$s.='<div class="menu_button_left" onmousedown="view_image(\''.$location.'\')">view image</div>';
	$s.='<div class="menu_button_left" onmousedown="tentacle.nodes.insert(\'db_movie\',\''.$location.'.'.$ext.'\') ">add node</div>';
	//$s.='</div>';
	return $s;
}
function open_moviedata_html(){
	$s='<div class="bBar" style="margin-top:1px;">movie:</div>';
	return $s;
}
/*function close_moviedata_html($location,$ext){
	$s= '<img src="../media/images/thumbnails/'.$location.'.jpg">';
	$s.='<div class="bBar" onmousedown="node.insert(\'db_movie\',\''.$location.'.'.$ext.'\') ">add node</div>';
	return $s;
}*/
?>
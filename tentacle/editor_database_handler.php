<?php
//**********************************
// This file is called from javascript
// It handles the ajax call to insert the data for the selected node being inserted into the editor
// It needs the 'node' value of the the call to be an availalb node from the folder
//**********************************
require_once 'mysql_config.php';
require_once 'mysql.php';
require_once 'html.php';
require_once 'json.php';
require_once 'functions_images.php';

$act = (isset($_GET['act']) && $_GET['act'] != '') ? $_GET['act'] : 'broke';			//get the passed node variable

function inspect(){
//if($act=='inspect'){
	$id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : '';			//get the passed node variable

	$mysql=new mysql();
	$mysql->grab_nav_row($id);//grab the database table of the single navigation item
	//--------------
	
	//---------
	$s= open_navdata_html($mysql->nav_row['title'],$mysql->nav_row['id']);
	
	//$s.= open_page_button_html('pages/tentacle_navigation',$mysql->nav_row['link']);
	//$s.= remove_nav_button_html($mysql->nav_row['id']);
	//$s.=modify_nav_html($mysql->nav_row);
	
	if($mysql->nav_row['type'] != 'ind'){
		$mysql->grab_album($mysql->nav_row['link'],$mysql->nav_row['type']);//get the album data
		//$s.=upload_element_button_html($mysql->nav_row['type'],$mysql->nav_row['link']);//the upload element button
		
		$s.=open_thumb_scroll_html();
		for($i=0;$i<=sizeof($mysql->album)-1;$i++){//this builds out the thumb nail gallery
			$s.=album_thumb_html($mysql->album[$i],$mysql->nav_row);
			//$s.=$mysql->album[$i]['title'].'<br>';
		}
		$s.=close_thumb_scroll_html($mysql->nav_row['id']);
	}else{//this is an ind page so do the appropriate thing
		//this is an ind page do something else
	}	
	$s.=close_navdata_html();
	print $s;
}
function inspect_image(){
//if ($act=='inspect_image'){
	$album = (isset($_GET['album']) && $_GET['album'] != '') ? $_GET['album'] : 'broke';			//get the passed node variable
	$image = (isset($_GET['image']) && $_GET['image'] != '') ? $_GET['image'] : '';
	$image_raw=explode(".",$image);//break off the extension
	$location= $album.'/'.$image;
	
	$s= open_imagedata_html();
	$s.=modify_image_html($location,$album);
	$s.= open_page_button_html('media/pages/'.$album,$image_raw[0]);

	//$s.= close_imagedata_html($location);
	print $s;
}
function inspect_movie(){
//if ($act=='inspect_movie'){
	$album = (isset($_GET['album']) && $_GET['album'] != '') ? $_GET['album'] : 'broke';			//get the passed node variable
	$movie = (isset($_GET['movie']) && $_GET['movie'] != '') ? $_GET['movie'] : '';
	$movie_raw=explode(".",$movie);//break off the extension
	$location= $album.'/'.$movie_raw[0];
	$location_full=$album.'/'.$movie;//with the extentions in tact
	
	$s= open_moviedata_html();
	$s.=modify_movie_html($location,$album);
	$s.= open_page_button_html('media/pages/'.$album,$movie_raw[0]);
	//$s.= close_moviedata_html($location,$movie_raw[1]);
	print $s;
}
//-------------------------
function new_upload_window(){
	$atype = (isset($_GET['type']) && $_GET['type'] != '') ? $_GET['type'] : 'broke';			//get the passed node variable
	$album = (isset($_GET['album']) && $_GET['album'] != '') ? $_GET['album'] : 'broke';
	
	$mysql= new mysql();
	$id=$mysql->grab_nav_id_by_link($album);
	$row=$mysql->grab_nav_row($id);
	
	$s= open_navdata_html('upload:'.$atype.':'.$album,'upload');
	$s.=upload_dialogue_html($atype,$album,$row['type']==$atype);
	$s.=close_navdata_html();
	print $s;
}
function create_thumb_window(){
	$tpath = (isset($_GET['path']) && $_GET['path'] != '') ? $_GET['path'] : 'broke';			//get the passed node variable
	$tfile = (isset($_GET['file']) && $_GET['file'] != '') ? $_GET['file'] : 'broke';
	
	$html= open_navdata_html('create thumb:'.$tpath.':'.$tfile,'create_thumb');
	$html.=create_thumb_html($tpath,$tfile);
	$html.=close_navdata_html();
	
	$im_dime = getimagesize('../images/'.$tpath.'/'.$tfile);
	
	//using json so I can give javascript my images width and height for thumbnail creation
	$json = new Services_JSON();
	$data= array();
	$data[0]=$html;
	$data[1]=$tpath;//send the path and file too
	$data[2]=$tfile;
	$data[3]=$im_dime[0];
	$data[4]=$im_dime[1];
	
	$data_object=$json->encode($data);
	
	print($data_object);
}
function upload_thumbnail(){
	$x1 = (isset($_GET['x1']) && $_GET['x1'] != '') ? $_GET['x1'] : 'broke';			//get the passed node variable
	$y1 = (isset($_GET['y1']) && $_GET['y1'] != '') ? $_GET['y1'] : 'broke';
	$x2 = (isset($_GET['x2']) && $_GET['x2'] != '') ? $_GET['x2'] : 'broke';			//get the passed node variable
	$y2 = (isset($_GET['y2']) && $_GET['y2'] != '') ? $_GET['y2'] : 'broke';
	$w = (isset($_GET['w']) && $_GET['w'] != '') ? $_GET['w'] : 'broke';			//get the passed node variable
	$h = (isset($_GET['h']) && $_GET['h'] != '') ? $_GET['h'] : 'broke';
	$path = (isset($_GET['path']) && $_GET['path'] != '') ? $_GET['path'] : 'broke';			//get the passed node variable
	$file = (isset($_GET['file']) && $_GET['file'] != '') ? $_GET['file'] : 'broke';
	
	$scale = 80/$w;//thumbnail width, which right now is HARD coded fooooool
	$cropped = resizeThumbnailImage('../images/thumbnails/'.$path.'/'.$file, '../images/'.$path.'/'.$file,$w,$h,$x1,$y1,$scale);
	print $cropped;
}
//-------------------------
/*this shit is obsolete
function new_nav_window(){//this just returns a window for the new page window thing
	$s= open_navdata_html('new_page','add_db');
	$s.=new_db_entry_html();
	$s.=close_navdata_html();
	print $s;
}*/
function new_nav(){//this send the command to make a new table
	$title = (isset($_GET['title']) && $_GET['title'] != '') ? $_GET['title'] : 'broke';			//get the passed node variable
	$ptype = (isset($_GET['type']) && $_GET['type'] != '') ? $_GET['type'] : 'broke';
	$visib = (isset($_GET['vis']) && $_GET['vis'] != '') ? $_GET['vis'] : '';
	$tlink = clean($title);//send it to the cleaner 
	
	$mysql=new mysql();
	$creation = $mysql->create_nav($title,$ptype,$tlink,$visib);//makes the entry
	
	$nav_id=$mysql->grab_navigation_id($title);
	$nav_data=array('id'=>$nav_id,'title'=>$title,'type'=>$ptype,'link'=>$tlink,'visibility'=>$visib);
	
	if($creation){
		//---we also neeed to make the folders to store the shit if it is a new album
		if($ptype=='album_movies') {
			create_directory('../media/pages/'.$tlink);
			create_directory('../media/mov/'.$tlink);
			create_directory('../media/images/'.$tlink);
			create_directory('../media/images/thumbnails/'.$tlink);
			//$s=database_menu_button_am( $title,$mysql->grab_navigation_id($title) );
		}
		if($ptype=='album_images') {
			create_directory('../media/pages/'.$tlink);
			create_directory('../media/images/'.$tlink);
			create_directory('../media/images/thumbnails/'.$tlink);
			//$s=database_menu_button_ai( $title,$mysql->grab_navigation_id($title) );
		}
		//if($ptype=='ind') {
			//$s=database_menu_button_in( $title,$mysql->grab_navigation_id($title) );
		//}
		$s=database_menu_buttons($nav_data);
	}
	print $s;	
}
function remove_nav(){
	$id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : 0;			//get the passed node variable
	$mysql=new mysql();
	//i also need to remove the folders if there are folders and all thier contents
	$mysql->grab_nav_row($id);
	$navpage = '../media/pages/navigation/'.$mysql->nav_row['link'].'.xml';

	if($mysql->nav_row['type']=='album_movies'){
		$filedir = '../media/mov/'.$mysql->nav_row['link']; 
		$pagedir = '../media/pages/'.$mysql->nav_row['link'];
		flush_folder($pagedir);
		flush_folder($filedir);
	}
	if($mysql->nav_row['type']=='album_images'){
		$filedir = '../media/images/'.$mysql->nav_row['link']; 
		$pagedir = '../media/pages/'.$mysql->nav_row['link'];
		flush_folder($pagedir);
		flush_folder($filedir);
	}
	//if($mysql->nav_row['type']=='ind'){
	
	//}
	if(is_file($navpage)) unlink($navpage);
	$mysql->delete_nav($id);

	print $id;//send back the number so i can remove the button and the property window	
}
//------
function create_directory($dir){//make a directory
	if(!is_dir($dir)){
		mkdir($dir, 0777);
		chmod($dir, 0777);
	}
}
function flush_folder($folder){//remove all the folder and all
	if(is_dir($folder)){
		$d = dir($folder); 
		while($entry = $d->read()) { 
			 if ($entry!= "." && $entry!= "..") { 
			 	unlink($entry); 
			 } 
		} 
		$d->close(); 
		rmdir($folder); 
	}
}
function clean($str){//make the string tentacle ready, no spaces
	$break = explode(' ',$str);
	$mend='';
	for($i=0;$i<=count($break)-1;$i++){
		if($i>0) $mend.='_';
		$mend.=$break[$i];
	}
	return strtolower($mend);
}
//------------------------------
function broke(){
	print 'broke';
}
//---------------------------
$act();//run the thing mo

?>
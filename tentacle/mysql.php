<?php
require_once 'path.php';//this is so I know where I am when lookin for config
class mysql{
	var $navigation = array();//array to hold all the nav table
	var $nav_row = array();//array to hold single nav row at a time
	var $album=array();
	var $media=array();
	var $user=array();

	function mysql(){
		include_once(tentacle_root.'mysql_config.php');
	}
	//-------------------------------------
	//-------------------------------------
	//		get from database
	//-------------------------------------
	//-------------------------------------
	function grab_navigation(){
		//the part where it checks to see if a tentalce main tables have been set up
		//doesn't work remotely. probably something to do with the versions.
		
		if($this->table_exists('tentacle_navigation')){
			$count=0;
			$nav_array=array();
			$rawNav =  mysql_query("SELECT * FROM tentacle_navigation ORDER BY id ASC") or 'navigation unavailable:'. mysql_error();
			while($info = mysql_fetch_array( $rawNav )){
				$nav_array[$count] = array( 'id'=>$info['id'] , 
											'title'=>$info['title'], 
											'type'=>$info['type'], 
											'link'=>$info['link'], 
											'visibility'=>$info['visibility']);
				$count+=1;
			}
			$this->navigation = $nav_array;
			return $this->navigation;
		}else{//navigation table is not there, do this:
			$this->create_tentacle_navigation_table();
			//$this->create_tentacle_settings_table();
			$this->grab_navigation();
		}
	}
	function grab_navigation_id($title){
		$r= mysql_query("SELECT id FROM tentacle_navigation WHERE title='$title'") or 'navigation unavailable:'. mysql_error();
		$a=	mysql_fetch_array( $r );
		return $a['id'];	
	}
	function grab_nav_id_by_link($link){
		$r= mysql_query("SELECT id FROM tentacle_navigation WHERE link='$link'") or 'navigation unavailable:'. mysql_error();
		$a=	mysql_fetch_array( $r );
		return $a['id'];	
	}
	function grab_nav_row($id){
		//----------------------
		//  All this if else stuff is because I come to this function from the url of loading pages, and I want to allow getting to pages with text as well as numbers
		//----------------------
		if(is_numeric($id)){
			$rawNav =  mysql_query("SELECT * FROM tentacle_navigation WHERE id='$id'") or 'navigation unavailable:'. mysql_error();
		}else{
			$spaces=explode(" ",$id);
			if($spaces[1]){
				$rawNav =  mysql_query("SELECT * FROM tentacle_navigation WHERE title='$id'") or 'navigation unavailable:'. mysql_error();
			}else{
				$rawNav =  mysql_query("SELECT * FROM tentacle_navigation WHERE link='$id'") or 'navigation unavailable:'. mysql_error();
			}
		}
		//----------------------
		while($info = mysql_fetch_array( $rawNav )){
			$nav_array=array('id'=>$info['id'] , 
						'title'=>$info['title'], 
						'type'=>$info['type'], 
						'link'=>$info['link'], 
						'visibility'=>$info['visibility']);
		}
		$this->nav_row = $nav_array;
		return $nav_array;
		//$this->nav_row = array('id'=>99);
	}
	//--------------------------
	function previous_row($table,$id){
		$n=array();
		$d = mysql_query("SELECT * FROM $table WHERE id < $id ORDER BY id DESC LIMIT 1") or 'navigation unavailable:'. mysql_error();
		while($info = mysql_fetch_array( $d )){
			$n=array('id'=>$info['id']);
		}
		return $n;
	}
	function next_row($table,$id){
		$n=array();
		$d = mysql_query("SELECT * FROM $table WHERE id > $id ORDER BY id ASC LIMIT 1") or 'navigation unavailable:'. mysql_error();
		while($info = mysql_fetch_array( $d )){
			$n=array('id'=>$info['id']);
		}
		return $n;
	}
	//--------------------------
	function grab_album($link,$type,$begin=0,$limit=0){
		$count=0;
		$album_array=array();
		if($limit>0){//if we are sending and aoffset amount, we're using pagin, and thus grab certain amount
			$rawAlbum = mysql_query("SELECT * FROM ".$link." ORDER BY id DESC LIMIT $begin, $limit") or die('grab album with limit didnt work' . mysql_error());//get info from album table
		}else{
			$rawAlbum = mysql_query("SELECT * FROM ".$link." ORDER BY id DESC") or die('not sure' . mysql_error());//get info from album table
		}
		while($info = mysql_fetch_array( $rawAlbum )){
			if($type=='album_images'){
				$album_array[$count]=array('id'=>$info['id'] , 
						'filename'=>$info['filename'], 
						'title'=>$info['title'] );
			}
			if($type=='album_movies'){
					$album_array[$count]=array('id'=>$info['id'] , 
						'filename'=>$info['filename'], 
						'title'=>$info['title'],
						'width'=>$info['width'] , 
						'height'=>$info['height'],
						'page'=>$info['page']);
			}
			$count++;
		}
		$this->album=$album_array;
	}
	//-----get album row
	function grab_album_row($album,$row_id){
		$raw_media=mysql_query("SELECT * FROM $album WHERE id = $row_id")or die('didnt grab from album table right');
		$info = mysql_fetch_array( $raw_media );
		$this->media=$info;
		return $info;
	}
	//------
	//count for albums
	function count_rows($table){
		$query = "SELECT COUNT(id) AS numrows FROM $table";
		$result = mysql_query($query) or die('count failed');
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$numrows = $row['numrows'];
		return $numrows;
	}
	//-------------------------------------
	//      check if table exists
	//-------------------------------------
	//http://www.electrictoolbox.com/check-if-mysql-table-exists/php-function/
	function table_exists($table){
		$exists=0;
		$result = mysql_query("SHOW TABLES LIKE '$table'") or die ('error reading database while looking for a specific table');
		if (mysql_num_rows ($result)>0)$exists=1;
		
		/*$tables=mysql_list_tables(dbname);
		$num_tables=mysql_numrows($tables); 
		$i=0;
		$exists=0;
		while($i<$num_tables){
			$tablename=mysql_tablename($tables,$i);
			if($tablename==$table)$exists=1;
			$i++;
		}*/
		
		return $exists;
	}
	//-----------
	function create_directory($dir){//make a directory
			if(!is_dir($dir)){
				mkdir($dir, 0777);
				chmod($dir, 0777);
			}
		}
	function create_tentacle_navigation_table(){//this is the first time we are setting up the tales for tentacle. also set up folders
		//basically, these will only be run the first time we make a nav
		$this->create_directory(page_root.'media/pages');
		$this->create_directory(page_root.'media/pages/tentacle_navigation');
		$this->create_directory(page_root.'media/saves');
		$this->create_directory(page_root.'media/mov');
		$this->create_directory(page_root.'media/images');
		$this->create_directory(page_root.'media/images/thumbnails');
		
		mysql_query("CREATE TABLE tentacle_navigation(
			id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			title VARCHAR(36) NOT NULL,
			type VARCHAR(36) NOT NULL,
			link VARCHAR(36) NOT NULL,
			visibility TINYINT(1) NOT NULL
			)")or die (mysql_error().' i am here, trying to make a table for navigation');
		$this->create_nav('Latest','ind','latest',true);
	}
	function create_tentacle_settings_table(){
		mysql_query("CREATE TABLE tentacle_settings(
			id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			key VARCHAR(36) NOT NULL,
			value VARCHAR(36) NOT NULL,
			)")or die (mysql_error());
	}
	function create_tentacle_users_table(){
		mysql_query("CREATE TABLE tentacle_users(
			id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			user VARCHAR(36) NOT NULL,
			password VARCHAR(44) NOT NULL,
			permission TINYINT(1) NOT NULL
			)")or die (mysql_error());
		//$this->create_user('admin','password','1');
	}
	function create_user($user,$password,$permission){
		if(!$this->table_exists('tentacle_users')) $this->create_tentacle_users_table();//create the table if it ain't already there
		//I need to make sure that there isn't already a user with the same name. so that it isn't input twice
		$passwordhashed = sha1($password);
		$query = "INSERT INTO tentacle_users (user, password, permission) 
				  VALUES ('$user', '$passwordhashed', '$permission')";
	
		mysql_query($query) or die('Error, creating user ' . mysql_error());    
	}
	function get_user_password($user){
		$all_users = mysql_query("SELECT * FROM tentacle_users ORDER BY id DESC") or die( mysql_error());//get info from album table
		
		while($au = mysql_fetch_array( $all_users )){
			if($au['user']==$user) {//this user does indeed exists
				return $au['password'] ;
			}else{
				return 'denied';//no user	
			}
		}
	}
	//-------------------------------------
	//-------------------------------------
	//		set in database
	//-------------------------------------
	//-------------------------------------
	function create_nav($title,$type,$link,$visibility){
		/*if($visibility=="true"){
			$visibility=1;
		}else{
			$visibility=0;
		}*/
		if($type == "album_images"){//------------if making a album nav item, create a table
			mysql_query("CREATE TABLE $link(
				id INT NOT NULL AUTO_INCREMENT, 
				PRIMARY KEY(id),
				filename VARCHAR(99) NOT NULL, 
				title VARCHAR(99) NOT NULL,
				page TEXT NOT NULL)")
			or die(mysql_error());
		}else{
			if($type == "album_movies"){
				mysql_query("CREATE TABLE $link(
					id INT NOT NULL AUTO_INCREMENT, 
					PRIMARY KEY(id),
					filename VARCHAR(99) NOT NULL, 
					title VARCHAR(99) NOT NULL,
					width INT(11) NOT NULL,
					height INT(11) NOT NULL,
					page TEXT NOT NULL)")
				or die(mysql_error());
			}
		}
		
		if (!get_magic_quotes_gpc()) {
			$title  = addslashes($title);
			$type  = addslashes($type);
			$link  = addslashes($link);
		}  
		
		
		$query = "INSERT INTO tentacle_navigation (title, type, link, visibility) 
				  VALUES ('$title', '$type', '$link', '$visibility')";
	
		mysql_query($query) or die('Error, add navigation failed : ' . mysql_error());                    
	
		// the album is saved, go to the album list 
		return true;
	}
	//-------------------------------------
	//-------------------------------------
	//		remove from database
	//-------------------------------------
	//-------------------------------------
	function delete_nav($id){
		$this->grab_nav_row($id);
		if($this->nav_row['type']!='ind'){//if this is an album
			$ar=mysql_query("DROP TABLE ".$this->nav_row['link']."")or die("issues with table".mysql_error());
		}
		$r=mysql_query("DELETE FROM tentacle_navigation WHERE  id = '$id'") or die('Delete album failed. ' . mysql_error());
		
	}
	//---
}
?>
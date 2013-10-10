<?php
require_once 'mysql.php';

function update_db_album_table(){
	if($_POST['data_input']=="true"){//if this exists I need to put the data in yo
		
		$album_type=$_POST['album_type'];
		$album=$_POST['album'];
		//$file_link = basename($_FILES['upload']['name']);
		$file_link = basename($_POST['filename']);
		//$file_link=$HTTP_POST_FILES['upload']['name'];
		if (!get_magic_quotes_gpc()) $file_link  = addslashes($file_link);
			
		if($album_type=='album_images'){
			$desc=$_POST['mtxDesc']; 
			if (!get_magic_quotes_gpc()) $desc  = addslashes($desc); 
			$query = "INSERT INTO $album (filename, title) 
					  VALUES ('$file_link', '$desc')";
		}
		
		if($album_type=='album_movies'){
			$m_width=$_POST['moviewidth'];
			$m_height=$_POST['movieheight'];
			$desc=$_POST['movieDesc'];  
			if (!get_magic_quotes_gpc()) $desc  = addslashes($desc); 
			$query = "INSERT INTO $album (filename, title, width, height) 
					  VALUES ('$file_link', '$desc', '$m_width', '$m_height')";
		}
		
		$mysql=new mysql();
		mysql_query($query) or die('Error, add image failed : ' . mysql_error());
		
	}
}

?>
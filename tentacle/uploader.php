<?php
require_once 'upload_update_db.php';

$path = '../'.$_POST['folder'].'/'.$_POST['album'].'/';

$html_output = '';

/*if(is_uploaded_file($HTTP_POST_FILES['upload']['tmp_name'])){
	if(move_uploaded_file($HTTP_POST_FILES['upload']['tmp_name'],$path.$HTTP_POST_FILES['upload']['name'])){
		$html_output .= 'Upload finished';
		//--------------do mysql jig here
		//update_db_album_table();
		//----------------
	}else{
		$html_output .= 'Upload failed';
	}
}*/

if(is_uploaded_file($_FILES['userfile']['tmp_name']))
{
	if(move_uploaded_file($_FILES['userfile']['tmp_name'],$path.$_FILES['userfile']['name']))
	{
		$html_output .= 'Upload sucessful:';
		echo $html_output.$path.$_FILES['userfile']['name'];
		//--------------do mysql jig here
		update_db_album_table();
		//----------------
	}else{
		$html_output .= 'Upload shit the bed';
	}
}

?>
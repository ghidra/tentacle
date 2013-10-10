<?php
include_once 'path.php';
include_once 'html.php';
class xml_file_browser{
	var $file_list=array();
	function xml_file_browser(){
		//$this->file_list['saves']=$this->browse_dir('saves');
		$this->file_list['media/pages']=$this->browse_dir('media/pages');
		//$this->file_list['compounds']=$this->browse_dir('tentacle/compounds');
		//print_r($this->file_list);
	}
	//----------the recursive magic
	//grab the files and folders, return array of them all.
	function browse_dir($folder){
		$files=array();//array to hold the files
		$dir = page_root.$folder.'/';																	//the directory where all the nodes are held
		$dh = opendir($dir);
		$i=0;
		while (false !== ($filename = readdir($dh)) ){
			if(substr($filename,0,1)!='.'){
				if(!is_dir($dir.$filename)){	//this is a file
					$filename_part=explode(".",$filename);//break off the dile name minus the file extension
					$files[$i]=$filename_part[0];
				}else{ //this is another dir
					$files[$filename]=$this->browse_dir($folder.'/'.$filename);//we are gonna recursively grab  all the files in folders and put them in the array too
				}
				$i++;
			}
		}
		return $files;//return the array of files
	} 
	//-----------------
	//-----------------
	//-----------------Browse to open
	function browse_to_open($files,$path='',$i=1){
		if($i) $s=open_browse_window_html();//from the html.php
		//$s='';
		while(list($key,$val) = each($files)) {						//for each of the values in this node, seperate into key and val
			
			if(!is_array($val)){//if it is NOT an array, it is a file, make it go
				$s.=browse_file($val,$path);//from html.php
			}else{//this is another folder, put it under a header:
				//trying to send the path correctly for recursion and it add on.
				if(!$this->file_list[$key]){//if there is not a main array value at this index
					$new_path=$path.'/'.$key;
				}else{
					$new_path=$key;	
				}
				//make the menu of folders
				if($key=='media/pages' ){
					$s.=open_browse_menu_fold($key,false);
				}else{
					$s.=open_browse_menu_fold($key);
				}
				//recurse
				$s.=$this->browse_to_open($val,$new_path,0);
				//$path=$key;//rest the path
				//finish
				$s.=close_browse_menu_fold();
			}
		}		
		if($i) $s.=close_browse_window_html();//from the html.php
		return $s;
	}
	//-----------------
	//-----------------
	//-----------------Browse to read, render
	function browse_to_read($files,$path='',$with_header=1){
		$s='';
		while(list($key,$val) = each($files)) {						//for each of the values in this node, seperate into key and val
			
			if(!is_array($val)){//if it is NOT an array, it is a file, make it go
				$s.= '<a href="'.$_SERVER['PHP_SELF'].'?page='.$path.'/'.$val;
				if(!$with_header) $s.='&header=0';
				$s.='">'.$val.'</a><br>';
				
				//$s.=browse_file($val,$path);//from html.php
			}else{//this is another folder, put it under a header:
				//trying to send the path correctly for recursion and it add on.
				if(!$this->file_list[$key]){//if there is not a main array value at this index
					$new_path=$path.'/'.$key;
				}else{
					$new_path=$key;	
				}
				//make the menu of folders
				//recurse
				$s.=$this->browse_to_read($val,$new_path,$with_header);
			}
		}		
		return $s;
	}
}
?>
<?php
//everything loaded into the index page most likely comes through this since this node is plugged in in the index.xml file

require_once node_root.'node.php';
require_once tentacle_root.'mysql.php';
require_once tentacle_root.'render_handler.php';

class tentacle_page extends node{
	var $type='tentacle_page';
	var $number_ports=0;
	//function __construct(){
	//}
	function render($data,$nodes){	
		$page_id=(isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : false;
		$file_id=(isset($_GET['f']) && $_GET['f'] != '') ? $_GET['f'] : false;			//get the passed node variable
		//----there are only used for general media
		$path_id = (isset($_GET['pa']) && $_GET['pa'] != '') ? $_GET['pa'] : '';			//get the path
		$dir_id = (isset($_GET['dir']) && $_GET['dir'] != '') ? $_GET['dir'] : '';
		
		$render=new render_handler();
		$mysql=new mysql();
		//----get the nav info from the id sent in
		if($page_id){
			$mysql->grab_nav_row($page_id);
			$link=$mysql->nav_row['link'];//get the link of the page
			$type=$mysql->nav_row['type'];
		}else{
			$link='latest';
			$type='ind';
		}
		//file data
		if($file_id){//render the media node, or media xml
			if(is_numeric($file_id)){
				if($page_id){//if we know for sure what album it is from
					$mysql_media=new mysql();
					$mysql_media->grab_album_row($mysql->nav_row['link'],$file_id);
					$file_parts=explode('.',$mysql_media->media['filename']);//break the filename up
					if(file_exists('media/pages/'.$mysql->nav_row['link'].'/'.$file_parts[0].'.xml')){
						$render->set_file('media/pages/'.$mysql->nav_row['link'].'/'.$file_parts[0]);
						$render->execute($render->result);//send the execute node, to complie the code (This is in the render magic)
						$s=$render->get_output($render->result[0]);
					}else{
						//fake a node for media
						include_once node_root.'tentacle_media.php';			//now make sure the node code is avilable., and create the means to make the code 
						$album= new tentacle_media();							//make an object to build the data
						$album_result=$album->render(array('index'=>0,'class'=>'image_page','pclass'=>'nav_paging_but'),array());//here i am faking a node
						$s=$album_result['result'];
					}
				}else{
					//not gonna be able to find it, there will probaly be multiple from all albums	
				}
			}else{
				//this is probably an image from the general tentacle gallery...
				//grey code here is waiting for the ability to create pages for each fole too
				//$s='<div style="color:red">this is my general gallery business</div>';
				/*$file_parts=explode('.',$file_id);
				if(file_exists('media/pages'.$dir_id.'/'.$file_parts[0].'.xml')){
					$render->set_file('media/pages'.$dir_id.'/'.$file_parts[0]);
					$render->execute($render->result);//send the execute node, to complie the code (This is in the render magic)
					$s=$render->get_output($render->result[0]);
				}else{*/
					//fake a node for media
					include_once node_root.'tentacle_general_media.php';			//now make sure the node code is avilable., and create the means to make the code 
					$album= new tentacle_general_media();							//make an object to build the data
					$album_result=$album->render(array('index'=>0,'class'=>'image_page','pclass'=>'nav_paging_but'),array());//here i am faking a node
					$s=$album_result['result'];
				//}
			}
		}else{//render the page node, or the page xml
			if(file_exists('media/pages/tentacle_navigation/'.$link.'.xml')){
				$render->set_file('media/pages/tentacle_navigation/'.$link);
				$render->execute($render->result);//send the execute node, to complie the code (This is in the render magic)
				$s=$render->get_output($render->result[0]);
			}else{
				if($type!='ind'){//this is an album
					//now we are gonna fake a simple single node
					include_once node_root.'tentacle_album.php';			//now make sure the node code is avilable., and create the means to make the code 
					$album= new tentacle_album();							//make an object to build the data
					$album_result=$album->render(array('index'=>0,'class'=>'image_album','pclass'=>'nav_paging','bclass'=>'nav_paging_but','sclass'=>'nav_paging_sel'),array());//here i am faking a node
					$s.=$album_result['result'];
				}	
			}
		}	
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
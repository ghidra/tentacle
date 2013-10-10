<?php
require_once node_root.'node.php';
require_once tentacle_root.'html.php';
require_once tentacle_root.'mysql.php';

class tentacle_album extends node{
	var $type='tentacle_album';
	
	var $perpage=25;//stack height of elements
	
	var $id='';
	var $class='image_album';//doesn't come with brute force from tentacle_page, mst be sent in fake array
	var $style='';
	var $title='';
	
	var $pid='';
	var $pclass='nav_paging';//doesn't come with brute force from tentacle_page, mst be sent in fake array
	var $pstyle='';
	var $ptitle='';
	
	var $bid='';
	var $bclass='nav_paging_but';//doesn't come with brute force from tentacle_page, mst be sent in fake array
	var $bstyle='';
	var $btitle='';
	
	var $sid='';
	var $sclass='nav_paging_sel';//doesn't come with brute force from tentacle_page, mst be sent in fake array
	var $sstyle='';
	var $stitle='';

	var $number_ports=0;

	var $tentacle_album_ports=array('tentacle album attributes'=>array(
			'perpage'=>array('type'=>'integer','exposed'=>0)
		));

	var $tentacle_album_container_ports=array('tentacle album container attributes'=>array(
			'id'=>array('type'=>'string','exposed'=>1),
			'class'=>array('type'=>'string','exposed'=>1),
			'style'=>array('type'=>'string','exposed'=>1),
			'title'=>array('type'=>'string','exposed'=>1)
		));
	
	var $tentacle_album_paging_ports=array('paging attributes'=>array(
			'pid'=>array('type'=>'string','exposed'=>1),
			'pclass'=>array('type'=>'string','exposed'=>1),
			'pstyle'=>array('type'=>'string','exposed'=>1),
			'ptitle'=>array('type'=>'string','exposed'=>1)
		));
	var $tentacle_album_paging_buttons_ports=array('paging button attributes'=>array(
			'id'=>array('type'=>'string','exposed'=>1),
			'class'=>array('type'=>'string','exposed'=>1),
			'style'=>array('type'=>'string','exposed'=>1),
			'title'=>array('type'=>'string','exposed'=>1)
		));
	var $tentacle_album_paging_selected_ports=array('paging selected attributes'=>array(
			'id'=>array('type'=>'string','exposed'=>1),
			'class'=>array('type'=>'string','exposed'=>1),
			'style'=>array('type'=>'string','exposed'=>1),
			'title'=>array('type'=>'string','exposed'=>1)
		));

	function __construct(){
		$this->append('tentacle_album_ports');
		$this->append('tentacle_album_container_ports');
		$this->append('tentacle_album_paging_buttons_ports');
		$this->append('tentacle_album_paging_selected_ports');
	}
	function render($data,$nodes){	
		$page_id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : 'default';			//get the passed node variable
		$per_page = (isset($_GET['pp']) && $_GET['pp'] != '') ? $_GET['pp'] : $data['perpage'];
		$page_num = (isset($_GET['p']) && $_GET['p'] != '') ? $_GET['p'] : 1;
		
		if(!$per_page) $per_page=$this->perpage;//if we still don't have a per page number
		
		$mysql=new mysql();
		//----get the nav info from the id sent in
		if($page_id!='default'){
			$mysql->grab_nav_row($page_id);
			$link=$mysql->nav_row['link'];//get the link of the page
			$type=$mysql->nav_row['type'];
		}
		if($page_id=='default'){//do default behavior
			$link='latest';
			$type='ind';
			$page_id=$mysql['id'];//set the id for the links to work
		}
		if($type!='ind'){//just incase, should never come in though
			//----paging numbers
			$offset = ($page_num - 1) * $per_page;//get page offset
			$num_rows = $mysql->count_rows($link);
			$max_pages = ceil($num_rows/$per_page);
			//----grab nav
			$mysql->grab_album($link,$type,$offset,$per_page);
			//----start string
			$s='';
			//----iterate
			$album_number=count($mysql->album)-1;//number of nav items
			for($i=0;$i<=$album_number;$i++){
				//send along the file number and the nav id number
				$s.= '<a href="' . $_SERVER['PHP_SELF'] . '?id='.$page_id.'&f='.$mysql->album[$i]['id'].'"><img'.$this->get_base_attributes($data,$nodes).' src="media/images/thumbnails/'.$link.'/';
				if($type=='album_images'){
					$s.= $mysql->album[$i]['filename'];
				}elseif($type=='album_movies'){
					$movie_parsed=substr($mysql->album[$i]['filename'], 0, - 4);//cut off the mv part
					$s.= $movie_parsed.'.jpg';
				}
				$s.='" alt="' . $mysql->album[$i]['title'] . '" title="' . $mysql->album[$i]['title'] . '"></a>';
			}
			//-----paging junk
			$s.= '<div'.$this->get_base_attributes_p($data,$nodes).'>';
			
			$at_b=$this->get_base_attributes_b($data,$nodes);
			$at_s=$this->get_base_attributes_s($data,$nodes);
			
			if($page_num>1){//if we are past the first page make a previous page button
				$page = $page_num -1;
				$s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id . '&p=' . $page .'><div'.$at_b.'>&laquo;</div></a>';
			}else{
				$s.= '<div'.$at_b.'>&nbsp;</div>';
			}
			//echo 'Page ' . $pageNum . ' of ' . $maxPage . ' ';//show pages
			if($max_pages>1){//if there is more than one page
				for($page = 1; $page <= $max_pages; $page++){
					if($page == $page_num){
						$s.= '<div'.$at_s.'>' . $page. '</div>';
					}else{
						$s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id . '&p=' . $page .'><div'.$at_b.'>'.$page.'</div></a>';
					}
				}
			}
			if($page_num < $max_pages){//as long as we are not on last page yet
				$page = $page_num + 1;
				$s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id . '&p=' . $page .'><div'.$at_b.'>&raquo;</div></a>';
			}else{
				$s.= '<div'.$at_b.'>&nbsp;</div>';
			}
			
			$s.= '</div>';
			//---end paging junk
		}
		//----begin string
		//$s='';
		//----render age based on type
		//$render->set_file('pages/navigation/'.$link);
		//$render->execute($render->result);//send the execute node, to complie the code (This is in the render magic)
		//$s=$render->get_output($render->result[0]);
			
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
	function get_attribute_assembled($data,$nodes,$attr,$drop=false){
		//global $nodes;// this variable comes from the render.php execute function
		$s='';
		if (strlen($data[$attr])>0) {
			if($drop){
				$s.=' '.substr($attr, 1).'="';//this subtrings removes the first character ceom pclass, pid etc
			}else{
				$s.=' '.$attr.'="';
			}
			if(is_string($data[$attr])){
				$s.=$data[$attr].'"';
			}else{
				$s.=$nodes[$data[$attr]['index']][$data['port_'.$attr]].'"';	
			}
		}
		return $s;
	}
	function get_base_attributes($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		$s='';//hold the tag data that is pligged in i guess
		
		$s.=$this->get_attribute_assembled($data,$nodes,'id',0);
		$s.=$this->get_attribute_assembled($data,$nodes,'title',0);
		$s.=$this->get_attribute_assembled($data,$nodes,'class',0);
		$s.=$this->get_attribute_assembled($data,$nodes,'style',0);
		
		return $s;
	}
	function get_base_attributes_p($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		$s='';//hold the tag data that is pligged in i guess
		
		$s.=$this->get_attribute_assembled($data,$nodes,'pid',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'ptitle',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'pclass',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'pstyle',1);
		
		return $s;
	}
	function get_base_attributes_b($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		$s='';//hold the tag data that is pligged in i guess
		
		$s.=$this->get_attribute_assembled($data,$nodes,'bid',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'btitle',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'bclass',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'bstyle',1);
		
		return $s;
	}
	function get_base_attributes_s($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		$s='';//hold the tag data that is pligged in i guess
		
		$s.=$this->get_attribute_assembled($data,$nodes,'sid',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'stitle',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'sclass',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'sstyle',1);
		
		return $s;
	}
}
?>
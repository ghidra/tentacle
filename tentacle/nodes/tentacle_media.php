<?php
//require_once './html.php';
require_once tentacle_root.'html.php';
require_once tentacle_root.'mysql.php';

class tentacle_media{
	var $type='tentacle_media';
	var $result='';
	var $mode=0;
	var $index=0;
	var $left=0;
	var $top=0;
		
	var $id = '';
	var $class = 'image_page';//this doesn't come through when brite forced from tentacle_page. So I fake it there too
	var $style = '';
	var $title = '';
	
	var $pid = '';
	var $pclass = 'nav_paging_but';//doesn't come with brute force from tentacle_page, mst be sent in fake array
	var $pstyle = '';
	var $ptitle = '';
	
	function tentacle_media(){
	}
	function assemble_node(){	
		$s= node_header($this->index,$this->type,$this->mode);
		$s.=node_output('result',$this->result,$this->index);
		
		$s.=node_input('id',$this->id,$this->index);
		$s.=node_input('class',$this->class,$this->index);
		$s.=node_input('style',$this->style,$this->index);
		$s.=node_input('title',$this->title,$this->index);
		
		$s.=node_input('pid',$this->pid,$this->index);
		$s.=node_input('pclass',$this->pclass,$this->index);
		$s.=node_input('pstyle',$this->pstyle,$this->index);
		$s.=node_input('ptitle',$this->ptitle,$this->index);
		
		return $s;
	}
	function assign_values($values){
		$this->result='';
		$this->index=$values['index'];
		$this->mode=$values['mode'];
		$this->type=$values['type'];
		$this->left=$values['left'];
		$this->top=$values['top'];
	
		$this->id=$values['id'];
		$this->class=$values['class'];
		$this->style=$values['style'];
		$this->title=$values['title'];
		
		$this->pid=$values['pid'];
		$this->pclass=$values['pclass'];
		$this->pstyle=$values['pstyle'];
		$this->ptitle=$values['ptitle'];
	}
	function render($data,$nodes){	
		$page_id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : false;			//get the passed node variable
		$file_id=(isset($_GET['f']) && $_GET['f'] != '') ? $_GET['f'] : false;			//get the passed node variable
		//----get the nav info from the id sent in		
		if($page_id && $file_id){
			$mysql=new mysql();
			$mysql_media=new mysql();
			
			$mysql->grab_nav_row($page_id);
			$mysql_media->grab_album_row($mysql->nav_row['link'],$file_id);
			
			if($mysql->nav_row['type']=='album_images'){
				$root_link='media/images/'.$mysql->nav_row['link'].'/'.$mysql_media->media['filename'];
				$s='<img'.$this->get_base_attributes($data,$nodes).' src="'.$root_link.'">';
			}elseif($mysql->nav_row['type']=='album_movies'){
				$root_link='media/mov/'.$mysql->nav_row['link'].'/'.$mysql_media->media['filename'];
				$w=$mysql_media->media['width'];
				$h=$mysql_media->media['height']+16;
				$s.='<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width='.$w.' height='.$h.' >';
				$s.='<param name="src" value='.$root_link.' >';
				$s.='<param name="autoplay" value="false"><param name="controller" value="true">';
				$s.='<embed src='.$root_link.' width='.$w.' height='.$h.' autoplay="false" controller="true" border="0" pluginspage="http://www.apple.com/quicktime/download/""></embed>  		';
				$s.='</object>';
			}
			
			$s.='<br>';
			
			//$nav_mysql=new mysql();
			$at = $this->get_base_attributes_p($data,$nodes);
			$pre = $mysql->previous_row($mysql->nav_row['link'],$mysql_media->media['id']);
			$nxt = $mysql->next_row($mysql->nav_row['link'],$mysql_media->media['id']);
			
			if($nxt) $s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id . '&f=' . $nxt['id'] .'><div'.$at.'>&laquo;</div></a>';
			$s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id .'><div'.$at.'>^</div></a>';
			if($pre) $s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id . '&f=' . $pre['id'] .'><div'.$at.'>&raquo;</div></a>';
			//$s.= '<a href=' . $_SERVER['PHP_SELF'] . '><div'.$at.'>home</div></a>';
		}
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
	function inspect($data){
		$data= $this->translate($data);//translate the data into an array that can be more easily used
		$s= property_header($this->index,$this->type);

		$s.=open_prop_attr_html($this->index);//hidden
		if($data['id']!='_in') $s.=property_text_input('id',$data['id']);
		if($data['class']!='_in') $s.=property_text_input('class',$data['class']);
		if($data['style']!='_in') $s.=property_text_input('style',$data['style']);
		if($data['title']!='_in') $s.=property_text_input('title',$data['title']);
		$s.=close_prop_attr_html();//from the html.php
		
		$s.=open_prop_attr_html($this->index.'_paging_group');//hidden
		if($data['pid']!='_in') $s.=property_text_input('pid',$data['pid']);
		if($data['pclass']!='_in') $s.=property_text_input('pclass',$data['pclass']);
		if($data['pstyle']!='_in') $s.=property_text_input('pstyle',$data['pstyle']);
		if($data['ptitle']!='_in') $s.=property_text_input('ptitle',$data['ptitle']);
		$s.=close_prop_attr_html();//from the html.php

		return $s;
	}
	function open_node(){
		return open_node_html($this->index,$this->left,$this->top,$this->type);
	}
	function close_node(){
		return close_node_html();
	}
	//--------
	function translate($data){
		$a=array();
		$d=explode(',',$data);
		for ($i=0;$i<=count($d)-1;$i++){
			$d_s=explode(":",$d[$i]);
			//-----------fix the bug with the : split
			$all_data='';
			for($j=1;$j<=sizeof($d_s)-1;$j++){
				$all_data.=$d_s[$j];
				if($j!=sizeof($d_s)-1){
					$all_data.=':';
				}
			}
			//-----------
			$a[$d_s[0]]=$all_data;//set the associate array values for each thing
		}
		return $a;//now return the final array
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
		$s='';//hold the tag data that is pligged in i guess
		
		$s.=$this->get_attribute_assembled($data,$nodes,'pid',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'ptitle',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'pclass',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'pstyle',1);
		
		return $s;
	}
}
?>
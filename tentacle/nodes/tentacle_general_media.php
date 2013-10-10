<?php
//require_once './html.php';
require_once tentacle_root.'html.php';
require_once tentacle_root.'functions_images.php';
require_once tentacle_root.'test_speed.php';
require_once node_root.'tentacle_general_gallery.php';//need to use some of the functions from here

class tentacle_general_media{
	var $type='tentacle_general_media';
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
	
	function tentacle_general_media(){
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
		$path_id = (isset($_GET['pa']) && $_GET['pa'] != '') ? $_GET['pa'] : '';			//get the path
		//$dir_id = (isset($_GET['dir']) && $_GET['dir'] != '') ? $_GET['dir'] : '';//get the dir. path is the root folder. dir is the sub folders.	
		//$file_id = (isset($_GET['f']) && $_GET['f'] != '') ? $_GET['f'] : false;			//get the file name
		$key_id = (isset($_GET['k']) && $_GET['k'] != '') ? $_GET['k'] : 0 ;
		
		//----get the nav info from the id sent in		
		if($path_id && $key_id!='undefined'){
			//first tap into the power of the genreal gallery node, to create the array of files in the folders so that I can manipulate it all
			$functions = new tentacle_general_gallery(); //using this class just to reuse some of the functions that I'll need here
			$tmp = $functions->browse_dir($path_id);//get an array of the folder and files
			$tmp_tr = $functions->array_flatten($tmp);//flatten the array so I can use it better
			$tmp_tr = $functions->post_flatten_reconstruction($tmp_tr);
			
			$file_id = $tmp_tr[$key_id][0];
			$dir_id = $tmp_tr[$key_id][1];
			
			if( is_file($path_id.$dir_id.'/'.$file_id) ){//if this is a file that can be opened
				$this->create_directory($path_id.'/halfsize'.$dir_id);
				$creation = $this->create_thumbnails($file_id,$path_id,'/halfsize',0.5,$dir_id);
				$s.='<img '.$this->get_base_attributes($data,$nodes).' src='.$path_id.'/halfsize'.str_replace(' ','%20',$dir_id).'/'.$file_id.'></a>';
				
				$s.='<br>';
				if($creation>0) $s.='<div style="color:red">'.$creation.'</div><br>';
			}
			
			//now I need to build the page next and previous and home buttons
			
			$at = $this->get_base_attributes_p($data,$nodes);
			
			if($key_id>0){
				$s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id . '&pa='.$path_id.'&f=read&k='. strval($key_id-1) .'><div'.$at.'>&laquo;</div></a>';
			}else{
				$s.='<div'.$at.'>&nbsp;</div></a>';
			}
			$s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id .'><div'.$at.'>^</div></a>';
			if($key_id < count($tmp_tr)-1 ){
				$s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id . '&pa='.$path_id.'&f=read&k='. strval($key_id+1) .'><div'.$at.'>&raquo;</div></a>';
			}else{
				$s.='<div'.$at.'>&nbsp;</div></a>';
			}
			
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
	//---------------------
	function create_directory($dir){//make a directory
		if(!is_dir($dir)){
			mkdir($dir, 0777);
			chmod($dir, 0777);
		}
	}
	// $file_id,$path_id,'/halfsize',0.5,$dir_id)
	function create_thumbnails($f,$pa,$fo,$s,$o){//file, thumb folder, scale, original folder, //----for when I am actually makeing the thumb image. The other variables don;t put us where we need to be
		$ignore_type = array('mov','MOV','avi','AVI','mpg','MPG','mpeg','MPEG','flv','FLV','swf','SWF');
		$of = $pa.$o.'/'.$f;//original file name
		$nf = $pa.$fo.$o.'/'.$f;//thumbnail name if it exists
		$timer = new test_speed();
		$speed=0;
		if(!is_file($nf)){//if the file doesn't exists
		    //---test the speed and show it if we want to
			$timer->test_speed();
			$ext_array = explode('.',$f);//explode thefile name
			$ext = $ext_array[ count($ext_array) - 1];//get the extension
			if( array_search($ext,$ignore_type)===false ){
				$is = getimagesize($of);
				$tmp_s = $s;
				if($s>1){//if it is greater than one, then I am sending in a max width, not a scale, conver to scale relative to the sent in value
					$tmp_s = $s/$is[0];
				}
				$cropped = resizeThumbnailImage($nf, $of ,$is[0],$is[1],0,0,$tmp_s);
			}
			$speed=$timer->get_time();
		}
		return $speed;
	}
	//=====================
}
?>
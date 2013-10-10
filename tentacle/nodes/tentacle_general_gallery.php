<?php
session_start();
require_once tentacle_root.'html.php';
require_once tentacle_root.'functions_images.php';
require_once tentacle_root.'test_speed.php';
require_once tentacle_root.'mysql.php';

class tentacle_general_gallery{
	var $type='tentacle_general_gallery';
	var $result='';
	var $mode=0;
	var $index=0;
	var $left=0;
	var $top=0;
	
	var $maxwidth = 200;
	
	var $folder='/images';	
	var $perpage=5;//stack height of elements //25
	
	var $id = '';
	var $class = 'image_album';//this doesn't come through when brite forced from tentacle_page. So I fake it there too
	var $style = '';
	var $title = '';
	
	var $pid = '';
	var $pclass = 'nav_paging';//doesn't come with brute force from tentacle_page, mst be sent in fake array
	var $pstyle = '';
	var $ptitle = '';
	
	var $bid='';
	var $bclass='nav_paging_but';//doesn't come with brute force from tentacle_page, mst be sent in fake array
	var $bstyle='';
	var $btitle='';
	
	var $sid='';
	var $sclass='nav_paging_sel';//doesn't come with brute force from tentacle_page, mst be sent in fake array
	var $sstyle='';
	var $stitle='';
	
	function tentacle_general_gallery(){
	}
	function assemble_node(){	
		$s= node_header($this->index,$this->type,$this->mode);
		$s.=node_output('result',$this->result,$this->index);
		
		$s.=node_input('folder',$this->folder,$this->index);
		$s.=hidden_input('perpage',$this->perpage,$this->index);
		
		$s.=node_input('maxwidth',$this->maxwidth,$this->index);
		
		$s.=node_input('id',$this->id,$this->index);
		$s.=node_input('class',$this->class,$this->index);
		$s.=node_input('style',$this->style,$this->index);
		$s.=node_input('title',$this->title,$this->index);
		
		$s.=node_input('pid',$this->pid,$this->index);
		$s.=node_input('pclass',$this->pclass,$this->index);
		$s.=node_input('pstyle',$this->pstyle,$this->index);
		$s.=node_input('ptitle',$this->ptitle,$this->index);
		
		$s.=node_input('bid',$this->bid,$this->index);
		$s.=node_input('bclass',$this->bclass,$this->index);
		$s.=node_input('bstyle',$this->bstyle,$this->index);
		$s.=node_input('btitle',$this->btitle,$this->index);
		
		$s.=node_input('sid',$this->sid,$this->index);
		$s.=node_input('sclass',$this->sclass,$this->index);
		$s.=node_input('sstyle',$this->sstyle,$this->index);
		$s.=node_input('stitle',$this->stitle,$this->index);
		
		return $s;
	}
	function assign_values($values){
		$this->result='';
		$this->index=$values['index'];
		$this->mode=$values['mode'];
		$this->type=$values['type'];
		$this->left=$values['left'];
		$this->top=$values['top'];
	
		$this->maxwidth=$values['maxwidth'];
	
		$this->folder=$values['folder'];
		$this->perpage=$values['perpage'];
	
		$this->id=$values['id'];
		$this->class=$values['class'];
		$this->style=$values['style'];
		$this->title=$values['title'];
		
		$this->pid=$values['pid'];
		$this->pclass=$values['pclass'];
		$this->pstyle=$values['pstyle'];
		$this->ptitle=$values['ptitle'];
		
		$this->bid=$values['bid'];
		$this->bclass=$values['bclass'];
		$this->bstyle=$values['bstyle'];
		$this->btitle=$values['btitle'];
		
		$this->sid=$values['sid'];
		$this->sclass=$values['sclass'];
		$this->sstyle=$values['sstyle'];
		$this->stitle=$values['stitle'];
	}
	function render($data,$nodes){	
		//get the data from the url string
		$page_id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : 'default';			//get the passed node variable
		$per_page = (isset($_GET['pp']) && $_GET['pp'] != '') ? $_GET['pp'] : $data['perpage'];
		$page_num = (isset($_GET['p']) && $_GET['p'] != '') ? $_GET['p'] : 1;
		
		//check if we are logged in
		$logged_in = $this->check_login();
		
		//look through the folder and find files and clamp to the needed files per page
		$tmp = $this->browse_dir($data['folder'],$logged_in[0]);//get an array of the folder and files
		$tmp_tr = $this->array_flatten($tmp);//flatten the array so I can use it better
		$tmp_tr = array_slice($tmp_tr, (($page_num-1)*$per_page), $per_page,true);//the offest to trim the array to , true keeps the keys to the same numbers, so I can use them later
		$tmp_tr = $this->post_flatten_reconstruction($tmp_tr);
		
		$this->create_directory($data['folder'].'/thumbnails');
		$this->create_directory($data['folder'].'/halfsize');
		
		
		//$this->create_thumbnails($tmp_t,$data['folder'],'/thumbnails',$data['maxwidth']);//make them thumbnails if they need to be made
		$timer = new test_speed();
		$timer->test_speed();
		$this->create_thumbnails($tmp_tr,$data['folder'],'/thumbnails',$data['maxwidth']);//make them thumbnails if they need to be made
		$speed=$timer->get_time();
		if($speed>0.01) $speed_html.='<div style="color:red">'.$speed.'</div><br>';
		
		
		//now make the html
		$tmp_thumb_styles = $this->get_base_attributes($data,$nodes);
		//$tmp_s = $this->array_to_html($tmp_t,$data['folder'],$page_id,$tmp_thumb_styles);
		$tmp_s = $this->array_to_html($tmp_tr,$data['folder'],$page_id,$tmp_thumb_styles,$logged_in[0]);
				
		//-----paging junk
		$max_pages = ceil($this->count_elements($tmp)/$per_page);
		$s.= '<div'.$this->get_base_attributes_p($data,$nodes).'>';
		
		$at_b=$this->get_base_attributes_b($data,$nodes);
		$at_s=$this->get_base_attributes_s($data,$nodes);
		
		if($page_num>1){//if we are past the first page make a previous page button
			$page = $page_num -1;
			$s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id . '&p=' . $page .'&pp='.$per_page.'><div'.$at_b.'>&laquo;</div></a>';
		}else{
			$s.= '<div'.$at_b.'>&nbsp;</div>';
		}
		//echo 'Page ' . $pageNum . ' of ' . $maxPage . ' ';//show pages
		if($max_pages>1){//if there is more than one page
			for($page = 1; $page <= $max_pages; $page++){
				if($page == $page_num){
					$s.= '<div'.$at_s.'>' . $page. '</div>';
				}else{
					$s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id . '&p=' . $page .'&pp='.$per_page.'><div'.$at_b.'>'.$page.'</div></a>';
				}
			}
		}
		if($page_num < $max_pages){//as long as we are not on last page yet
			$page = $page_num + 1;
			$s.= '<a href=' . $_SERVER['PHP_SELF'] . '?id=' . $page_id . '&p=' . $page .'&pp='.$per_page.'><div'.$at_b.'>&raquo;</div></a>';
		}else{
			$s.= '<div'.$at_b.'>&nbsp;</div>';
		}
		
		$s.= '</div>';
		//----------------
		//$trash=$logged_in[0]?'true':'false';
		//this is wher I put in te log in stuff at the bottom
		if(!$logged_in[0]){
			$s.='<br><div style="color:white;background-color:red;margin-top:2px;">login disabled</div';
			//$s.=$logged_in[1];
		}
		
		//----------------
		//this is a debug sting //$s.='<br><div style="color:red">'.$this->array_to_string($tmp_tr).'</div>';
		
		$nodes[$data['index']]['result']=$tmp_s.$speed_html.$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
	function inspect($data){
		$data= $this->translate($data);//translate the data into an array that can be more easily used
		$s= property_header($this->index,$this->type);

		$s.=property_text_input('folder',$data['folder']);
		$s.=property_text_input('perpage',$data['perpage']);
		
		$s.=property_text_input('maxwidth',$data['maxwidth']);

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
		
		$s.=open_prop_attr_html($this->index.'_paging_button');//hidden
		if($data['bid']!='_in') $s.=property_text_input('bid',$data['bid']);
		if($data['bclass']!='_in') $s.=property_text_input('bclass',$data['bclass']);
		if($data['bstyle']!='_in') $s.=property_text_input('bstyle',$data['bstyle']);
		if($data['btitle']!='_in') $s.=property_text_input('btitle',$data['btitle']);
		$s.=close_prop_attr_html();//from the html.php

		$s.=open_prop_attr_html($this->index.'_paging_selected');//hidden
		if($data['sid']!='_in') $s.=property_text_input('sid',$data['sid']);
		if($data['sclass']!='_in') $s.=property_text_input('sclass',$data['sclass']);
		if($data['sstyle']!='_in') $s.=property_text_input('sstyle',$data['sstyle']);
		if($data['stitle']!='_in') $s.=property_text_input('stitle',$data['stitle']);
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
	//--------specific needs
	function browse_dir($folder,$logged_in=false){
		$ignore_type = array('mov','avi','mpg','mpeg','flv','swf');
		
		$files=array();//array to hold the files
		$dir = $folder.'/';																	//the directory where all the nodes are held
		$dh = opendir($dir);
		$i=0;
		while (false !== ($filename = readdir($dh)) ){
			if(substr($filename,0,1)!='.'){
				if(!is_dir($dir.$filename)){	//this is a file
					$ext_array = explode('.',$filename);//explode thefile name
					$ext = strtolower($ext_array[ count($ext_array) - 1]);//get the extension
					$fn = $ext_array[0];//get the file name only, obviously only works right if there is only 1 period on the file name 
					if( array_search($ext,$ignore_type)===false ){
						$private = (strpos($fn,'_HIDDEN')!=false)?true:false; 
						if($logged_in===true || $logged_in===false && $private ===false){
							$files[$i]=$filename;
							$i++;	
						}/*elseif($logged_in===false && $private ===false ){
							$files[$i]=$filename;
							$i++;
						}*/
					}
				}else{ //this is another dir
					if($filename != 'thumbnails' && $filename != 'halfsize'){//ignore the two utility folders
						$files[$filename] = $this->browse_dir($folder.'/'.$filename,$logged_in);//we are gonna recursively grab  all the files in folders and put them in the array too
					}
				}
			}
		}
		return $files;//return the array of files
	}
	function create_directory($dir){//make a directory
		if(!is_dir($dir)){
			mkdir($dir, 0777);
			chmod($dir, 0777);
		}
	}
	function create_thumbnails($a,$f,$fo,$s){//array, file, thumb folder, scale, original folder, //----for when I am actually makeing the thumb image. The other variables don;t put us where we need to be
		$ignore_type = array('mov','MOV','avi','AVI','mpg','MPG','mpeg','MPEG','flv','FLV','swf','SWF');
		
		while(list($k,$v)=each($a)){
			$fc = str_replace('_HIDDEN','',$v[0]);
			
			$nf = $f.'/'.$fo.$v[1];//new folder name
			$of = $f.$v[1].'/'.$fc;//original file name
			$nt = $f.'/'.$fo.$v[1].'/'.$fc;//thumbnail name if it exists
			
			$this->create_directory($nf);//make the new folder if it is needed.
			
			if(!is_file($nt)){//if the file doesn't exists
				$ext_array = explode('.',$v[0]);//explode thefile name
				$ext = $ext_array[ count($ext_array) - 1];//get the extension
				if( array_search($ext,$ignore_type)===false ){
					$is = getimagesize($of);
					$tmp_s = $s;
					if($s>1){//if it is greater than one, then I am sending in a max width, not a scale, conver to scale relative to the sent in value
						$tmp_s = $s/$is[0];
					}
					$cropped = resizeThumbnailImage($nt, $of ,$is[0],$is[1],0,0,$tmp_s);
				}
			}
		}
	}
	//-------
	function count_elements($a){//count the number of elements
		$n = 0;
		while(list($k,$v)=each($a)){
			if(is_array($v)){
				$n+=$this->count_elements($v);
			}else{
				$n+=1;
			}
		}
		return $n;
	}
	function array_to_string($a){//temp utility function to convert an array to a string I can use
		$s = '';
		while(list($k,$v)=each($a)){
			if(is_array($v)){
				$s.='<br>';
				$s.=$v.':'.$k.':<br>';
				$s.=$this->array_to_string($v);
			}else{
				$s.=$k.':'.$v.'<br>';
			}
		}
		return $s;
	}
	function array_flatten($a,$p='') {
	    if (!is_array($a)) {// nothing to do if it's not an array
	        return array($a.$p);//i'm just contructing a string with the file name first, and the directories it is nested in after, sperated by commas. This means that no fles or folders can have commas, it will break shit
	    }

	    $result = array();
		while(list($k,$v)=each($a)){
			$dir=$p;//get the directory, so I can attach it to the file name
			if(!is_numeric($k)){//as long as this key is not a basic index numeber, and an actual folder name
				$dir.='_____'.$k;
			}
	        $result = array_merge($result, $this->array_flatten($v,$dir));
	    }

	    return $result;
	}
	function post_flatten_reconstruction($a){//this is a patch function that take the flattened array, and splits the value at the commans, and re builds it in a more usefull to me way.
		$s=array();//new array to rebuild
		while(list($k,$v)=each($a)){
			$e = explode('_____',$v);//split at the commas
			$s[$k][0]=$e[0];
			//$s[count($s)][0]=$e[0];
			$c=0;//start counting
			$d='';//to hold the array of sub_directories
			while(list($ka,$va)=each($e)){
				if($c>0){//ignore the first value, as it is the file name and is used already
					$d.='/'.$va;//append to the directory
				}
				$c+=1;
			}
			$s[$k][1]=$d;
			//$s[count($s)-1][1]=$d;
		}
		return $s;
		//[ file_name, subdirectory ] ]
	}
	//-----------------------
	function array_to_html($a,$f,$id,$st,$logged_in){//array, folder, page id, style
		$s = '';
		
		if($logged_in){//this is the logged in version with editting capabilities
			$s.='<script>
				function checkbox_clicked(){
					alert("well shit");
				}
			</script>';
			
			while(list($k,$v)=each($a)){
				$s.='<div '.$st.'>';
				
				$nf = str_replace(' ','%20',$v[1]);//replace the 'space' with the html %20 representation
				$nn = str_replace('_HIDDEN','',$v[0]);//replace the '_HIDDEN in any files we are looking at'
				
				$s.='<a href="' . $_SERVER['PHP_SELF'] . '?id='.$id.'&pa='.$f.'&f=read&k='.$k.'"><img src='.$f.'/thumbnails'.$nf.'/'.$nn.' '.$st.'></a>';
			
				$ext_array = explode('.',$v[0]);//explode thefile name
				$private = (strpos($ext_array[0],'_HIDDEN')!=false)?true:false;
				$s.='<br><div style="margin-right:auto;margin-left:auto;width:20px;">';
				if($private===false){
					$s.='<input type="checkbox" value="hide" checked="true" onclick=checkbox_clicked()>';
				}else{
					$s.='<input type="checkbox" value="hide" onclick=checkbox_clicked()>';
				}
				$s.='</div></div>';
			}
			
		}else{//this is basic not logged in
			
			while(list($k,$v)=each($a)){
				$nf = str_replace(' ','%20',$v[1]);//replace the 'space' with the html %20 representation
				$s.='<a href="' . $_SERVER['PHP_SELF'] . '?id='.$id.'&pa='.$f.'&f=read&k='.$k.'"><img src='.$f.'/thumbnails'.$nf.'/'.$v[0].' '.$st.'></a>';
				//i have to send f=read, or anything in there that is text, cause in the tentacle page it check that that is a non number value to pass it to the tentacle general media node
			}
			
		}

		return $s;
	}
	//--------------
	//--------------
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
	//----------log in fun
	function check_login(){
		$l=false;
		$s='';
	    if (!isset($_SESSION['edit_isLogin']) || $_SESSION['edit_isLogin'] == false) {
			//we are NOT logged in
			$mysql = new mysql();
			//------if we are loging into tentacle
			if (isset($_POST['txtUserid'])) {
				$check_to = $mysql->get_user_password($_POST['txtUserid']);//get the user data
				if($check_to!='denied'){//we are a go, user exists
					$passwordhashed = sha1($_POST['txtUserpw']);
					if ($passwordhashed === $check_to) {
		        		$_SESSION["edit_isLogin"] = true;
		        		$_SESSION["edit_user"] = $_POST['txtUserid'];//this is here so I can send it back to tentacle cloud
						$s='LOGGED IN';
						$l=true;
		    		} else {
		        		$s.= 'wrong password';
		    		} 	
				}else{
					$s.= 'user does not exist';
				}     
			}else{
				//$s.='we are NOT logged in';
				$s.='<div style="margin-top:16px;">
					<form action="" method="post" name="frmLogin" id="frmLogin">
					<input name="txtUserid" type="text" id="txtUserid" value="user" style="width:80px">
					<input name="txtUserpw" type="password" id="txtUserpw" value="password" style="width:80px">
					<input type="submit" name="Submit" value="Submit">
					</form>
					</div>';
			}
	    }else{
			//WE ARE LOGGED IN!
			$l=true;
			$s.='Not sure I get here ever, but if we do I assume we are logged in';
		}
		return array($l,$s);
	}
	//------------
}
?>
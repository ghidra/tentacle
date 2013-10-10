<?php
require_once node_root.'node.php';
require_once tentacle_root.'html.php';
require_once tentacle_root.'mysql.php';

class tentacle_navigation extends node{
	var $type='tentacle_navigation';

	var $vstack=4;//stack height of elements
	var $showhidden='false';
	//styles for the nav buttons
	var $id='';
	var $class='nav_but';
	var $style='';
	var $title='';
	//styles for the selected nav buttons
	var $sid='';
	var $sclass='nav_sel';
	var $sstyle='';
	var $stitle='';
	//styles for the over all nav container
	var $pid='';
	var $pclass='nav_column';
	var $pstyle='';
	var $ptitle='';

	var $number_ports=0;

	var $tentacle_navigation_ports=array('tentacle navigation attributes'=>array(
			'vstack'=>array('type'=>'integer','exposed'=>0),
			'showhidden'=>array('type'=>'boolean','exposed'=>0)
		));
	var $tentacle_navigation_button_ports=array('navigation button attributes'=>array(
			'id'=>array('type'=>'string','exposed'=>0),
			'class'=>array('type'=>'string','exposed'=>0),
			'style'=>array('type'=>'string','exposed'=>0),
			'title'=>array('type'=>'string','exposed'=>0)
		));
	var $tentacle_navigation_selected_ports=array('selected attributes'=>array(
			'sid'=>array('type'=>'string','exposed'=>0),
			'sclass'=>array('type'=>'string','exposed'=>0),
			'sstyle'=>array('type'=>'string','exposed'=>0),
			'stitle'=>array('type'=>'string','exposed'=>0)
		));
	var $tentacle_navigation_container_ports=array(' attributes'=>array(
			'pid'=>array('type'=>'string','exposed'=>0),
			'pclass'=>array('type'=>'string','exposed'=>0),
			'pstyle'=>array('type'=>'string','exposed'=>0),
			'ptitle'=>array('type'=>'string','exposed'=>0)
		));
	
	function __construct(){
		$this->append('tentacle_navigation_ports');
		$this->append('tentacle_navigation_button_ports');
		$this->append('tentacle_navigation_selected_ports');
		$this->append('tentacle_navigation_container_ports');
	}
	function render($data,$nodes){
		$page_id=(isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : false;

		$mysql=new mysql();//get a new db object
		$mysql->grab_navigation();//get the navigation
		
		if($page_id){
			$mysql->grab_nav_row($page_id);
			$link=$mysql->nav_row['link'];//get the link of the page
			$type=$mysql->nav_row['type'];
		}else{
			$link='latest';
			$type='ind';
		}
		
		$s='';
		$vcount=0;//count for vertical stack
		$nav_number=count($mysql->navigation)-1;//number of nav items
		for($i=0;$i<=$nav_number;$i++){
			$midget=$mysql->navigation[$i];//shorthand the variable name
			if($midget['visibility'] || $data['showhidden']=='true'){//if visible nav item, or if we are showing hidden anyway
				if($vcount==0) $s.='<div'.$this->get_base_attributes_p($data,$nodes).'>'."\n";//first in the stack start the containing div

				if($midget['link']==$link){
					$s.='<div'.$this->get_base_attributes_s($data,$nodes).'>';
				}else{
					$s.='<div'.$this->get_base_attributes($data,$nodes).'>';
				}
				$s.= '<a href="' . $_SERVER['PHP_SELF'] . '?id=' . $midget['id'] . '">' . $midget['title'] . '</a>';
				$s.='</div>'."\n";
				$vcount++;
			}
			if($vcount>=$data['vstack'] || $i==$nav_number){//if the count is less than the stack so we can build them down
				$s.= '</div>'."\n";//end the stack div
				$vcount = 0;//start stack count again
			}
			//now there looks like one extra </div> at very end of nav
		}
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result		
		//$this->get_tag_assembled( $data,	$this->get_attributes($data) );
	}

	//--------
	function get_attribute_assembled($data,$nodes,$attr,$drop=false){
		//global $nodes;// this variable comes from the render.php execute function
		$s='';
		//if (strlen($data[$attr])>0) {
		if ($data[$attr]) {
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
	function get_base_attributes_s($data,$nodes){
		//global $nodes;// this variable comes from the render.php execute function
		$s='';//hold the tag data that is pligged in i guess
		
		$s.=$this->get_attribute_assembled($data,$nodes,'sid',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'stitle',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'sclass',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'sstyle',1);
		
		return $s;
	}
	function get_base_attributes_p($data,$nodes){//attributes for the parent
		//global $nodes;// this variable comes from the render.php execute function
		$s='';//hold the tag data that is pligged in i guess
		
		$s.=$this->get_attribute_assembled($data,$nodes,'pid',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'ptitle',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'pclass',1);
		$s.=$this->get_attribute_assembled($data,$nodes,'pstyle',1);
		
		return $s;
	}
}
?>
<?php
require_once tentacle_root.'ascii_logo.php';
require_once node_root.'node_html.php';

class html_page extends node_html{
	var $type='html_page';
	var $pagetitle='tentacle';
	var $head='';

	var $html_page_ports=array('html page attributes'=>array(
				'pagetitle'=>array('type'=>'string','exposed'=>0),
				'head'=>array('type'=>'string','exposed'=>0)
			));

	function __construct(){
		$this->append('html_page_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		$ascii_logo=new ascii_logo();
		//global $nodes;// this variable comes from the render.php execute function		
		$s='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'."\n";
		$s.='<html>'."\n";
		$s.='<head>'."\n";
		$s.=$ascii_logo->get_output();
		$s.="\t".'<title>'.$data['pagetitle'].'</title>'."\n";
		
		
		//if (strlen($data['head'])) {//something is killing the load here. Because the string is bogus or something.
			if(is_string($data['head'])){
				$s.=$data[$attr];
			}else{
				$s.=$nodes[$data['head']['index']][$data['port_head']];	
			}
		//}
		//$s.=$data['head'];
		
		$s.='</head>'."\n";
		$s.='<body'.$this->get_base_attributes($data,$nodes).'>'."\n";
		
		//$s.='<'.$this->format_options[$data['format']].$this->get_base_attributes($data).'>';
		
		/*for($i=0;$i<=$data['number_ports']-1;$i++){
			$s.= $nodes[$data['content'.$i]['index']][$data['port_content'.$i]];
		}*/
		$s.=$this->get_ports_data($data,$nodes);
		
		$s.="\n".'</body>'."\n";
		$s.='</html>'."\n";
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result	
	}
}
?>
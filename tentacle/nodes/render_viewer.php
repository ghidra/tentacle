<?php
//require_once './html.php';
require_once tentacle_root.'html.php';
//require_once tentacle_root.'mysql.php';

class render_viewer{
	var $type='render_viewer';
	var $result='';
	var $mode=0;
	var $index=0;
	var $left=0;
	var $top=0;
	var $number_ports=1;

	
	var $perpage=25;//stack height of elements
	
	var $id='';
	var $class='';
	var $style='';
	var $title='';
	
	function render_viewer(){
	}
	function assemble_node(){	
		$s= node_header($this->index,$this->type,$this->mode);
		$s.=$this->content_ports();//make the content ports
		$s.=node_inwait('content.',$this->index,$this->type);
		
		return $s;
	}
	function assign_values($values){
		$this->result='';
		$this->index=$values['index'];
		$this->mode=$values['mode'];
		$this->type=$values['type'];
		$this->left=$values['left'];
		$this->top=$values['top'];
	
		$this->number_ports=$values['number_ports'];
	}
	function render($data,$nodes){	
		$result='';
		for($i=0;$i<=$data['number_ports']-1;$i++){
			$result.= $nodes[$data['content'.$i]['index']][$data['port_content'.$i]];
		}
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
	function inspect($data){
		$s= property_header($this->index,$this->type);
		$s.='<div onmousedown="render_view('.$this->index.')">refresh</div><br><div id="render_viewer_'.$this->index.'"></div>';
		//$render=new render_handler();
		//$render->set_file('pages/index');
		//$render->execute($render->result);
		//$render->output($render->result[0]);//right now results is in an array. The out put only wants the one node to render
		return $s;
	}
	function open_node(){
		return open_node_html($this->index,$this->left,$this->top,$this->type);
	}
	function close_node(){
		return close_node_html();
	}
	//--------content node inputs
	function content_ports(){
		$p='';
		for($i=0;$i<=$this->number_ports-1;$i++){
			$p.=node_input('content'.$i,'',$this->index);
		}
		return $p;
	}
	//i don't even know if this is used. It is also in html_base
	function add_content(){
		return new_input('content'.$this->number_ports,'',$this->index);
	}
}
?>
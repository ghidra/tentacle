<?php
////im not sure this is even ever used
//or this page is called procedural

//require_once './html.php';
require_once node_root.'node.php';
require_once tentacle_root.'render_handler.php';

class render_page extends node{
	var $type='tentacle_page';
	
	//this node isn't in need of a javascript object so no need to __construct
	//function __construct(){}

	function render($data,$nodes){	
		$file = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 'media/pages/navigation/latest';			//get the passed node variable
		
		$s='';
		
		$render=new render_handler();

		if($file!='browse' && $file!='index'){
			$render->set_file($file);
			$render->execute($render->result);//send the execute node, to complie the code (This is in the render magic)
			$s.=$render->get_output($render->result[0]);
		}else{
			$s.=$render->browse();
		}
			
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>
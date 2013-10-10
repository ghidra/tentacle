<?php
require_once node_root.'node_css.php';

class background_base extends node_css{

	var $type='background';
	var $number_ports=0;
	
	var $repeat_options=array('repeat','repeat-x','repeat-y','no-repeat');
	var $attachment_options=array('scroll','fixed','inherit');
	var $position_options=array('top left','top center','top right','center left','center center','center right','bottom left','bottom center','bottom right');
	
	var $color='';
	var $image='';
	var $repeat='repeat';
	var $attachment='scroll';
	var $position='top left';

	var $background_base_ports = array('background attributes'=>array(
							'color'=>array('type'=>'color','exposed'=>0),
							'image'=>array('type'=>'string','exposed'=>1),
							'repeat'=>array('type'=>'dropdown','exposed'=>0),
							'attachment'=>array('type'=>'dropdown','exposed'=>0),
							'position'=>array('type'=>'dropdown','exposed'=>0)
						));

	function __construct(){
		parent::__construct();
	}
}
?>
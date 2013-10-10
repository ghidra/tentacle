<?php
require_once node_root.'node_css.php';

class border_base extends node_css{
	
	var $type='border';
	var $effect_options=array('border','border-bottom','border-left','border-right','border-top');
	var $width_options=array('thin','medium','thick','length');
	var $style_options=array('none','hidden','dotted','dashed','solid','double','groove','ridge','inset','outset');
	
	var $effect='border';
	var $width='thin';
	var $length=0;
	var $style='solid';
	var $color='';
	
	//var $measure_options=array('%','px','em','pt','pc','ex','cm','mm','in');
	var $measure='px';

	var $border_base_ports = array('border attributes'=>array(
							'effect'=>array('type'=>'dropdown','exposed'=>0),
							'width'=>array('type'=>'dropdown','exposed'=>0),
							'length'=>array('type'=>'scalar','exposed'=>0),
							'measure'=>array('type'=>'dropdown','exposed'=>0),
							'style'=>array('type'=>'dropdown','exposed'=>0),
							'color'=>array('type'=>'color','exposed'=>0)
						));

	function __construct(){
		parent::__construct();
	}
}
?>
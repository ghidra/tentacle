<?php
require_once node_root.'node_html.php';

class iframe extends node_html{
	var $type='iframe';

	var $src='';
	var $srcpath='local';
	var $longdesc='';
	var $descpath='local';
	var $frameborder='true';
	var $width=0;
	var $wmeasure='px';
	var $height=0;
	var $hmeasure='px';
	var $marginwidth=0;
	var $marginheight=0;
	var $noresize='false';
	var $scrolling='auto';

	var $srcpath_options=array('http://','http://www.','local');
	var $descpath_options=array('http://','http://www.','local');

	var $scrolling_options=array(
		'yes',
		'no',
		'auto'
	);
	var $wmeasure_options=array('px','%');
	var $hmeasure_options=array('px','%');

	var $iframe_ports=array('iframe attributes'=>array(
			'src'=>array('type'=>'string','exposed'=>1),
			'srcpath'=>array('type'=>'dropdown','exposed'=>0),
			'longdesc'=>array('type'=>'string','exposed'=>1),
			'descpath'=>array('type'=>'dropdown','exposed'=>0),
			'name'=>array('type'=>'string','exposed'=>1),
			'width'=>array('type'=>'scalar','exposed'=>0),
			'wmeasure'=>array('type'=>'dropdown','exposed'=>0),
			'height'=>array('type'=>'scalar','exposed'=>0),
			'hmeasure'=>array('type'=>'dropdown','exposed'=>0),
			'frameborder'=>array('type'=>'boolean','exposed'=>0),
			'marginwidth'=>array('type'=>'scalar','exposed'=>0),
			'marginheight'=>array('type'=>'scalar','exposed'=>0),
			'noresize'=>array('type'=>'boolean','exposed'=>0),
			'scrolling'=>array('type'=>'dropdown','exposed'=>0)
		));

	function __construct(){
		$this->append('iframe_ports');
		parent::__construct();
	}
	function render($data,$nodes){
		return parent::render( $data,$nodes,$this->get_attributes($data,$nodes) );
	}

	function get_attributes($data,$nodes,$local_attributes = '', $local_inner = ''){		
		$s= $this->get_link_attribute($data,$nodes,'src','srcpath');//attribute and path a p
		$s.= $this->get_link_attribute($data,$nodes,'longdesc','descpath');//attribute and path a p
		$s.=$this->get_attribute_assembled($data,$nodes,'name');

		if($data['frameborder']!='true') $s.=' frameborder="0"';
		if($data['noresize']!='false') $s.=' noresize="noresize"';

		if($data['marginwidth']>0) $s.=' marginwidth="'.$data['marginwidth'].'px"';
		if($data['marginheight']>0) $s.=' marginheight="'.$data['marginheight'].'px"';
		//if($data['scrolling']!='auto') $s.=$this->get_attribute_assembled($data,$nodes,'scrolling');

		if($data['width']>0) $s.=' width="'.$data['width'].$data['wmeasure'].'"';
		if($data['height']>0) $s.=' height="'.$data['height'].$data['hmeasure'].'"';

		return $s;

	}
	function get_link_attribute($data,$nodes,$a,$p){//this is basically a duplicate of parent onject get attribute asembled
		$s='';
		if (strlen($data[$a])>0) {
			$s.=' '.$a.'="';
			if($data[$p]!='local'){
				$s.=$data[$p];
			}
			if(is_string($data[$a])){
				$s.=$data[$a].'"';
			}else{
				$s.=$nodes[$data[$a]['index']][$data['port_'.$a]].'"';
			}
		}
		return $s;
	}
}
?>

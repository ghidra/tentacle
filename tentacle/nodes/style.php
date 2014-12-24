<?php
require_once node_root.'node_html.php';

class style extends node_html{
	var $type='style';
	var $media='screen';
	var $media_options=array(
		'screen',
		'tty',
		'tv',
		'projection',
		'handheld',
		'print',
		'braille',
		'aural',
		'all'
	);
	var $style_ports=array('style attributes'=>array(
			'media'=>array('type'=>'dropdown','exposed'=>0)
		));

	function __construct(){
		$this->append('style_ports');
		$this->append_ignore(array('id','class','title','style','accesskey','tabindex','dir','lang','xmllang'));
	}
	function render($data,$nodes,$local_attributes = '', $local_inner = ''){
		$s='<style type="text/css"';
		if($data['media']!='screen') $s.=' media="'.$data['media'].'"';
		$s.='>'."\n";
		for($i=0;$i<=$data['number_ports']-1;$i++){
			$s.= $nodes[$data['content'.$i]['index']][$data['port_content'.$i]];
		}
		$s.='</style>'."\n";

		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>

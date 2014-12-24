<?php
require_once node_root.'node_html.php';

class input extends node_html{
	var $type='input';

	var $input='text';
	var $size=20;
	var $disabled='false';

	var $src='';//for submit button
	var $path='local';
	var $readonly='false';//only for text input
	var $maxlength=20;//only for text input
	var $checked='false';//checkbox only
	var $alt='';//image only

	var $path_options=array(
		'http://',
		'http://www.',
		'local'
	);
	var $input_options=array(
		'button',
		'checkbox',
		'file',
		'hidden',
		'image',
		'password',
		'radio',
		'reset',
		'submit',
		'text'
	);
	var $number_ports=0;
	var $input_ports=array('input attributes'=>array(
			'input'=>array('type'=>'dropdown','exposed'=>0),
			'size'=>array('type'=>'scalar','exposed'=>0),
			'disabled'=>array('type'=>'boolean','exposed'=>0),
			'src'=>array('type'=>'string','exposed'=>1),
			'path'=>array('type'=>'dropdown','exposed'=>0),
			'readonly'=>array('type'=>'boolean','exposed'=>0),
			'maxlength'=>array('type'=>'integer','exposed'=>0),
			'checked'=>array('type'=>'boolean','exposed'=>0),
			'alt'=>array('type'=>'string','exposed'=>1),
		));

	function __construct(){
		$this->append('input_ports');
		parent::__construct();
	}
	function render($data,$nodes,$local_attributes = '', $local_inner = ''){
		//global $nodes;// this variable comes from the render.php execute function
		$s='<input type='.$data['input'].$this->get_base_attributes($data,$nodes);
		$s.=$this->get_attribute_assembled($data,$nodes,'value');
		$s.=$this->get_attribute_assembled($data,$nodes,'name');
		if($data['size']!=20) $s.=$this->get_attribute_assembled($data,$nodes,'size');
		if($data['disabled']!='false') $s.=' disabled="disabled"';

		if($data['input']=='text'){
			if($data['readonly']!='false') $s.=' readonly="readonly"';
			if($data['maxlength']!=20) $s.=$this->get_attribute_assembled($data,$nodes,'maxlength');
		}
		if($data['input']=='checkbox'){
			if($data['checked']!='false') $s.=' checked="checked"';
		}
		if($data['input']=='image'){
			$s.=$this->get_link($data);//get the src if this is a submit
		 	$s.=$this->get_attribute_assembled($data,$nodes,'alt');
		}
		$s.='/>';

		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
	//--------
	function get_link($data,$nodes){//this is basically a duplicate of parent onject get attribute asembled
		//global $nodes;// this variable comes from the render.php execute function
		$s='';
		if (strlen($data['src'])>0) {
			$s.=' src="';
			if($data['path']!='local'){
				$s.=$data['path'];
			}
			if(is_string($data['src'])){
				$s.=$data['src'].'"';
			}else{
				$s.=$nodes[$data['src']['index']][$data['port_src']].'"';
			}
		}
		return $s;
	}
}
?>

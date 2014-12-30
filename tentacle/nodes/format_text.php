<?php
require_once node_root.'node_html.php';

class format_text extends node_html{
	var $type='format_text';
	var $format='italic';
	var $format_options=array(
		'paragraph'=>'p',
		'span'=>'span',
		'quote'=>'q',
		'preformatted'=>'pre',
		'subscript'=>'sub',
		'superscript'=>'sup',
		'italic'=>'i',
		'bold'=>'b',
		'big'=>'big',
		'small'=>'small',
		'teletype'=>'tt',
		'emphasized'=>'em',
		'strong'=>'strong',
		'definition'=>'dfn',
		'computer code'=>'code',
		'sample computer code'=>'samp',
		'keyboard'=>'kbd',
		'variable'=>'var',
		'citation'=>'cite',
		'heading 1'=>'h1',
		'heading 2'=>'h2',
		'heading 3'=>'h3',
		'heading 4'=>'h4',
		'heading 5'=>'h5',
		'heading 6'=>'h6',
		'definition term'=>'dt',
		'definition'=>'dd',
		'list'=>'li'
	);
	var $format_text_ports=array('format text attributes'=>array(
			'format'=>array('type'=>'dropdown','exposed'=>0)
		));
	function __construct(){
		$this->append('format_text_ports');
		parent::__construct();
	}
	function render($data,$nodes,$local_attributes = '', $local_inner = ''){
		$s='<'.$this->format_options[$data['format']].$this->get_base_attributes($data,$nodes).'>';

		for($i=0;$i<=$data['number_ports']-1;$i++){
			if(array_key_exists('port_content'.$i,$data)){
				$s.= $nodes[$data['content'.$i]['index']][$data['port_content'.$i]];
			}
		}
		$s.='</'.$this->format_options[$data['format']].'>';

		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
}
?>

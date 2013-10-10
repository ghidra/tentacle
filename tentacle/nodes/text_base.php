<?php
require_once node_root.'node_css.php';

class text_base extends node_css{
	var $direction_options=array(
		'ltr',
		'rtl'
	);
	var $lineheight_options=array(
		'normal',
		'number',
		'length'
	);
	var $letterspacing_options=array(
		'normal',
		'length'
	);
	var $align_options=array(
		'left',
		'right',
		'center',
		'justify'
	);
	var $decoration_options=array(
		'none',
		'underline',
		'overline',
		'line-through',
		'blink'
	);
	var $transform_options=array(
		'none',
		'capitalize',
		'uppercase',
		'lowercase'
	);
	var $white_options=array(
		'normal',
		'pre',
		'nowrap'
	);
	var $wordspacing_options=array(
		'normal',
		'length'
	);
	
	var $color='';
	var $direction='ltr';
	var $lineheight='normal';
	var $llength=0;
	var $lmeasure_options=array();
	var $lmeasure='px';
	var $letterspacing='normal';
	var $lslength=0;
	var $lsmeasure_options=array();
	var $lsmeasure='px';
	var $align='left';
	var $decoration='none';
	var $indent=0;
	var $imeasure_options=array();
	var $imeasure='px';
	var $transform='none';
	var $white='normal';
	var $wordspacing='normal';
	var $wlength=0;
	var $wmeasure_options=array();
	var $wmeasure='px';
	
	//array('color','direction','direction_options','lineheight','lineheight_options','llength','lmeasure_options','lmeasure','letterspacing','letterspacing_options','lslength','lsmeasure_options','lsmeasure',
	//	'align','align_options','decoration','decoration_options','indent','imeasure_options','imeasure','transform','transform_options','white','white_options','wordspacing','wordspacing_options','wlength','wmeasure_options','wmeasure');

	function __construct(){
		$this->lmeasure_options = $this->clone_measure();
		$this->lsmeasure_options = $this->clone_measure();
		$this->imeasure_options = $this->clone_measure();
		$this->wmeasure_options = $this->clone_measure();

		parent::__construct();
	}
}
?>
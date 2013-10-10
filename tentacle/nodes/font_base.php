<?php
require_once node_root.'node_css.php';

class font_base extends node_css{
	var $type='font';
	
	var $style_options=array(
		'normal',
		'italic',
		'oblique'
	);
	var $weight_options=array(
		'normal',
		'bold',
		'bolder',
		'lighter',
		'100','200','300','400','500','600','700','800','900'
	);
	var $variant_options=array(
		'normal',
		'small-caps'
	);
	var $size_options=array(
		'length',
		'xx-small',
		'x-small',
		'small',
		'medium',
		'large',
		'x-large',
		'xx-large',
		'smaller',
		'larger'
	);
	var $family_options=array(
		'arial'=>						'Arial,sans-serif',
		'arial black'=>					'\'Arial Black\',sans-serif',
		'comic sans'=>					'\'Comic Sans MS\',cursive',
		'couier new'=>					'\'Courier New\',monospace',
		'georgia'=>						'Georgia,serif',
		'imapct'=>						'Impact,sans-serif',
		'lucida console/monaco'=>		'\'Lucida Console\',Monaco,monospace',
		'lucida sans/lucida grande'=>	'\'Lucida Sans Unicode\',\'Lucida Grande\',sans-serif',
		'palatino linotype/palatino'=>	'\'Palatino Linotype\',Palatino,serif',
		'tahomoa/geneva'=>				'Tahoma,Geneva,sans-serif',
		'time new roman/times'=>		'\'Time New Roman\',Times,serif',
		'trebuchet/helvetica'=>			'\'Trebuchet MS\',Helvetica,sans-serif',
		'symbol'=>						'Symbol,fatasy',
		'webdings'=>					'Webdings,fatasy',
		'ms serif/new york'=>			'\'MS Serif\',\'New York\',serif',
		'serif'=>						'serif',
		'sans serif'=>					'sans-serif',
		'cursive'=>						'cursive',
		'fantasy'=>						'fantasy',
		'monospace'=>					'monospace'
	);		
	//var $smeasure_options=$measure_options;
	//var $lmeasure_options=$measure_options;
	//var $family_options_hold=array();//needs this to split the array down to bare minimus so I can set it back later
	
	var $style='normal';
	var $variant='normal';
	var $weight='normal';
	var $size='length';
	var $slength=0;
	var $smeasure='px';
	var $smeasure_options=array();
	var $llength=0;
	var $lmeasure='px';
	var $lmeasure_options=array();
	var $family='monospace';

	//array('style','style_options','variant','variant_options','weight','weight_options','size','size_options','slength','smeasure','smeasure_options','llength','lmeasure','lmeasure_options','family','family_options');

	function __construct(){
		$this->smeasure_options=$this->clone_measure();
		$this->lmeasure_options=$this->clone_measure();

		parent::__construct();
	}
	//----------------
	/*function get_family_options(){//set the font family array keys as options
		$a=array();
		while(list($key,$val) = each($this->family_options)) {
			array_push($a, $key);
		}
		return $a;
	}*/	
	
}
?>
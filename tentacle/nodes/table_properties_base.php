<?php
require_once node_root.'node_css.php';

class table_properties_base extends node_css{

	var $collapse_options=array(
		'collapse',
		'seperate'
	);
	var $side_options=array(
		'top',
		'bottom',
		'left',
		'right'
	);
	var $cell_options=array(
		'show',
		'hide'
	);
	var $layout_options=array(
		'auto',
		'fixed'
	);
	
	var $collapse='collapse';
	var $hlength=0;
	var $hmeasure_options=array();
	var $hmeasure='px';
	var $vlength=0;
	var $vmeasure_options=array();
	var $vmeasure='px';
	var $side='top';
	var $cell='hide';
	var $layout='auto';
	//array('collapse','collapse_options','hlength','hmeasure_options','hmeasure','vlength','vmeasure_options','vmeasure','side','side_options','cell','cell_options','layout','layout_options');
	function __construct(){
		$this->hmeasure_options=$this->clone_measure();
		$this->vmeasure_options=$this->clone_measure();
		parent::__construct();
	}
}
?>
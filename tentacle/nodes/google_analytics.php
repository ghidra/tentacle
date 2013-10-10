<?php
require_once node_root.'node.php';

class google_analytics extends node{
	var $type='google_analytics';
	var $number_ports=0;
	
	var $code='new';
	var $code_options=array(
		'new'=>'ga',
		'legacy'=>'urchin'
		);
	var $id ='';
	var $google_analytics_ports=array('google analytics attributes'=>array(
			'code'=>array('type'=>'dropdown','exposed'=>0),
			'id'=>array('type'=>'string','exposed'=>1)
		));
	function __construct(){
		$this->append('google_analytics_ports');
	}
	function render($data,$nodes){
		if($data['code']=='new'){
			$s=$this->google_analytics_ga($data['id']);	
		}else if($data['code']=='legacy'){
			$s=$this->google_analytics_urchin($data['id']);	
		}
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
	//--------------------
	//---
	function google_analytics_ga($id_number){
		return '<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
			try {
			var pageTracker = _gat._getTracker("'.$id_number.'");
			pageTracker._trackPageview();
			} catch(err) {}</script>';
	}
	function google_analytics_urchin($id_number){
		return '<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
			</script>
			<script type="text/javascript">
			try {
			_uacct = "'.$id_number.'";
			urchinTracker();
			} catch(err) {}</script>';
	}
}
?>
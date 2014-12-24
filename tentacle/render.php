<?php
//REPORT ERRORS FOR DEBUGGIN
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

//
include_once 'path.php';
include_once 'render_handler.php';
/*******************************************************************************************************************************************/
$file = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 'browse';			//get the passed node variable
$header = (isset($_GET['header']) && $_GET['header'] != '') ? $_GET['header'] : 1;			//this is if we want to use a header. By default this is true, use a header
/*******************************************************************************************************************************************/
$render=new render_handler();

if($header){
	$render->set_file('media/pages/index');
	$render->execute($render->result);
	$render->output($render->result[0]);//right now results is in an array. The out put only wants the one node to render
}else{
	if($file!='browse'){//no rendering index twice, inifinte looooop
		//$script_speed= new test_speed();
		$render->set_file($file);
		$render->execute($render->result);//send the execute node, to complie the code (This is in the render magic)
		$render->output($render->result[0]);//right now results is in an array. The out put only wants the one node to render
		//echo 'script time: '.$script_speed->get_time();
	}else{
		echo $render->browse(0);
	}
}


?>

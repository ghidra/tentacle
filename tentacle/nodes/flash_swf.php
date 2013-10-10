<?php
//require_once './html.php';
require_once node_root.'node.php';

class flash_swf extends node{

	var $type='flash_swf';
	
	var $width=720;
	var $height=480;
	var $src='';
	var $id='';//the following three are all the same value as src
	var $name='';
	var $movie='';//movie is the only value that keeps the whole file path.
	
	var $quality='high';
	var $quality_options=array(
		'low'=>'low',
		'high'=>'high'
		);
	var $align='middle';
	var $align_options=array(
		'left'=>'left',
		'middle'=>'middle',
		'right'=>'right'
		);
	var $play='true';
	var $loop='true';
	var $scale='showall';
	var $scale_options=array(
		'showall'=>'showall'
		);
	var $wmode='window';
	var $wmode_options=array(
		'window'=>'window'
		);
	var $devicefont='false';
	var $bgcolor='#ffffff';
	var $menu='true';
	var $allowFullScreen='false';
	var $allowScriptAccess='sameDomain';
	var $allowScriptAccess_options=array(
		'sameDomain'=>'sameDomain'
		);

	var $flash_swf_ports=array('flash swf attributes'=>array(
				'src'=>array('type'=>'string','exposed'=>1),
				'width'=>array('type'=>'integer','exposed'=>0),
				'height'=>array('type'=>'integer','exposed'=>0)
			),
			'flash swf more attributes'=>array(
				'quality'=>array('type'=>'dropdown','exposed'=>0),
				'align'=>array('type'=>'dropdown','exposed'=>0),
				'play'=>array('type'=>'boolean','exposed'=>0),
				'loop'=>array('type'=>'boolean','exposed'=>0),
				'scale'=>array('type'=>'dropdown','exposed'=>0),
				'wmode'=>array('type'=>'boolean','exposed'=>0),
				'devicefont'=>array('type'=>'boolean','exposed'=>0),
				'bgcolor'=>array('type'=>'color','exposed'=>0),
				'menu'=>array('type'=>'boolean','exposed'=>0),
				'allowFullScreen'=>array('type'=>'boolean','exposed'=>0),
				'allowScriptAccess'=>array('type'=>'dropdown','exposed'=>0)
			)
		);

	function __construct(){
		$this->append('flash_swf_ports');
	}
	function render($data,$nodes){
		$my_src=explode('/',$data['src']);
		$my_name=explode('.',$my_src[ count($my_src)-1 ]);
		$shorten = $my_name[0];
		
		$my_src_ne=explode('.',$data['src']);
		$no_extension = $my_src_ne[0];
		
		
		$s='<script language="javascript">
				if (AC_FL_RunContent == 0) {
					alert("This page requires AC_RunActiveContent.js.");
				} else {
					AC_FL_RunContent(
						"codebase", "http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0",
						"width", "'.$data['width'].'",
						"height", "'.$data['height'].'",
						"src", "'.$shorten.'",
						"quality", "'.$data['quality'].'",
						"pluginspage", "http://www.macromedia.com/go/getflashplayer",
						"align", "'.$data['align'].'",
						"play", "'.$data['play'].'",
						"loop", "'.$data['loop'].'",
						"scale", "'.$data['scale'].'",
						"wmode", "'.$data['wmode'].'",
						"devicefont", "'.$data['devicefont'].'",
						"id", "'.$shorten.'",
						"bgcolor", "#'.$data['bgcolor'].'",
						"name", "'.$shorten.'",
						"menu", "'.$data['menu'].'",
						"allowFullScreen", "'.$data['allowFullScreen'].'",
						"allowScriptAccess","'.$data['allowScriptAccess'].'",
						"movie", "'.$no_extension.'",
						"salign", ""
						); //end AC code
				}
			</script>
			<noscript>
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="'.$data['width'].'" height="'.$data['height'].'" id="'.$shorten.'" align="'.$data['align'].'">
				<param name="allowScriptAccess" value="'.$data['allowScriptAccess'].'" />
				<param name="allowFullScreen" value="'.$data['allowFullScreen'].'" />
				<param name="movie" value="'.$data['src'].'" />
				<param name="quality" value="'.$data['quality'].'" />
				<param name="bgcolor" value="#'.$data['bgcolor'].'" />	
				<embed src="'.$data['src'].'" quality="'.$data['quality'].'" bgcolor="#'.$data['bgcolor'].'" width="'.$data['width'].'" height="'.$data['height'].'" name="'.$shorten.'" align="'.$data['align'].'" allowScriptAccess="'.$data['allowScriptAccess'].'" allowFullScreen="'.$data['allowFullScreen'].'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
				</object>
			</noscript>';
		
		$nodes[$data['index']]['result']=$s;
		return $nodes[$data['index']];//return the entire node, with the result
	}
	//--------------------
}
?>
<?php
require_once 'html.php';
/*include_once node_root.'node.php';
include_once node_root.'node_html.php';
include_once node_root.'node_css.php';
include_once node_root.'node.php';*/

class allowed_nodes{
	var $colors=array();//I'm not using this array right now, but I am keeping it here incase for now
		/*'core'=>array('#CD8C4E','#E1771E'),
		'html'=>array('#889917','#378C52'),
		'css'=>array('#CD6074','#D93F5B'),
		'author'=>array('#6879AE','#2A50BF'),
		'etc'=>array('#439659','#0CCC3D')
	);*/
	var $core=array(
		'tentacle_navigation'=>array('navigation'),
		'tentacle_page'=>array('page'),
		'tentacle_album'=>array('album','gallery'),
		//'tentacle_media'=>array('media','image','movie'),
		'tentacle_general_gallery'=>array('media','gallery')
		//'tentacle_general_media'=>array('media','image')
	);
	var $html=array(
		'a'=>array('link','href'),
		'abbr'=>array(''),
		'acronym'=>array(''),
		'address'=>array(''),
		'base'=>array(''),
		'blockquote'=>array(''),
		'br'=>array('break','line break'),
		'button'=>array(''),
		'format_text'=>array(''),
		'div'=>array(''),
		'fieldset'=>array(''),
		'form'=>array(''),
		'frame'=>array(''),
		'head_stack'=>array(''),
		'html_page'=>array(''),
		'hr'=>array(''),
		'iframe'=>array(''),
		'img'=>array(''),
		'input'=>array(''),
		'label'=>array(''),
		'legend'=>array(''),
		'link_ext'=>array(''),
		'lists'=>array(''),
		'map'=>array(''),
		'meta'=>array(''),
		'noframes'=>array(''),
		'noscript'=>array(''),
		'script'=>array(''),
		'style'=>array('')
	);
	var $css=array(
		'css_stack'=>array(''),
		'background'=>array(''),
		'background_attachment'=>array(''),
		'background_color'=>array(''),
		'background_image'=>array(''),
		'background_position'=>array(''),
		'background_repeat'=>array(''),
		'border'=>array(''),
		'border_collapse'=>array(''),
		'border_color'=>array(''),
		'border_spacing'=>array(''),
		'border_style'=>array(''),
		'border_width'=>array(''),
		'caption_side'=>array(''),
		'clear'=>array(''),
		'cursor'=>array(''),
		'display'=>array(''),
		'float'=>array(''),
		'position'=>array(''),
		'visibility'=>array(''),
		'dimension'=>array(''),
		'font'=>array(''),
		'font_family'=>array(''),
		'font_size'=>array(''),
		'font_style'=>array(''),
		'font_variant'=>array(''),
		'font_weight'=>array(''),
		'list_style'=>array(''),
		'list_style_position'=>array(''),
		'list_style_type'=>array(''),
		'margin'=>array(''),
		'outline'=>array(''),
		'outline_color'=>array(''),
		'outline_style'=>array(''),
		'outline_width'=>array(''),
		'padding'=>array(''),
		'clip'=>array(''),
		'overflow'=>array(''),
		'vertical_align'=>array(''),
		'table_properties'=>array(''),
		'empty_cells'=>array(''),
		'table_layout'=>array(''),
		'text_properties'=>array(''),
		'color'=>array(''),
		'direction'=>array(''),
		'line_height'=>array(''),
		'text_align'=>array(''),
		'text_decoration'=>array(''),
		'text_transform'=>array(''),
		'white_space'=>array('')
	);
	var $author=array(
		'text_long'=>array(''),
		'text_short'=>array('')
	);
	var $etc=array(
		'flash_swf'=>array(''),
		'google_analytics'=>array('')
	);
	var $ignore=array(
		'html_base'=>array(''),
		'execute'=>array(''),
		'db_image'=>array(''),
		'db_movie'=>array(''),
		'background_base'=>array(''),
		'border_base'=>array(''),
		'font_base'=>array(''),
		'list_style_base'=>array(''),
		'margin_padding_base'=>array(''),
		'outline_base'=>array(''),
		'table_properties_base'=>array(''),
		'text_base'=>array('')
	);
	
	function __construct(){
		//I want to grab the colors from the node files
		//var $core_colors = new node();
		//var $html_colors = new node_html();
		//var $css_colors = new node_css();

		$this->colors['core']=array($core_colors->color_main,$core_colors->color_alt);
		$this->colors['html']=array($html_colors->color_main,$html_colors->color_alt);
		$this->colors['css']=array($css_colors->color_main,$css_colors->color_alt);
		$this->colors['author']=array('#6879AE','#2A50BF');
		$this->colors['etc']=array('#439659','#0CCC3D');
	}
	function assemble_menu(){
		$s='<div id="dd_nodes" class="menu_dd_nav" onmouseover="tentacle.drop_down.cancel_close()" onmouseout="tentacle.drop_down.close()">';
		//$s.=open_node_menu_html('nodes:',true);//from the html.php
		foreach($this as $group => $array) {
			if($group!='colors' && $group!='ignore'){
								
				$s.=open_node_menu_html($group);//from the html.php
				foreach($array as $index => $data) {
					$s.=menu_node($index,$group);//from html.php
				}
				$s.=close_node_menu_html();//from the html.php
			}
		}
		//$s.=close_node_menu_html();//from the html.php
		$s.='</div>';
		return $s;
	}
	//------------------------------
	function get_group($type){
		$findings='';//to hold the findings
		foreach($this as $group => $array) {
			if($group!='colors'){
				foreach($array as $index => $data) {
					if($index==$type){
						$findings = $group;
					}	
				}
			}
		}
		if($findings=='ignore')$findings='core';
		return $findings;
	}
	//------------------------------
	function get_color($node_type,$color_index){
		$group=$this->get_group($node_type);
		return $this->colors[$group][$color_index];
	}
	//------------------------------
}
?>
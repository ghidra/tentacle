<?php
class ascii_logo{
	function ascii_logo(){
	}
	function get_output(){
		$s='<noscript><script language="javascript" type="text/javascript"> /*'."\n";
		$s.='       __         __'."\n";
		$s.='      / /\       / /\ '."\n";
		$s.='      \ \ \    __\_\/'."\n";
		$s.='       \ \ \  / /\/ /\ '."\n";
		$s.='   _____\_\ \/ / / /  \ '."\n";
		$s.='  / /        \/  \_\/\ \ '."\n";
		$s.='  \ \  ______/\   \/ / /'."\n";
		$s.='   \ \ \ __  \ \   \/ /'."\n";
		$s.='    \_\// /\  \ \    /'."\n";
		$s.='        \_\/   \ \  /'."\n";
		$s.='                \_\/'."\n";
		$s.='      2oo9 nervegass'."\n"."\n";
		$s.='*/</script></noscript>'."\n";
		return $s;
	}
}
?>
<?php
//----------------------http://www.helicron.net/php/
/*$localpath=getenv("SCRIPT_NAME");
$absolutepath=realpath(basename($localpath));
// a fix for Windows slashes
$absolutepath=str_replace("\\","/",$absolutepath);
$docroot=substr($absolutepath,0,strpos($absolutepath,$localpath));
//get the site root relative to doc root and this file
$localpath_sans_filename = explode('/',$localpath);//split out the file
$localpath_sans_tentacledir = substr($localpath,0,strpos($localpath,$localpath_sans_filename[count($localpath_sans_filename)-1]));
//----------------------
$site_root_folder = substr($localpath_sans_tentacledir,0,strrpos($localpath_sans_tentacledir, 'tentacle/')).'';
*/
$site_root_folder = '/tentacle/';
$tentacle_root_folder = $site_root_folder.'tentacle/';





define('document_root',$_SERVER['DOCUMENT_ROOT'].'/');
//define('document_root',$docroot.'/');
define('page_root',document_root.substr($site_root_folder,1) );
define('tentacle_root',document_root.substr($tentacle_root_folder,1) );
define('node_root',tentacle_root.'nodes/');

/*print '<H1>'.document_root.'</H1>';
print '<H1>'.page_root.'</H1>';
print '<H1>'.tentacle_root.'</H1>';
print '<H1>'.node_root.'</H1>';*/
?>

<?php
header('Content-type: text/css');

$s='/* CSS Document */
body {
	font-family:"Courier New", Courier, monospace;
	font-size: 12px;
	/*color:white;*/
	/*background: url(../images/ng_pattern.jpg) repeat-y;*/
	background-color: black;
}
/*******************Header**********************/
.head_logo{float:left;color:black}
.head_logo a:link{color:black; text-decoration: none;}
.head_logo a:visited{color:black; text-decoration: none;}
.head_logo a:hover{color:black; text-decoration: none;}
/************************************************/
a:link img{ border: 1px solid black; }
a:visited img{ border: 1px solid black; }
a:hover img{ border: 1px solid red; }
/*---------------------Navigation Styles----------------------------*/
.nav_column{float:left; margin:0 0 0 2px;}
.nav_column a:link{text-decoration: none; color: white;}
.nav_column a:visited{text-decoration: none; color: white;}
.nav_column a:hover{background:#888888;text-decoration: none; color: white;}
.nav_sel{ background-color:red; clear:left; float:left; margin-bottom:1px; text-decoration: none; color: white;}
.nav_but{ background-color:#666666;clear:left; float:left; margin-bottom:1px; text-decoration: none; color: white; }
/*----------------------content Sytles---------------------------*/
.image_album{max-width:80px;/*max-height:60px;*/ border: 1px solid black; padding-left:-1px; }
.image_page{max-width:460px; border: 1px solid black;}
/*--------------pageing format--------*/
.nav_paging{margin:2 0 0 1px;}
.nav_paging_sel{ background-color:red; float:left; margin-right:2px; text-decoration: none; color: white;}
.nav_paging_but{ background-color:#666666; float:left; margin-right:2px; text-decoration: none; color: white; }
.nav_paging_but:hover{ background-color:#888888;}
.nav_paging a:link{background:#444444;margin-right:1px; text-decoration: none; color: white;}
.nav_paging a:visited{background:#666666;margin-right:1px; text-decoration: none; color: white;}
.nav_paging a:hover{background:#888888;margin-right:1px; text-decoration: none; color: white;}';

print $s;

?>
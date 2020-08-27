<?php

$categories = mysql_select("SELECT id,name FROM online_olympiads_categories order by name asc",'array');

$table = array(
	'id'		=>	'rank:desc id',
	'name'		=>	'',
	'category'	=>	'<a href="/admin.php?m=online_olympiads_categories&id={category}">{oc_name}</a>',
//	'price'		=>	'right',
//	'price2'	=>	'right',
//	'price3'	=>	'right',
	'rank'		=>	'',
	'display'	=>	'boolean'
);

$mytype=93;
include('online_olympiads.php');

?>